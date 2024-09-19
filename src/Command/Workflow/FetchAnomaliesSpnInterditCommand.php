<?php

namespace App\Command\Workflow;

use Exception;
use App\Helper\S3FileManager;
use App\Entity\Workflow\TaskLock;
use App\Entity\Workflow\AnomaliesSPN;
use App\Entity\Workflow\AnomalieType;
use App\Entity\Workflow\AnomalieState;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:fetch-anomalies-spn-interdit',
    description: 'A command that fetches AnomaliesSpnInterdit from S3 to the DB',
)]
class FetchAnomaliesSpnInterditCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private S3FileManager $s3FileManager;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
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
        $io->title('Fetch Anomalies SPN Interdit');
        $today = new \DateTime('today');

        if ($this->isTaskLocked($today)) {
            return Command::FAILURE;
        }

        $this->lockTask($today);

        try {
            $content = $this->s3FileManager->getContentFile($this->getS3FileName());
            $io->writeln("Content downloaded.");

            $anomalieState = $this->entityManager
                ->getRepository(AnomalieState::class)
                ->findOneBy(['name' => "ATraiter"]);

            $anomalieType = $this->entityManager
                ->getRepository(AnomalieType::class)
                ->findOneBy(['name' => $this->getAnomalieType()]);

            $row = 0;
            $fileHeader = [];
            $stream = fopen('data://text/plain,' . $content, 'r');

            while (($data = fgetcsv($stream, 1000, ";")) !== false) {
                if ($row === 0) {
                    $fileHeader = array_flip($data);
                    $row++;
                    continue;
                }

                $anomaliesSpn = new AnomaliesSPN();
                $anomaliesSpn->setUpr($data[$fileHeader["UPR"]])
                    ->setCodeServeur42L($data[$fileHeader["CodeServeur42L"]])
                    ->setNd($data[$fileHeader["ND"]])
                    ->setTypePorta($data[$fileHeader["TypePorta"]])
                    ->setCrn($data[$fileHeader["CRN"]])
                    ->setZ0bpq((int) $data[$fileHeader["Z0BPQ"]])
                    ->setAnomalieType($anomalieType)
                    ->setAnomalieState($anomalieState);

                $datePorta = \DateTime::createFromFormat('d/m/Y', (string) $data[$fileHeader["DatePorta"]]);
                if ($datePorta !== false) {
                    $anomaliesSpn->setDatePorta($datePorta);
                }

                $this->entityManager->persist($anomaliesSpn);

                if ($row % 1000 === 0) {
                    $this->entityManager->flush();
                }

                $row++;
            }

            $this->entityManager->flush();
            $io->writeln("Data stored.");

            $io->success('Anomalies SPN Interdit fetched successfully.');

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }
    }

    private function getS3FileName(): string
    {
        $yesterdayDate = date('d-m-Y', strtotime("yesterday"));
        return 'anomalies-grc/Anomalies_ND_Interdit_42L_' . $yesterdayDate . '.csv';
    }

    private function getAnomalieType(): string
    {
        return 'Interdit';
    }

    private function isTaskLocked(\DateTime $date): bool
    {
        $name = "fetch_anomalies_spn_interdit";

        return $this->entityManager
            ->getRepository(TaskLock::class)
            ->findOneBy(['name' => $name, 'scheduledAt' => $date]) !== null;
    }

    private function lockTask(\DateTime $date): void
    {
        $name = "fetch_anomalies_spn_interdit";

        $taskLock = new TaskLock();
        $taskLock->setName($name)
            ->setStatus("Done")
            ->setScheduledAt($date);

        $this->entityManager->persist($taskLock);
        $this->entityManager->flush();
    }
}
