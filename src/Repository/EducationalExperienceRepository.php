<?php

namespace App\Repository;

use App\Entity\EducationalExperience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EducationalExperience>
 *
 * @method EducationalExperience|null find($id, $lockMode = null, $lockVersion = null)
 * @method EducationalExperience|null findOneBy(array $criteria, array $orderBy = null)
 * @method EducationalExperience[]    findAll()
 * @method EducationalExperience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EducationalExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EducationalExperience::class);
    }

    public function save(EducationalExperience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EducationalExperience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EducationalExperience[] Returns an array of EducationalExperience objects
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

//    public function findOneBySomeField($value): ?EducationalExperience
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
