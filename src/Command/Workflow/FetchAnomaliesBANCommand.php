<?php

namespace App\Command\Workflow;

use Exception;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use App\Entity\Workflow\TaskLock;
use App\Entity\Workflow\AnomaliesBAN;
use App\Entity\Workflow\AnomalieState;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



#[AsCommand(
    name: 'app:fetch-anomalies-ban',
    description: 'A command that fetches AnomaliesBAN from S3 to the DB',
)]
class FetchAnomaliesBANCommand extends Command
{

    /**
     * Undocumented variable
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    private S3Client $s3Client;

    /**
     * Undocumented function
     *
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface $params
     */
    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => 'eu-west-3',
            'endpoint' => $_ENV['OUTILGRC_S3_HOST'],
            'credentials' => new Credentials(
                $_ENV['OUTILGRC_S3_ACCESS_KEY_ID'],
                $_ENV['OUTILGRC_S3_SECRET_ACCESS_KEY']
            ),
            'http' => ['verify' => false]
        ]);
    }

    /**
     * Undocumented function
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Fetch Anomalies BAN');

        $today = new \DateTime('today');

        if ($this->isTaskLocked($today)) {
            // Task is already running or has been executed
            return Command::FAILURE;
        }

        $this->lockTask($today);

        try {
            /**
             * Fetch file from Bucket S3
             */
            $yesterdayDate = date('d-m-Y', strtotime("yesterday"));
            $object = $this->s3Client->getObject(array(
                'Bucket' => $_ENV['OUTILGRC_S3_BUCKET'],
                'Key' => 'anomalies-grc/Anomalies_BAN_' . $yesterdayDate . '.csv',
            ));
            $io->writeln("File Downloaded.");

            /**
             * Get Anomalies State
             */
            $anomalieState = $this->entityManager
                ->getRepository(AnomalieState::class)
                ->findOneBy(['name' => "ATraiter"]);

            /**
             * Read content file and store data
             */
            $row = 0;
            $fileHeader = [];
            $stream = fopen('data://text/plain,' . $object['Body']->getContents(), 'r');
            while (($data = fgetcsv($stream, 1000, ";")) !== false) {

                if ($row === 0) // Is Header
                {
                    $fileHeader = array_flip($data);
                    $row++;
                    continue;
                }

                $anomaliesBan = new AnomaliesBAN();
                $anomaliesBan->setUpr($data[$fileHeader["UPR"]])
                    ->setCodeBan($data[$fileHeader["CodeBan"]])
                    ->setCode42C($data[$fileHeader["Code42C"]])
                    ->setNra($data[$fileHeader["NRA"]])
                    ->setSgtqs($data[$fileHeader["SGTQS"]])
                    ->setNd($data[$fileHeader["ND"]])
                    ->setTypePorta($data[$fileHeader["TypePorta"]])
                    ->setCrn($data[$fileHeader["CRN"]])
                    ->setOp($data[$fileHeader["OP"]])
                    ->setDatePorta(\DateTime::createFromFormat('d/m/Y H:i:s', $data[$fileHeader["DatePorta"]]))
                    ->setAnomalieState($anomalieState);

                $this->entityManager->persist($anomaliesBan);
            }

            $this->entityManager->flush();
            $io->writeln("Data stored.");

            $io->success('Anomalies BAN fetched successfully.');

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
            ->findOneBy(['name' => "fetch_anomalies_ban", 'scheduledAt' => $date])) == null) {
            return false;
        } else {
            return true;
        }
    }

    private function lockTask(\DateTime $date): void
    {
        $taskLock = new TaskLock();
        $taskLock->setName("fetch_anomalies_ban")
            ->setStatus("Done")
            ->setScheduledAt($date);

        $this->entityManager->persist($taskLock);
        $this->entityManager->flush();
    }
}
