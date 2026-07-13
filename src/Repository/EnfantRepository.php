<?php

namespace App\Repository;

use App\Entity\CentreEtatCivil;
use App\Entity\Enfant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enfant>
 */
class EnfantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enfant::class);
    }

    public function findByCecOrderByMatricule(CentreEtatCivil $cec): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.agent', 'a')
            ->where('e.centreEtatCivil = :cec')
            ->andWhere('e.enfant_reconnu_y_n = true')
            ->setParameter('cec', $cec)
            ->orderBy('a.matricule', 'ASC')
            ->addOrderBy('e.date_naissance', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Annuler la saisie d'un enfant
     * @param string $matricule
     * @param string $nomEnfant
     * @return int
     */
    public function reinitialiserSaisie(string $matricule, string $nomEnfant, \DateTimeInterface $dateNaissance): int
    {
        return $this->getEntityManager()
            ->createQuery(
                "UPDATE App\Entity\Enfant e
            SET
                e.agent_saisie = NULL,
                e.numero_acte = NULL,
                e.date_acte_naissance = NULL,
                e.nom_conjoint = NULL,
                e.enfant_reconnu_y_n = false,
                e.createdAt = NULL,
                e.handicapeYN = false,
                e.centreEtatCivil = NULL,
                e.date_acte_after_3m_yn = NULL
            WHERE e.enfant_reconnu_y_n = true
            AND e.matricule = :matricule
            AND e.nom_enfant = :nomEnfant
            AND e.date_naissance = :dateNaissance"
            )
            ->setParameter('matricule', $matricule)
            ->setParameter('nomEnfant', $nomEnfant)
            ->setParameter('dateNaissance', $dateNaissance)
            ->execute();
    }

    //    /**
    //     * @return Enfant[] Returns an array of Enfant objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Enfant
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
