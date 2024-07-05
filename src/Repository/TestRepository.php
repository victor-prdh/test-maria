<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Test>
 */
class TestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Test::class);
    }

    public function extractName(string $code): string 
    {
        $query = $this->createQueryBuilder('t')
        ->select("JSON_EXTRACT(t.data, '$.name') as name")
        ->getQuery();
        
        return $query->getSingleScalarResult();
    }
}
