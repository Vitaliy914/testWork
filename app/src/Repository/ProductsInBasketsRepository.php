<?php

namespace App\Repository;

use App\Entity\ProductsInBaskets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductsInBaskets>
 *
 * @method ProductsInBaskets|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductsInBaskets|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductsInBaskets[]    findAll()
 * @method ProductsInBaskets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsInBasketsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductsInBaskets::class);
    }

    public function save(ProductsInBaskets $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductsInBaskets $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ProductsInBaskets[] Returns an array of ProductsInBaskets objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductsInBaskets
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
