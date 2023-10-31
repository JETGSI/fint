<?php

namespace App\Repository;

use App\Entity\AssociativeExperience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssociativeExperience>
 *
 * @method AssociativeExperience|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssociativeExperience|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssociativeExperience[]    findAll()
 * @method AssociativeExperience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociativeExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssociativeExperience::class);
    }

    public function save(AssociativeExperience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AssociativeExperience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AssociativeExperience[] Returns an array of AssociativeExperience objects
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

//    public function findOneBySomeField($value): ?AssociativeExperience
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
