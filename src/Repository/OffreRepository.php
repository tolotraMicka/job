<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    private $security;
    private $entityManager;

    public function __construct(ManagerRegistry $registry, Security $security, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        parent::__construct($registry, Offre::class);
    }

    public function add(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function selectOffre($type = "", $conditionInParams ="")
    {
        if($type == "User") {
            $condition = " WHERE done <> 1 ";
        }
        else{
            $condition = " WHERE id_recruteur = ".$this->security->getUser()->getId() ." ";
        }
        $sql = "SELECT offre.*, r.societe, type.nom as nom_type, offre.id as id FROM offre
                LEFT JOIN recruteur r on r.id = offre.id_recruteur
                LEFT JOIN type on type.id = offre.type $condition $conditionInParams ";
        $connection = $this->entityManager->getConnection();
        $statement = $connection->executeQuery($sql);
        $offres = $statement->fetchAllAssociative();

        return $offres;
    }

    public function selectMissionOfOffre($request){
        $sql = "SELECT nom FROM mission WHERE id_offre = ".$request->request->get('id');
        $connection = $this->entityManager->getConnection();
        $statement = $connection->executeQuery($sql);
        $missions = $statement->fetchAllAssociative();

        return $missions;
    }

    public function selectCompetenceOfOffre($request){
        $sql = "SELECT nom FROM competence WHERE id_offre = ".$request->request->get('id');
        $connection = $this->entityManager->getConnection();
        $statement = $connection->executeQuery($sql);
        $competences = $statement->fetchAllAssociative();

        return $competences;
    }

    

//    /**
//     * @return Offre[] Returns an array of Offre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offre
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
