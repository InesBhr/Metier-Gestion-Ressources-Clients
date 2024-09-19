<?php

namespace App\Controller\Core;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 *
 */
class HealthAction
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        if (!$this->getDatabaseStatus()) {
            return new Response('Database unavailable', 503, ['Content-Type' => 'text/plain']);
        }

        return new Response('200 ok', 200, ['Content-Type' => 'text/plain']);
    }

    /**
     * Database status return
     *
     * @return bool
     */
    private function getDatabaseStatus(): bool
    {
        try {
            $connection = $this->entityManager->getConnection();
            return !(!$connection->isConnected() && !$connection->connect());
        } catch (Throwable $e) {
            return false;
        }
    }
}
