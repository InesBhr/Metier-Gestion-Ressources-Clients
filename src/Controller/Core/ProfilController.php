<?php

namespace App\Controller\Core;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class ProfilController extends AbstractController
{
    /**
     * @param Security $security
     *
     * @return Response
     */
    public function index(Security $security): Response
    {
        return $this->render('core/profil.html.twig', ['user' => $security->getUser()]);
    }
}
