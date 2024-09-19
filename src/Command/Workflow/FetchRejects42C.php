<?php

namespace App\Command\Workflow;

use PharData;
use Exception;
use App\Helper\S3FileManager;
use RecursiveIteratorIterator;
use App\Entity\Workflow\Rejects42C;
use App\Entity\Workflow\TaskLock;
use Symfony\Component\Finder\Finder;
use App\Entity\Workflow\AnomalieState;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:fetch-rejects-42c',
    description: 'A command that fetches Rejects42C from S3 to the DB',
)]
class FetchRejects42C extends Command
{
    private EntityManagerInterface $entityManager;
    protected string $tempDirectory;
    private S3FileManager $s3FileManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ContainerBagInterface $containerBag,
        ParameterBagInterface $params
    ) {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->tempDirectory = $containerBag->get('kernel.project_dir') . "\\var\\tmp";

        if (!is_dir($this->tempDirectory)) {
            mkdir($this->tempDirectory);
        }

        $this->s3FileManager = new S3FileManager(
            $params->get('osi_storage')['host'],
            'eu-west-3',
            $params->get('osi_storage')['access_key_id'],
            $params->get('osi_storage')['secret_access_key'],
            $params->get('osi_storage')['bucket']
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Fetch Rejects 42C');

        $today = new \DateTime('today');

        if ($this->isTaskLocked($today)) {
            // Task is already running or has been executed
            return Command::FAILURE;
        }

        $this->lockTask($today);

        try {
            $filePath = $this->fetchFileFromS3($io);
            $rejectState = $this->getAnomaliesState();
            $this->decompressAndExtract($io, $filePath);
            $csvFilePath = $this->concatenateCsv($io);
            $this->storeDataFromCsv($io, $csvFilePath, $rejectState);
            unlink($csvFilePath);

            $io->success('Rejects 42C fetched successfully.');
            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }
    }

    private function isTaskLocked(\DateTime $date): bool
    {
        if (($this->entityManager
            ->getRepository(TaskLock::class)
            ->findOneBy(['name' => "fetch_rejects_42c", 'scheduledAt' => $date])) == null) {
            return false;
        } else {
            return true;
        }
    }

    private function lockTask(\DateTime $date): void
    {
        $taskLock = new TaskLock();
        $taskLock->setName("fetch_rejects_42c")
            ->setStatus("Done")
            ->setScheduledAt($date);

        $this->entityManager->persist($taskLock);
        $this->entityManager->flush();
    }


    private function fetchFileFromS3(SymfonyStyle $io): string
    {
        $yesterdayDate = str_replace('-', '', date('d-m-Y', strtotime("yesterday")));
        $fileExtension = '.tar.gz';
        $filePath =  $this->tempDirectory . '\\fichiers_rejetTypeC_global_' . $yesterdayDate . $fileExtension;
        $keyPath = 'transferts/rejets/fichiers_rejetTypeC_global_' . $yesterdayDate . $fileExtension;

        $this->s3FileManager->downloadFile($keyPath, $filePath);
        $io->writeln("File Downloaded.");
        return $filePath;
    }

    private function getAnomaliesState(): AnomalieState
    {
        return $this->entityManager
            ->getRepository(AnomalieState::class)
            ->findOneBy(['name' => "ATraiter"]);
    }

    private function decompressAndExtract(SymfonyStyle $io, string $filePath): void
    {
        $phar = new PharData($filePath);
        $tarPath = str_replace('.tar.gz', '.tar', $filePath);
        $phar->decompress();
        unlink($filePath);
        $io->writeln("File decompressed.");

        $tar = new PharData($tarPath);
        $tar->extractTo($this->tempDirectory);
        unlink($tarPath);

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->tempDirectory, false));
        foreach ($iterator as $file) {
            if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'tar') {
                $innerTar = new PharData($file->getPathname());
                $innerTar->extractTo($this->tempDirectory);
                unlink($file->getPathname());
            }
        }
        $io->writeln("Files extracted.");
    }

    private function concatenateCsv(SymfonyStyle $io): string
    {
        $outputFile = $this->tempDirectory . DIRECTORY_SEPARATOR . 'Rejects42C_' . date('Ymd') . '.csv';
        $finder = new Finder();

        $csvFile = fopen($outputFile, 'w');
        $headers = ["Base", "Date_Rejet", "Infos_Site", "Operation"];
        fputcsv($csvFile, $headers, ";");

        $finder->files()->in($this->tempDirectory)->name('*.hst');
        foreach ($finder as $file) {
            $this->processHstFile($file, $csvFile);
        }

        fclose($csvFile);
        $io->writeln('CSV File created and completed');

        return $outputFile;
    }

    private function processHstFile(SplFileInfo $file, $csvFile): void
    {
        $base = "Base : " . (strtoupper(substr($file->getFilename(), 0, 2)));
        $filePath = $file->getRealPath();
        $fileContent = file_get_contents($filePath);

        if (preg_split('/\n{2,}/', $fileContent) != '') {
            $sections = preg_split('/\n{2,}/', $fileContent);
            $firstDivision = substr($sections[0], 0, stripos($sections[0], 'Code'));
            $secondDivision = str_replace("\n", '', substr($sections[0], stripos($sections[0], 'Code') + 17));
            $sections = array($base . $firstDivision . $secondDivision);
        }
        foreach ($sections as $section) {
            if (preg_split('/\n/', $section) != '') {
                $line =  preg_split('/\n/', $section);
            }

            foreach ($line as $key => $elem) {
                if ($key == 3) {
                    break;
                }
                if ($key == 2) {
                    $dataLine = explode(":", $line[$key]);
                    $line[$key] = substr($dataLine[1], 0, 5) . $dataLine[2];
                    break;
                } else {
                    $dataLine = explode(":", $line[$key]);
                    $line[$key] = trim($dataLine[1]);
                }
            }
            fputcsv($csvFile, array_filter($line), ";");
        }
        unlink($filePath);
    }

    private function storeDataFromCsv(SymfonyStyle $io, string $csvFilePath, AnomalieState $rejectState): void
    {
        $row = 0;
        $fileHeader = [];
        $csvFile = fopen($csvFilePath, 'r');

        while (($data = fgetcsv($csvFile, 1000, ";")) !== false) {
            if ($row === 0) {
                $fileHeader = array_flip($data);
                $row++;
                continue;
            }

            $reject42C = new Rejects42C();
            $correctDateString = substr($data[$fileHeader["Date_Rejet"]], 0, 6) . '20' . substr($data[$fileHeader["Date_Rejet"]], 6, 2);
            $reject42C->setBase($data[$fileHeader["Base"]])
                ->setDateRejet(\DateTime::createFromFormat('d/m/Y', $correctDateString))
                ->setInfosSite($data[$fileHeader["Infos_Site"]])
                ->setOperation($data[$fileHeader["Operation"]])
                ->setRejectState($rejectState);

            $this->entityManager->persist($reject42C);
        }

        $this->entityManager->flush();
        fclose($csvFile);
        $io->writeln("Data stored.");
    }
}
