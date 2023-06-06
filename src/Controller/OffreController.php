<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Type;
use DateTime as GlobalDateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class OffreController extends AbstractController
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    /**
     * @Route("/offre", name="offre")
     */
    public function index(): Response
    {
        $sql = "SELECT offre.*, r.societe, type.nom as nom_type, offre.id as id FROM offre
                LEFT JOIN recruteur r on r.id = offre.id_recruteur
                LEFT JOIN type on type.id = offre.type 
                WHERE id_recruteur = ".$this->security->getUser()->getId()." ";
        $connection = $this->entityManager->getConnection();
        $statement = $connection->executeQuery($sql);
        $offres = $statement->fetchAllAssociative();

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

        return $this->render('recruteur/offre/create.html.twig', [
            'controller_name' => 'OffreController',
            'variables' => ['types' => $results]
        ]);
    }

    /**
     * @Route("/sauvegarder_offre", name="sauvegarder_app_offre")
     */
    public function save(Request $request) 
    {
        $data = [
            'titre' => $request->request->get('titre'),
            'date_publication' => new GlobalDateTime(),
            'date_fin' => $request->request->get('date_fin'),
            'detail' => $request->request->get('detail'),
            'type' => $request->request->get('type'),
            'salaire_min' => $request->request->get('salaire_min'),
            'salaire_max' => $request->request->get('salaire_max'),
            'temps' => $request->request->get('temps'),
            'id_recruteur' => $this->security->getUser()->getId()
        ];

        $offre = new Offre();

        $offre->setTitre($data['titre']);
        $offre->setDatePublication($data['date_publication']);
        $offre->setDateFin(new GlobalDateTime($data['date_fin']));
        $offre->setDetail($data['detail']);
        $offre->setType($data['type']);
        $offre->setSalaireMin($data['salaire_min']);
        $offre->setSalaireMax($data['salaire_max']);
        $offre->setTemps($data['temps']);
        $offre->setIdRecruteur($data['id_recruteur']);
        
        $this->entityManager->persist($offre);
        $this->entityManager->flush();

        return $this->redirectToRoute('offre');

    }

    /**
     * @Route("/detail_offre", name="detail_offre")
     */
    public function detail_offre(Request $request)
    {
        $sql = "SELECT detail FROM offre WHERE id = ".$request->request->get('id');
        $connection = $this->entityManager->getConnection();
        $statement = $connection->executeQuery($sql);
        $offre = $statement->fetchAllAssociative();

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

        $repo = $this->entityManager->getRepository(Offre::class);
        $offre = $repo->find($id);

        return $this->render('recruteur/offre/create.html.twig', [
            'controller_name' => 'OffreController',
            'variables' => ['types' => $results, 'id' => $id, 'offre' => $offre]
        ]);
    }
    
}
