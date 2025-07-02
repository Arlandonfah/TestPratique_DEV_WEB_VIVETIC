<?php

namespace App\Repository;

use App\Entity\LogPortique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LogPortiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogPortique::class);
    }

    public function getCollaborateursWithCards(): array
    {
        return $this->createQueryBuilder('l')
            ->select('l.name', 'l.pin', 'GROUP_CONCAT(DISTINCT l.cardNo ORDER BY l.cardNo ASC) as cards')
            ->groupBy('l.name, l.pin')
            ->getQuery()
            ->getResult();
    }

    public function getLogsByDate(\DateTimeInterface $date): array
    {
        $startDate = clone $date;
        $endDate = clone $date;
        $endDate->modify('+1 day');

        return $this->createQueryBuilder('l')
            ->where('l.time >= :start AND l.time < :end')
            ->setParameter('start', $startDate->format('Y-m-d 00:00:00'))
            ->setParameter('end', $endDate->format('Y-m-d 00:00:00'))
            ->orderBy('l.time', 'ASC')
            ->getQuery()
            ->getResult();
    }
}