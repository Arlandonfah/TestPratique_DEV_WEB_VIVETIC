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


    public function getDailySummary(DateTimeInterface $date): array
    {
        $start = (clone $date)->modify('-1 day')->format('Y-m-d 00:00:00');
        $end = (clone $date)->modify('+1 day')->format('Y-m-d 23:59:59');

        $sql = "
            SELECT
                l.pin,
                l.Name AS name,
                GROUP_CONCAT(DISTINCT l.card_no) AS cards,
                MIN(CASE WHEN l.event_point_name = 'entrée' THEN l.time END) AS first_in,
                MAX(CASE WHEN l.event_point_name = 'sortie' THEN l.time END) AS last_out,
                COUNT(DISTINCT CASE WHEN l.event_point_name = 'sortie' THEN l.id END) AS exit_count,
                COUNT(DISTINCT CASE WHEN l.event_point_name = 'entrée' THEN l.id END) AS entry_count
            FROM log_portiques l
            WHERE l.time BETWEEN :start AND :end
            GROUP BY l.pin, l.Name
            ORDER BY l.Name ASC
        ";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('start', $start);
        $stmt->bindValue('end', $end);

        return $stmt->executeQuery()->fetchAllAssociative();
    }


    public function findLogsByDateRange(DateTimeInterface $start, DateTimeInterface $end): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.time BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('l.time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLogsByPinAndDate(int $pin, \DateTimeInterface $date): array
{
    $start = (clone $date)->modify('-1 day')->format('Y-m-d 00:00:00');
    $end = (clone $date)->modify('+1 day')->format('Y-m-d 23:59:59');

    return $this->createQueryBuilder('l')
        ->where('l.pin = :pin')
        ->andWhere('l.time BETWEEN :start AND :end')
        ->setParameter('pin', $pin)
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->orderBy('l.time', 'ASC')
        ->getQuery()
        ->getResult();
}
}
