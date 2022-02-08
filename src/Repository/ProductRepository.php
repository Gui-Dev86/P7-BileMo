<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;


/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Returns all Products with brand
     * @return void 
     */
    public function getAllProducts(){
        $query = $this->createQueryBuilder('a')
        ->select('a.id',
            'a.name',
            'a.price',
            'a.description',
            'b.name')
        ->innerJoin('App\Entity\Brand', 'b', Join::WITH, 'a.brand = b.id')
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * Returns one Product with brand
     * @return void 
     */
    public function getOneProduct($id){
        $query = $this->createQueryBuilder('a')
        ->select('a.id',
            'a.name',
            'a.price',
            'a.description',
            'b.name')
        ->innerJoin('App\Entity\Brand', 'b', Join::WITH, 'a.brand = b.id')
        ->where('a.id = :id')
        ->setParameter(':id', $id)
        ;
        return $query->getQuery()->getResult();
    }
}
