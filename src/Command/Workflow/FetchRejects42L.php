<?php

namespace App\Command\Workflow;

use PharData;
use Exception;
use App\Helper\S3FileManager;
use RecursiveIteratorIterator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use App\Entity\Workflow\Rejects42L;
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
    name: 'app:fetch-rejects-42l',
    description: 'A command that fetches Rejects42L from S3 to the DB',
)]
class FetchRejects42L extends Command
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
        $this->tempDirectory = $containerBag->get('kernel.project_dir') . "\\var\\tmp\\42L\\";

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
        $io->title('Fetch Rejects 42L');

        $today = new \DateTime('today');

        if ($this->isTaskLocked($today)) {
            // Task is already running or has been executed
            return Command::FAILURE;
        }

        $this->lockTask($today);

        try {
            $this->fetchFileFromS3($io);
            $rejectState = $this->getAnomaliesState();
            $this->concatenateCsv($rejectState, $io);

            $io->success('Rejects 42L fetched successfully.');
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
            ->findOneBy(['name' => "fetch_rejects_42l", 'scheduledAt' => $date])) == null) {
            return false;
        } else {
            return true;
        }
    }

    private function lockTask(\DateTime $date): void
    {
        $taskLock = new TaskLock();
        $taskLock->setName("fetch_rejects_42l")
            ->setStatus("Done")
            ->setScheduledAt($date);

        $this->entityManager->persist($taskLock);
        $this->entityManager->flush();
    }


    private function fetchFileFromS3(SymfonyStyle $io): string
    {
        $yesterdayDate = date('Ymd', strtotime("yesterday"));
        $fileExtension = '.xls';

        $filePath =  $this->tempDirectory . 'UPR_SE_' . $yesterdayDate . $fileExtension;
        $keyPath = '42l/RTC/RES_ANNEXES/UPR_SE_' . $yesterdayDate . $fileExtension;
        $this->s3FileManager->downloadFile($keyPath, $filePath);

        $filePath =  $this->tempDirectory . 'UPR_REU_' . $yesterdayDate . $fileExtension;
        $keyPath = '42l/RTC/RES_ANNEXES/UPR_REU_' . $yesterdayDate . $fileExtension;
        $this->s3FileManager->downloadFile($keyPath, $filePath);

        $filePath =  $this->tempDirectory . 'UPR_IDF_' . $yesterdayDate . $fileExtension;
        $keyPath = '42l/RTC/RES_ANNEXES/UPR_IDF_' . $yesterdayDate . $fileExtension;
        $this->s3FileManager->downloadFile($keyPath, $filePath);

        $filePath =  $this->tempDirectory . 'UPR_CAR_' . $yesterdayDate . $fileExtension;
        $keyPath = '42l/RTC/RES_ANNEXES/UPR_CAR_' . $yesterdayDate . $fileExtension;
        $this->s3FileManager->downloadFile($keyPath, $filePath);

        $io->writeln("Files Downloaded.");
        return $filePath;
    }

    private function getAnomaliesState(): AnomalieState
    {
        return $this->entityManager
            ->getRepository(AnomalieState::class)
            ->findOneBy(['name' => "ATraiter"]);
    }


    private function concatenateCsv(AnomalieState $rejectState, SymfonyStyle $io): void
    {
        $files = glob($this->tempDirectory . '*.xls');

        foreach ($files as $file) {
            $spreadsheet = $this->loadSpreadsheet($file);

            foreach ($spreadsheet->getSheetNames() as $sheetName) {
                $sheetData = $this->getSheetData($spreadsheet, $sheetName);
                if (empty($sheetData)) {
                    continue;
                }

                foreach ($sheetData as $line) {
                    if ($this->shouldSkipLine($line)) {
                        continue;
                    }

                    $reject42L = $this->createReject42L($file, $line, $rejectState);
                    if ($reject42L !== null) {
                        $this->entityManager->persist($reject42L);
                    }
                }
            }
        }

        $this->entityManager->flush();
        $io->writeln("Data 42L stored.");
    }

    private function loadSpreadsheet(string $file): Spreadsheet
    {
        $reader = new Xls();
        return $reader->load($file);
    }

    private function getSheetData(Spreadsheet $spreadsheet, string $sheetName): array
    {
        $sheet = $spreadsheet->getSheetByName($sheetName);
        $data = $sheet->toArray();
        array_shift($data);  // Remove header row
        return $data;
    }

    private function shouldSkipLine(array $line): bool
    {
        return str_contains($line[15], "OUV") || str_contains($line[13], 'VO_M') || str_contains($line[13], 'RO_M')
            || ((str_contains($line[12], 'A') || str_contains($line[12], 'D')) && str_contains($line[13], 'situation_incorrecte_B'))
            || str_contains($line[13], 'AncienNDtransmis:inexistantdanslabase') || str_contains($line[13], "LeNDtransmisdoitetreegalal'ancienNDtransmis");
    }

    private function createReject42L(string $file, array $line, AnomalieState $rejectState): ?Rejects42L
    {
        $reject42L = new Rejects42L();

        if (!str_contains($file, "IDF")) {
            if (($line[2] == null) || (substr($line[2], -1) === '*')) {
                return null;
            }
            $reject42L->setDateMvt($line[0])
                ->setCentre($line[1])
                ->setNd($line[2])
                ->setNe($line[3])
                ->setNumeroMvt((int) $line[4])
                ->setCodeSituation($line[7]);
        } else {
            if (($line[3] == null) || (substr($line[3], -1) === '*')) {
                return null;
            }
            $reject42L->setDr($line[0])
                ->setDateMvt($line[1])
                ->setCentre($line[2])
                ->setNd($line[3])
                ->setNe($line[4])
                ->setTypeNe($line[7]);
        }

        return $reject42L->setCodeMouvement($line[5])
            ->setRepartiteur($line[6])
            ->setAncienNd($line[8])
            ->setCodeAdresseRattachement($line[9])
            ->setCodeOperateurConcurrent($line[10])
            ->setTypePortabilite($line[11])
            ->setIndicateurBlocage($line[12])
            ->setInfo1($line[13])
            ->setInfo2($line[14])
            ->setA054($line[15])
            ->setRejectState($rejectState);
    }
}
