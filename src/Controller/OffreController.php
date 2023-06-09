<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\Offre;
use App\Entity\Type;
use App\Repository\OffreRepository;
use DateTime as GlobalDateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class OffreController extends AbstractController
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
     * @Route("/offre", name="offre")
     */
    public function index(): Response
    {
        $offres = $this->repository->selectOffre();

        return $this->render('recruteur/offre/index.html.twig', [
            'controller_name' => 'OffreController',
            'variables' => ['offres' => $offres]
        ]);
    }

    /**
     * @Route("/creer_offre", name="creer_app_offre")
     */
    public function create() 
    {
        $repository = $this->entityManager->getRepository(Type::class);
        $results = $repository->findAll();

        //retourner un ajax pour la liste des missions
        $liste_missions = $this->listeMissions();

        return $this->render('recruteur/offre/create.html.twig', [
            'controller_name' => 'OffreController',
            'variables' => ['types' => $results, 'liste_missions' => $liste_missions]
        ]);
    }

    /**
     * @Route("/sauvegarder_offre", name="sauvegarder_app_offre")
     */
    public function save(Request $request) 
    {

        if($request->request->get('id') > 0) {
            //prendre l'offre qui existe dans la BDD
            $repo = $this->entityManager->getRepository(Offre::class);
            $offre = $repo->find($request->request->get('id'));
        }
        else {
            $offre = new Offre();
        }

        $offre->setTitre($request->request->get('titre'));
        $offre->setDatePublication(new GlobalDateTime($request->request->get('date_publication')));
        $offre->setDateFin(new GlobalDateTime($request->request->get('date_fin')));
        $offre->setDetail($request->request->get('detail'));
        $offre->setType($request->request->get('type'));
        $offre->setSalaireMin($request->request->get('salaire_min'));
        $offre->setSalaireMax($request->request->get('salaire_max'));
        $offre->setTemps($request->request->get('temps'));
        $offre->setIdRecruteur($this->security->getUser()->getId());
        $offre->setDone(0);
        
        $this->entityManager->persist($offre);
        $this->entityManager->flush();

        //ajout dans la table mission
        $offreId = $request->request->get('id') ? $request->request->get('id') : $offre->getId();
        $this->ajoutMission($offreId, $request);

        return $this->redirectToRoute('offre');
    }

    //ajout de mission par rapport à une offre
    public function ajoutMission($newOffreId, $request) {
        if ($request->request->get('id') > 0) {
            $repo = $this->entityManager->getRepository(Mission::class);
            $missions = $repo->findBy(['id_offre' => $request->request->get('id')]);
        
            foreach ($missions as $mission) {
                $this->entityManager->remove($mission);
            }
        }
        
        for ($i = 0; $i < count($request->request->get('missions')); $i++) {
            $mission = new Mission();
            $mission->setIdOffre($newOffreId);
            $mission->setNom($request->request->get('missions')[$i]);
        
            $this->entityManager->persist($mission);
        }
        
        $this->entityManager->flush();
    }

    //selectionner les missions d'une offre lors de la mise à jour
    public function listeMissions($id = NULL) {
        $repo = $this->entityManager->getRepository(Mission::class);
        $liste_missions = $repo->findBy(['id_offre' => $id]);

        return $this->render('recruteur/ajax/liste_missions.html.twig', [
            'controller_name' => 'OffreController',
            'variables' => ['liste_missions' => $liste_missions, 'id' => $id]
        ]);
    }
    /**
     * @Route("/detail_offre", name="detail_offre")
     */
    public function detail_offre(Request $request)
    {
        $offre = $this->repository->selectDetail($request);
        foreach ($offre as $value) {
            $detail = $value['detail'];
        }

        // Convertissez les données en JSON et créez une réponse JSON
        $json = json_encode($detail);
        $response = new JsonResponse($json, 200, [], true);

        return $response;
    }

    /**
     * @Route("/modifier_offre/{id}", name="modifier_offre")
     */
    public function update($id) 
    {
        $repository = $this->entityManager->getRepository(Type::class);
        $results = $repository->findAll();

        //retourner un ajax pour la liste des missions
        $liste_missions = $this->listeMissions($id);

        $repo = $this->entityManager->getRepository(Offre::class);
        $offre = $repo->find($id);

        return $this->render('recruteur/offre/create.html.twig', [
            'controller_name' => 'OffreController',
            'variables' => ['types' => $results, 'id' => $id, 'offre' => $offre, 'liste_missions' => $liste_missions]
        ]);
    }

    /**
     * @Route("/cloturer_offre", name="cloturer_offre")
     */
    public function cloturer_offre(Request $request)
    {
        $repo = $this->entityManager->getRepository(Offre::class);
        $offre = $repo->find($request->request->get('id'));

        $offre->setDone(1);
        $this->entityManager->persist($offre);
        $this->entityManager->flush();

        // Convertissez les données en JSON et créez une réponse JSON
        $json = json_encode("Offre cloturée avec succès");
        $response = new JsonResponse($json, 200, [], true);

        return $response;
    }
    
}
