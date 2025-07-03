<?php

namespace App\Service;

use App\Entity\LogPortiques;

class PointageService
{
    public function processLogs(array $logs): array
    {
        $collaborateurs = [];

        foreach ($logs as $log) {
            if (!$log instanceof LogPortiques) continue;

            $pin = $log->getPin();

            if (!isset($collaborateurs[$pin])) {
                $collaborateurs[$pin] = [
                    'name' => $log->getName(),
                    'pin' => $pin,
                    'cards' => [],
                    'entries' => [],
                    'exits' => []
                ];
            }

            $collaborateurs[$pin]['cards'][$log->getCardNo()] = true;

            if ($log->getEventPointName() === 'entrée') {
                $collaborateurs[$pin]['entries'][] = $log->getTime();
            } elseif ($log->getEventPointName() === 'sortie') {
                $collaborateurs[$pin]['exits'][] = $log->getTime();
            }
        }

        $result = [];
        foreach ($collaborateurs as $pin => $data) {
            $entries = $data['entries'];
            $exits = $data['exits'];

            sort($entries);
            sort($exits);

            $firstEntry = $entries ? min($entries) : null;
            $lastExit = $exits ? max($exits) : null;

            $pauses = $this->calculatePauses($entries, $exits);

            $result[] = [
                'name' => $data['name'],
                'pin' => $pin,
                'cards' => implode(',', array_keys($data['cards'])),
                'firstEntry' => $firstEntry,
                'lastExit' => $lastExit,
                'pauseCount' => $pauses['count'],
                'pauseDuration' => $pauses['duration']
            ];
        }

        return $result;
    }

    public function processDailyLogs(array $logs, \DateTimeInterface $date): array
    {
        $collaborateurs = [];
        $nextDay = (clone $date)->modify('+1 day');
        $previousDay = (clone $date)->modify('-1 day');

        foreach ($logs as $log) {
            $pin = $log->getPin();
            $logTime = $log->getTime();

            if (!isset($collaborateurs[$pin])) {
                $collaborateurs[$pin] = [
                    'name' => $log->getName(),
                    'pin' => $pin,
                    'cards' => [],
                    'entries' => [],
                    'exits' => [],
                    'overnight' => false
                ];
            }

            $collaborateurs[$pin]['cards'][$log->getCardNo()] = true;

            // Gestion des entrées/sorties sur plusieurs jours
            if ($log->getEventPointName() === 'entrée') {
                // Si l'entrée est avant minuit mais la sortie serait après
                if ($logTime < $date && !$collaborateurs[$pin]['entries']) {
                    $collaborateurs[$pin]['overnight'] = true;
                }
                $collaborateurs[$pin]['entries'][] = $logTime;
            } elseif ($log->getEventPointName() === 'sortie') {
                $collaborateurs[$pin]['exits'][] = $logTime;
            }
        }

        $result = [];
        foreach ($collaborateurs as $pin => $data) {
            $entries = $data['entries'];
            $exits = $data['exits'];

            // Trier par ordre chronologique
            usort($entries, function($a, $b) { return $a <=> $b; });
            usort($exits, function($a, $b) { return $a <=> $b; });

            // Première entrée
            $firstEntry = $entries ? min($entries) : null;

            // Dernière sortie
            $lastExit = $exits ? max($exits) : null;

            // Calcul des pauses
            $pauses = $this->calculatePauses($entries, $exits);

            // Formatage de la durée des pauses
            $pauseDuration = $this->formatDuration($pauses['duration']);

            $result[] = [
                'name' => $data['name'],
                'pin' => $pin,
                'cards' => implode(',', array_keys($data['cards'])),
                'firstEntry' => $firstEntry,
                'lastExit' => $lastExit,
                'pauseCount' => $pauses['count'],
                'pauseDuration' => $pauseDuration,
                'overnight' => $data['overnight']
            ];
        }

        return $result;
    }

    private function calculatePauses(array $entries, array $exits): array
    {
        $pauses = ['count' => 0, 'duration' => 0];
        $exitIndex = 0;

        for ($i = 1; $i < count($entries); $i++) {
            while ($exitIndex < count($exits) && $exits[$exitIndex] < $entries[$i]) {
                $exitTime = $exits[$exitIndex];
                $entryTime = $entries[$i];

                if ($exitTime < $entryTime) {
                    $pauses['count']++;
                    $pauses['duration'] += ($entryTime->getTimestamp() - $exitTime->getTimestamp());
                    break;
                }
                $exitIndex++;
            }
        }

        // Convertir la durée en secondes
        $pauses['duration'] = $pauses['duration'] ?: 0;

        return $pauses;
    }
}
