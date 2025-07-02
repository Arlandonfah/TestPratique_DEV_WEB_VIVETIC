<?php

namespace App\Repository;

use App\Entity\LogPortiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeInterface;

class LogPortiquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogPortiques::class);
    }

    public function getCollaborateursWithCards(): array
    {
        // Solution avec requête SQL native pour éviter les problèmes de DQL
        $sql = "
            SELECT 
                Name AS name,
                pin,
                GROUP_CONCAT(DISTINCT card_no ORDER BY card_no ASC) AS cards
            FROM log_portiques
            GROUP BY Name, pin
        ";

        $conn = $this->getEntityManager()->getConnection();
        return $conn->executeQuery($sql)->fetchAllAssociative();
    }

    public function getLogsByDate(DateTimeInterface $date): array
    {
        $start = $date->format('Y-m-d 00:00:00');
        
        // Crée une copie pour éviter de modifier la date originale
        $endDate = clone $date;
        $endDate->modify('+1 day');
        $end = $endDate->format('Y-m-d 00:00:00');

        // Solution avec requête SQL native
        $sql = "
            SELECT * 
            FROM log_portiques 
            WHERE time >= :start AND time < :end
            ORDER BY time ASC
        ";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('start', $start);
        $stmt->bindValue('end', $end);
        
        return $stmt->executeQuery()->fetchAllAssociative();
    }
}