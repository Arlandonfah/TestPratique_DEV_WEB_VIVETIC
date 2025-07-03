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

    public function processDailyLogs(array $logs, \DateTimeInterface $selectedDate): array
{
    $collaborateurs = [];

    foreach ($logs as $log) {
        $pin = $log->getPin();
        $time = $log->getTime();

        if (!isset($collaborateurs[$pin])) {
            $collaborateurs[$pin] = [
                'name' => $log->getName(),
                'pin' => $pin,
                'cards' => [],
                'entries' => [],
                'exits' => []
            ];
        }

        // Ajout de la carte si elle n'est pas déjà présente
        if (!in_array($log->getCardNo(), $collaborateurs[$pin]['cards'])) {
            $collaborateurs[$pin]['cards'][] = $log->getCardNo();
        }

        // Stockage des entrées et sorties
        if ($log->getEventPointName() === 'entrée') {
            $collaborateurs[$pin]['entries'][] = $time;
        } elseif ($log->getEventPointName() === 'sortie') {
            $collaborateurs[$pin]['exits'][] = $time;
        }
    }

    $result = [];
    foreach ($collaborateurs as $pin => $data) {
        // Triage des horaires
        usort($data['entries'], function($a, $b) {
            return $a <=> $b;
        });
        usort($data['exits'], function($a, $b) {
            return $a <=> $b;
        });

        // Première entrée
        $firstEntry = $data['entries'] ? min($data['entries']) : null;
        
        // Dernière sortie
        $lastExit = $data['exits'] ? max($data['exits']) : null;
        
        // Calcule des pauses
        $pauses = $this->calculatePauses($data['entries'], $data['exits']);

        $result[] = [
            'name' => $data['name'],
            'pin' => $pin,
            'cards' => $data['cards'],
            'firstEntry' => $firstEntry,
            'lastExit' => $lastExit,
            'pauseCount' => $pauses['count'],
            'pauseDuration' => $pauses['duration']
        ];
    }

    return $result;
}

private function calculatePauses(array $entries, array $exits): array
{
    $pauses = ['count' => 0, 'duration' => 0];
    $exitIndex = 0;

    // On suppose que les tableaux sont triés par ordre chronologique
    for ($i = 1; $i < count($entries); $i++) {
       
        while ($exitIndex < count($exits) && $exits[$exitIndex] < $entries[$i]) {
            // Vérification si cette sortie est après l'entrée précédente
            if ($exits[$exitIndex] > $entries[$i - 1]) {
                $pauses['count']++;
                $pauses['duration'] += ($entries[$i]->getTimestamp() - $exits[$exitIndex]->getTimestamp());
                break;
            }
            $exitIndex++;
        }
    }

    return $pauses;
}

  
}
