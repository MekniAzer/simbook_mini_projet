<?php

namespace App\Repository;

use App\Entity\Livres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Livres>
 */
class LivresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livres::class);
    }

    // Get books by status (e.g., 'recommended', 'recently_read', etc.)
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.status = :status')
            ->setParameter('status', $status)
            ->orderBy('l.dateEdition', 'DESC') // Order by date of edition, for example
            ->getQuery()
            ->getResult();
    }


    public function findBySearchQuery(string $searchQuery, string $status, string $category = ''): array
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.titre LIKE :search OR l.resume LIKE :search')
            ->andWhere('l.status = :status')
            ->setParameter('search', '%'.$searchQuery.'%')
            ->setParameter('status', $status);

        // Join the Categories table to filter by the category name (libelle)
        $qb->leftJoin('l.cat', 'c')
            ->addSelect('c') // Add the category alias to the select clause
            ->andWhere('c.libelle LIKE :category')
            ->setParameter('category', '%'.$category.'%');

        return $qb->getQuery()->getResult();
    }

}
