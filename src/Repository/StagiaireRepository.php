<?php

namespace App\Repository;

use App\Entity\Stagiaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stagiaire>
 *
 * @method Stagiaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stagiaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stagiaire[]    findAll()
 * @method Stagiaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StagiaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stagiaire::class);
    }

    public function save(Stagiaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Stagiaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /** this is a subset form of query
     * A QueryBuilder provides an API that is designed for conditionally constructing 
     * a DQL (Doctrine Query Language) query in several steps
        * @return Stagiaire[] Returns an array of Stagiaire objects
        */
    public function findNonInscrit($institule_session_id)
    {
        $em = $this->getEntityManager();
        $cQB = $em->createQueryBuilder();

        $firstQuery = $cQB;

        //first query that merges both institulesession and stagiaire
        $firstQuery->select('s')
            ->from(Stagiaire::class, 's')
            ->innerJoin('s.institulesession', 'i')
            ->where('i.id = :id');

            //second query
        $cQB = $em->createQueryBuilder();

        $cQB->select('st')
            ->from(Stagiaire::class, 'st')
            ->where($cQB->expr()->notIn('st.id', $firstQuery->getDQL()))
            ->setParameter('id', $institule_session_id)
            ->orderBy('st.nom');

            $query = $cQB->getQuery();
            return $query->getResult();
    }


//    /**
//     * @return Stagiaire[] Returns an array of Stagiaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Stagiaire
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
