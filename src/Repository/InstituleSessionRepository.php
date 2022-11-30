<?php

namespace App\Repository;

use App\Entity\InstituleSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstituleSession>
 *
 * @method InstituleSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstituleSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstituleSession[]    findAll()
 * @method InstituleSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstituleSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstituleSession::class);
    }

    public function save(InstituleSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InstituleSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return InstituleSession[] Returns an array of InstituleSession objects
     *  this returns the courses that are in process 
     */
    public function sessionEnCour(): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('i')
            ->andWhere('i.datecommerce < :now')
            ->andWhere('i.datefin > :now')
            ->setParameter('now', $now)
            ->orderBy('i.datecommerce', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return InstituleSession[] Returns an array of InstituleSession objects
     *  this returns the courses that are in are still to come 
     */
    public function sessionToCome(): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('i')
            ->andWhere('i.datecommerce > :now')
            ->setParameter('now', $now)
            ->orderBy('i.datecommerce', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return InstituleSession[] Returns an array of InstituleSession objects
     *  this returns the courses that have passed 
     */
    public function sessionPassed(): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('i')
            ->andWhere('i.datefin < :now')
            ->setParameter('now', $now)
            ->orderBy('i.datecommerce', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return InstituleSession[] Returns an array of InstituleSession objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InstituleSession
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
