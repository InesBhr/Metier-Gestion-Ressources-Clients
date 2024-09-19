<?php

namespace App\Controller\Workflow;

use App\Entity\Workflow\Rejects42C;
use App\Entity\Workflow\Rejects42L;
use App\Entity\Workflow\AnomaliesBAN;
use App\Entity\Workflow\AnomaliesSPN;
use App\Entity\Workflow\AnomalieType;
use App\Entity\Workflow\AnomalieState;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkflowController extends AbstractController
{
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    /**
     * Controller constructor
     *
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
    ) {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    /**
     * A function that gets all the anomalies types/states
     *
     * @return JsonResponse
     */
    public function getAnomalies(
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        $anomalieStateRepository = $this->entityManager->getRepository(AnomalieState::class);
        $anomalieState = $anomalieStateRepository->findOneBy(['name' => $request->query->get('state')]);

        $anomalieTypeRepository = $this->entityManager->getRepository(AnomalieType::class);
        $anomalieType = $anomalieTypeRepository->findOneBy(['name' => $request->query->get('type')]);
        $anomaliesClass = $request->query->get('class');

        $repository = $entityManager->getRepository("App\\Entity\\Workflow\\$anomaliesClass");
        if ($anomaliesClass === 'AnomaliesBAN') {
            $data = $repository->findBy(['anomalieState' => $anomalieState]);
        } else {
            $data = $repository->findBy(['anomalieState' => $anomalieState, 'anomalieType' => $anomalieType]);
        }
        $jsonContent = $this->serializer->serialize($data, 'json');
        return new JsonResponse($jsonContent, 200, []);
    }

    /**
     * A function that gets all the anomalies types/states
     *
     * @return JsonResponse
     */
    public function getRejects(
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        $anomalieStateRepository = $this->entityManager->getRepository(AnomalieState::class);
        $rejectState = $anomalieStateRepository->findOneBy(['name' => $request->query->get('state')]);
        $rejectsClass = $request->query->get('class');

        $repository = $entityManager->getRepository("App\\Entity\\Workflow\\$rejectsClass");
        $data = $repository->findBy(['rejectState' => $rejectState]);

        $jsonContent = $this->serializer->serialize($data, 'json');
        return new JsonResponse($jsonContent, 200, []);
    }


    /**
     * A function that updates the rights for a specific file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStateBan(Request $request): JsonResponse
    {
        $anomalieStateRepository = $this->entityManager->getRepository(AnomalieState::class);
        $anomaliesBanRepo = $this->entityManager->getRepository(AnomaliesBAN::class);
        $anomalieState = $anomalieStateRepository->findOneBy(['id' =>
        json_decode($request->getContent())->state]);

        $idList = json_decode($request->getContent(), true)['idList'];
        foreach ($idList as $id) {
            $anomalieBan = ($anomaliesBanRepo->findOneBy(['id' => $id]));
            $anomalieBan->setAnomalieState($anomalieState);
            $this->entityManager->persist($anomalieBan);
        }
        $this->entityManager->flush();
        $jsonContent = $this->serializer->serialize($anomalieBan, 'json');
        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * A function that updates the rights for a specific file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStateSpn(Request $request): JsonResponse
    {

        $anomalieStateRepository = $this->entityManager->getRepository(AnomalieState::class);
        $anomaliesSpnRepo = $this->entityManager->getRepository(AnomaliesSPN::class);
        $anomalieState = $anomalieStateRepository->findOneBy(['id' =>
        json_decode($request->getContent())->state]);

        $idList = json_decode($request->getContent(), true)['idList'];
        foreach ($idList as $id) {
            $anomalieSpn = ($anomaliesSpnRepo->findOneBy(['id' => $id]));
            $anomalieSpn->setAnomalieState($anomalieState);
            $this->entityManager->persist($anomalieSpn);
        }

        $this->entityManager->flush();
        $jsonContent = $this->serializer->serialize($anomalieSpn, 'json');
        return new JsonResponse($jsonContent, 200, [], true);
    }

    /**
     * A function that updates the rights for a specific file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateState42C(Request $request): JsonResponse
    {

        $anomalieStateRepository = $this->entityManager->getRepository(AnomalieState::class);
        $rejects42CRepo = $this->entityManager->getRepository(Rejects42C::class);
        $anomalieState = $anomalieStateRepository->findOneBy(['id' =>
        json_decode($request->getContent())->state]);

        $idList = json_decode($request->getContent(), true)['idList'];
        foreach ($idList as $id) {
            $reject42C = ($rejects42CRepo->findOneBy(['id' => $id]));
            $reject42C->setRejectState($anomalieState)->setDateTraitement(new \DateTime);
            $this->entityManager->persist($reject42C);
        }

        $this->entityManager->flush();
        $jsonContent = $this->serializer->serialize($reject42C, 'json');
        return new JsonResponse($jsonContent, 200, [], true);
    }

    public function updateState42L(Request $request): JsonResponse
    {

        $anomalieStateRepository = $this->entityManager->getRepository(AnomalieState::class);
        $rejects42LRepo = $this->entityManager->getRepository(Rejects42L::class);
        $anomalieState = $anomalieStateRepository->findOneBy(['id' =>
        json_decode($request->getContent())->state]);

        $idList = json_decode($request->getContent(), true)['idList'];
        foreach ($idList as $id) {
            $reject42L = ($rejects42LRepo->findOneBy(['id' => $id]));
            $reject42L->setRejectState($anomalieState)->setDateTraitement(new \DateTime);
            $this->entityManager->persist($reject42L);
        }

        $this->entityManager->flush();
        $jsonContent = $this->serializer->serialize($reject42L, 'json');
        return new JsonResponse($jsonContent, 200, [], true);
    }
}
