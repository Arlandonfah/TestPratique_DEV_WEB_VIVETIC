<?php

namespace App\Controller;

use App\Repository\LogPortiquesRepository;
use App\Service\PointageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PointageController extends AbstractController
{
    #[Route('/', name: 'app_collaborateurs')]
    public function collaborateurs(LogPortiquesRepository $repository): Response
    {
        $collaborateurs = $repository->getCollaborateursWithCards();

        return $this->render('pointage/index.html.twig', [
            'collaborateurs' => $collaborateurs
        ]);
    }

    #[Route('/logs', name: 'app_logs')]
public function logs(Request $request, LogPortiquesRepository $repository, PointageService $service): Response
{
    $date = $request->query->get('date', date('Y-m-d'));
    $selectedDate = new \DateTime($date);

    // Récupérer les logs du jour sélectionné
    $logs = $repository->getLogsByDate($selectedDate);

    // Traiter les logs pour la journée
    $dailyLogs = $service->processDailyLogs($logs, $selectedDate);

    return $this->render('pointage/logs.html.twig', [
        'logs' => $dailyLogs,
        'selectedDate' => $selectedDate->format('Y-m-d')
    ]);
}
}
