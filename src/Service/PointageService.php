<?php

namespace App\Service;

use App\Entity\LogPortique;

class PointageService
{
    public function processLogs(array $logs): array
    {
        $collaborateurs = [];

        foreach ($logs as $log) {
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

        return $pauses;
    }
}