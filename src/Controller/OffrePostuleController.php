<?php

namespace App\Controller;

use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class OffrePostuleController extends AbstractController
{
    private $entityManager;
    private $security;
    private $registry;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, Security $security, ManagerRegistry $registry)
    {
        $this->repository = new OffreRepository($registry,$security,$entityManager);
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->registry = $registry;
    }
    
    /**
     * @Route("/offre/postule", name="app_offre_postule")
     */
    public function index(): Response
    {
        return $this->render('offre_postule/create.html.twig', [
            'controller_name' => 'OffrePostuleController',
        ]);
    }

    /**
     * @Route("/postuler_offre", name="postuler_offre")
     */
    public function postuler_offre(Request $request): Response
    {
        //si un utilisateur est connecté
        if(!is_null($this->security->getUser()->getId())) {
            return $this->render('offre_user/offre_postule/create.html.twig', [
                'controller_name' => 'OffrePostuleController',
            ]);
        }
        else{
            return $this->redirectToRoute('app_connexion_user');
        }
    }
}
