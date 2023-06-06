<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\PetitBoulot;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;


class PetitBoulotController extends AbstractController
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    /**
     * @Route("/petit_boulot", name="app_petit_boulot")
     */
    public function index(): Response
    {
        $sql = "SELECT pb.*, r.email nom_recruteur, cat.nom as nom_categorie, pb.id as id 
                FROM petit_boulot pb
                LEFT JOIN recruteur r on pb.id_recruteur = r.id
                LEFT JOIN categorie cat on cat.id = pb.categorie 
                WHERE id_recruteur = ".$this->security->getUser()->getId()." ";

        $connection = $this->entityManager->getConnection();
        $statement = $connection->executeQuery($sql);
        $petit_boulots = $statement->fetchAllAssociative();

        return $this->render('particulier/petit_boulot/index.html.twig', [
            'controller_name' => 'PetitBoulotController',
            'variables' => ['petit_boulots' => $petit_boulots]
        ]);
    }

    /**
     * @Route("/creer_petit_boulot", name="creer_petit_boulot")
     */
    public function create() 
    {
        $repository = $this->entityManager->getRepository(Categorie::class);
        $results = $repository->findAll();

        return $this->render('particulier/petit_boulot/create.html.twig', [
            'controller_name' => 'PetitBoulotController',
            'variables' => ['categories' => $results]
        ]);
    }

    /**
     * @Route("/sauvegarder_petit_boulot", name="sauvegarder_petit_boulot")
     */
    public function save(Request $request) 
    {
        $data = [
            'titre' => $request->request->get('titre'),
            'date_publication' => new DateTime(),
            'date_fin' => $request->request->get('date_fin'),
            'date_debut' => $request->request->get('date_debut'),
            'description' => $request->request->get('description'),
            'categorie' => $request->request->get('categorie'),
            'salaire' => $request->request->get('salaire'),
            'id_recruteur' => $this->security->getUser()->getId()
        ];

        if($request->request->get('id') > 0) {
            $repo = $this->entityManager->getRepository(PetitBoulot::class);
            $petit_boulot = $repo->find($request->request->get('id'));
        }
        else {
            $petit_boulot = new PetitBoulot();
        }

        $petit_boulot->setTitre($data['titre']);
        $petit_boulot->setDatePublication($data['date_publication']);
        $petit_boulot->setDateDebut(new DateTime($data['date_debut']));
        $petit_boulot->setDateFin(new DateTime($data['date_fin']));
        $petit_boulot->setDescription($data['description']);
        $petit_boulot->setCategorie($data['categorie']);
        $petit_boulot->setSalaire($data['salaire']);
        $petit_boulot->setIdRecruteur($data['id_recruteur']);
        $petit_boulot->setDone(0);
        
        $this->entityManager->persist($petit_boulot);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_petit_boulot');
    }

    /**
     * @Route("/description_petit_boulot", name="description_petit_boulot")
     */
    public function description_petit_boulot(Request $request)
    {
        $sql = "SELECT description FROM petit_boulot WHERE id = ".$request->request->get('id');
        $connection = $this->entityManager->getConnection();
        $statement = $connection->executeQuery($sql);
        $petit_boulot = $statement->fetchAllAssociative();

        foreach ($petit_boulot as $value) {
            $description = $value['description'];
        }

        // Convertissez les données en JSON et créez une réponse JSON
        $json = json_encode($description);
        $response = new JsonResponse($json, 200, [], true);

        return $response;
    }

    /**
     * @Route("/cloturer_petit_boulot", name="cloturer_petit_boulot")
     */
    public function cloturer_petit_boulot(Request $request)
    {
        $repo = $this->entityManager->getRepository(PetitBoulot::class);
        $petit_boulot = $repo->find($request->request->get('id'));

        $petit_boulot->setDone(1);
        $this->entityManager->persist($petit_boulot);
        $this->entityManager->flush();

        // Convertissez les données en JSON et créez une réponse JSON
        $json = json_encode("Petit boulot cloturée avec succès");
        $response = new JsonResponse($json, 200, [], true);

        return $response;
    }

    /**
     * @Route("/modifier_petit_boulot/{id}", name="modifier_petit_boulot")
     */
    public function update($id) 
    {
        $repository = $this->entityManager->getRepository(Categorie::class);
        $results = $repository->findAll();

        $repo = $this->entityManager->getRepository(PetitBoulot::class);
        $petit_boulot = $repo->find($id);

        return $this->render('particulier/petit_boulot/create.html.twig', [
            'controller_name' => 'petit_boulotController',
            'variables' => ['categories' => $results, 'id' => $id, 'petit_boulot' => $petit_boulot]
        ]);
    }
    
}
