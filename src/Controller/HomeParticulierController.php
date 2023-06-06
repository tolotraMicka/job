<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeParticulierController extends AbstractController
{
    /**
     * @Route("/particulier", name="app_home_particulier")
     */
    public function index(): Response
    {
        return $this->render('particulier/accueil.html.twig', [
            'controller_name' => 'HomeParticulierController',
        ]);
    }
}
