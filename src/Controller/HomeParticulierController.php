<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeParticulierController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em,Security $security)
    {
        $this->em=$em;
        
    }
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
