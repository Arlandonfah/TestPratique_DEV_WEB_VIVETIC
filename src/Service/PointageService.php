<?php

namespace App\Service;

use App\Entity\LogPortiques;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class PointageService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function processDailyLogs(array $logs, DateTimeInterface $selectedDate): array
    {
        $collaborateurs = [];
        $selectedDateStr = $selectedDate->format('Y-m-d');

        foreach ($logs as $log) {
            $pin = $log->getPin();
            $logDate = $log->getTime()->format('Y-m-d');

            if (!isset($collaborateurs[$pin])) {
                $collaborateurs[$pin] = [
                    'name' => $log->getName(),
                    'pin' => $pin,
                    'cards' => [],
                    'entries' => [],
                    'exits' => []
                ];
            }

            if ($logDate === $selectedDateStr) {
                $cardNo = $log->getCardNo();
                if (!in_array($cardNo, $collaborateurs[$pin]['cards'])) {
                    $collaborateurs[$pin]['cards'][] = $cardNo;
                }
            }

            if ($log->isEntry()) {
                $collaborateurs[$pin]['entries'][] = $log->getTime();
            } elseif ($log->isExit()) {
                $collaborateurs[$pin]['exits'][] = $log->getTime();
            }
        }

        $result = [];
        foreach ($collaborateurs as $pin => $data) {
            usort($data['entries'], fn($a, $b) => $a <=> $b);
            usort($data['exits'], fn($a, $b) => $a <=> $b);

            $firstEntry = $this->findFirstEntry($data['entries']);
            $lastExit = $this->findLastExit($data['exits']);
            $pauses = $this->calculatePauses($data['entries'], $data['exits']);

            $result[] = [
                'name' => $data['name'],
                'pin' => $pin,
                'cards' => $data['cards'],
                'firstEntry' => $firstEntry,
                'lastExit' => $lastExit,
                'pauseCount' => $pauses['count'],
                'pauseDuration' => $this->formatDuration($pauses['duration'])
            ];
        }

        return $result;
    }


    public function processDailySummary(array $dailySummary, \DateTimeInterface $selectedDate): array
    {
        $result = [];

        foreach ($dailySummary as $row) {
            $breakCount = min($row['entry_count'], $row['exit_count']) - 1;
            $breakCount = max(0, $breakCount);

            $firstEntry = $row['first_in'] ? \DateTime::createFromFormat('Y-m-d H:i:s', $row['first_in']) : null;
            $lastExit = $row['last_out'] ? \DateTime::createFromFormat('Y-m-d H:i:s', $row['last_out']) : null;

            $firstEntryContext = $this->getTimeContext($firstEntry, $selectedDate);
            $lastExitContext = $this->getTimeContext($lastExit, $selectedDate);

            $result[] = [
                'name' => $row['name'],
                'pin' => $row['pin'],
                'cards' => $row['cards'],
                'firstEntry' => $firstEntry,
                'firstEntryContext' => $firstEntryContext,
                'lastExit' => $lastExit,
                'lastExitContext' => $lastExitContext,
                'pauseCount' => $breakCount,
                'pauseDuration' => $this->calculateBreakDuration($row)
            ];
        }

        return $result;
    }

    private function getTimeContext(?\DateTimeInterface $time, \DateTimeInterface $selectedDate): string
    {
        if (!$time) return 'current';

        $timeDate = $time->format('Y-m-d');
        $selected = $selectedDate->format('Y-m-d');

        if ($timeDate < $selected) return 'previous';
        if ($timeDate > $selected) return 'next';
        return 'current';
    }

    private function findFirstEntry(array $entries): ?\DateTimeInterface
    {
        return $entries ? min($entries) : null;
    }

    private function findLastExit(array $exits): ?\DateTimeInterface
    {
        return $exits ? max($exits) : null;
    }

    private function calculateBreakDuration(array $row): string
{
    $pin = $row['pin'];
    $start = $row['first_in'] ?: null;
    $end = $row['last_out'] ?: null;

    if (!$start || !$end) {
        return '00:00';
    }


    $logs = $this->entityManager->getRepository(LogPortiques::class)
        ->createQueryBuilder('l')
        ->where('l.pin = :pin')
        ->andWhere('l.time BETWEEN :start AND :end')
        ->setParameter('pin', $pin)
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->orderBy('l.time', 'ASC')
        ->getQuery()
        ->getResult();

    $totalSeconds = 0;
    $lastExit = null;

    foreach ($logs as $log) {
        if ($log->isExit()) {
            $lastExit = $log->getTime();
        } elseif ($log->isEntry() && $lastExit) {
            $totalSeconds += $log->getTime()->getTimestamp() - $lastExit->getTimestamp();
            $lastExit = null;
        }
    }

    return $this->formatDuration($totalSeconds);
}

    private function formatDuration(int $seconds): string
    {
        if ($seconds <= 0) return '00:00';

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        return sprintf('%02d:%02d', $hours, $minutes);
    }


    private function calculatePauses(array $entries, array $exits): array
    {
        $pauses = ['count' => 0, 'duration' => 0];
        $exitIndex = 0;
        $entryCount = count($entries);

        for ($i = 1; $i < $entryCount; $i++) {
            while ($exitIndex < count($exits) && $exits[$exitIndex] < $entries[$i - 1]) {
                $exitIndex++;
            }

            if ($exitIndex < count($exits) &&
                $exits[$exitIndex] > $entries[$i - 1] &&
                $exits[$exitIndex] < $entries[$i]) {

                $pauses['count']++;
                $pauses['duration'] += $entries[$i]->getTimestamp() - $exits[$exitIndex]->getTimestamp();
                $exitIndex++;
            }
        }

        return $pauses;
    }

    public function processCollaborateurDetails(array $logs, \DateTimeInterface $selectedDate): array
{
    $entries = [];
    $exits = [];
    $events = [];
    $cards = [];

    foreach ($logs as $log) {
        $cards[$log->getCardNo()] = true;

        $events[] = [
            'time' => $log->getTime(),
            'type' => $log->getEventPointName(),
            'device' => $log->getDeviceName()
        ];

        if ($log->isEntry()) {
            $entries[] = $log->getTime();
        } elseif ($log->isExit()) {
            $exits[] = $log->getTime();
        }
    }


    usort($entries, fn($a, $b) => $a <=> $b);
    usort($exits, fn($a, $b) => $a <=> $b);


    $pauses = $this->calculatePauses($entries, $exits);

    return [
        'firstEntry' => $entries ? min($entries) : null,
        'lastExit' => $exits ? max($exits) : null,
        'pauseCount' => $pauses['count'],
        'pauseDuration' => $this->formatDuration($pauses['duration']),
        'cards' => array_keys($cards),
        'events' => $events
    ];
}
}
