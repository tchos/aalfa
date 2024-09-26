<?php

namespace App\Repository;

use App\Entity\Agent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Agent>
 */
class AgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agent::class);
    }

    public function findWithEnfants($mat)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.enfants', 'e')
            ->addSelect('e')
            ->where('a.matricule = :mat')
            ->orderBy('e.date_naissance','ASC')
            ->setParameter('mat', $mat)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findWithEnfantsId($id)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.enfants', 'e')
            ->addSelect('e')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByMatricule($mat): ?Agent
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.matricule = :val')
            ->setParameter('val', $mat)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    //    /**
    //     * @return Agent[] Returns an array of Agent objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Agent
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
