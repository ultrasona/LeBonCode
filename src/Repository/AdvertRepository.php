<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager,ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    public function findByPriceMin(int $price_min) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Advert a
            WHERE a.price > :price
            ORDER BY a.price ASC'
        )->setParameter('price', $price_min);

        return $query->getResult();
    }

    public function findByPriceMax(int $price_max) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Advert a
            WHERE a.price < :price
            ORDER BY a.price ASC'
        )->setParameter('price', $price_max);

        return $query->getResult();
    }
  
}
