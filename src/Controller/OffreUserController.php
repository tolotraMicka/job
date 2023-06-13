<?php

namespace App\Controller;

use App\Entity\Type;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class OffreUserController extends AbstractController
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
     * @Route("/offre_user", name="offre_user")
     */
    public function index(Request $request): Response
    {
        $repository = $this->entityManager->getRepository(Type::class);
        $types = $repository->findAll();
        
        $offres = $this->repository->selectOffre("User");

        if($request->query->get('mot_cle')) {
            $condition = $this->former_condition_recherche($request->query->get('mot_cle'));
            $offres = $this->repository->selectOffre("User", $condition);
        }

        return $this->render('offre_user/index.html.twig', [
            'controller_name' => 'OffreUserController',
            'variables' => ['offres' => $offres, 'types' => $types]
        ]);
    }

    /**
     * @Route("/recherche_offre_user", name="recherche_offre_user")
     */
    public function recherche_offre_user(Request $request): Response
    {
        $condition = $this->former_condition_recherche_jquery($request);
        $offres = $this->repository->selectOffre("User", $condition);

        $view = $this->renderView('offre_user/ajax/offre_recherche.html.twig', [
            'controller_name' => 'OffreController',
            'variables' => ['offres' => $offres]
        ]);

        // Convertissez les données en JSON et créez une réponse JSON
        $json = json_encode($view);
        $response = new JsonResponse($json, 200, [], true);

        return $response;
    }

    public function former_condition_recherche($input){
        $condition = " and (titre like '%$input%' or societe like '%$input%' or type.nom like '%$input%') ";

        return $condition;
    }

    public function former_condition_recherche_jquery($request){
        $condition = "";

        foreach ($request->query->all() as $key => $value) {
            if($key == 'type' && $value != "") {
                $condition .= " and type.id = ".$value;
            }
            if($key == 'temps' && $value != "") {
                $condition .= " and temps = ".$value;
            }
            if($key == 'publication' && $value != "") {
                $date_today = date('Y-m-d');
                $cor = [1 => '-3 day', 2 => '-7 day', 3 => '-14 day'];
                $date_before = date("Y-m-d", strtotime($cor[$value]));

                $condition .= " and date_publication between '$date_before' and '$date_today' ";
            }
        }

        return $condition;
    }
}
