<?php

namespace App\Controller\Recruteur;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeRecruteurController extends AbstractController
{
    /**
    * @Route("/recruteur_accueil", name="app_home_recruteur")
    */
    public function index(): Response
    {
        return $this->render('recruteur/accueil.html.twig', [
            'controller_name' => 'HomeRecruteurController',
        ]);
    }
}
