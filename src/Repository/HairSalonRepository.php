<?php

namespace App\Repository;

use App\Entity\HairSalon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HairSalon>
 *
 * @method HairSalon|null find($id, $lockMode = null, $lockVersion = null)
 * @method HairSalon|null findOneBy(array $criteria, array $orderBy = null)
 * @method HairSalon[]    findAll()
 * @method HairSalon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HairSalonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HairSalon::class);
    }

    public function save(HairSalon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HairSalon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    
    /**
     * @return HairSalon[] Returns an array of HairSalon objects
     */
    public function findByCodePostal($postalAdress): array
{
    return $this->createQueryBuilder('s')
        ->andWhere('s.postalAdress LIKE :postalAdress')
        ->setParameter('postalAdress', '%' . $postalAdress . '%')
        ->orderBy('s.id', 'ASC')
        ->getQuery()
        ->getResult();
}


//    public function findOneBySomeField($value): ?HairSalon
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
