<?php

namespace App\Repository;

use App\Entity\Filiere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Filiere>
 */
class FiliereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Filiere::class);
    }

    public function save(Filiere $entity, bool $flush = false): void
    {
        $em = $this->getEntityManager();
        $em-> persist($entity);
        if($flush){
            $em->flush();
        }
    }

    public function  findOneByNom(string $nom): ?Filiere
    {
        $db = $this->createQueryBuilder('l');
        $db 
            -> where('l.nom = :nom')
            -> setParameter('nom', $nom)
            // -> andWhere('l.countryCode = :countryCode')
            // -> setParameter('countryCode', 'PL')
            ;
        $query = $db->getQuery();
        $entity = $query->getOneOrNullResult();

        return $entity;
    }

//    /**
//     * @return Filiere[] Returns an array of Filiere objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Filiere
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
