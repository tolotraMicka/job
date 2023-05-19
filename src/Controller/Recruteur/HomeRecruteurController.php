<?php

namespace App\Controller\Recruteur;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeRecruteurController extends AbstractController
{
    /**
     * @Route("/home/recruteur", name="app_home_recruteur")
     */
    public function index(): Response
    {
        return $this->render('recruteur/home/indexRecruteur.html.twig', [
            'controller_name' => 'HomeRecruteurController',
        ]);
    }
}
