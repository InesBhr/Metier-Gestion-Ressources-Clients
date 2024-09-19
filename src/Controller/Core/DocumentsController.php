<?php

namespace App\Controller\Core;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class DocumentsController extends AbstractController
{
    /**
     * @param Security $security
     *
     * @return Response
     */
    public function documentation(Security $security): Response
    {
        return $this->render('documentation/documentation.html.twig', ['user' => $security->getUser()]);
    }
}
