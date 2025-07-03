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

   #[Route('/logs-jour', name: 'app_logs_jour')]
public function logsJour(Request $request, LogPortiquesRepository $repository, PointageService $service): Response
{
    // Date par défaut = aujourd'hui
    $dateString = $request->query->get('date', date('Y-m-d'));
    $selectedDate = new \DateTime($dateString);

    // Récupérer les logs pour la période (jour courant + nuit suivante)
    $logs = $repository->findLogsByDate($selectedDate);
    
    // Traiter les logs
    $dailyLogs = $service->processDailyLogs($logs, $selectedDate);
    
    return $this->render('pointage/logs_jour.html.twig', [
        'dailyLogs' => $dailyLogs,
        'selectedDate' => $selectedDate->format('Y-m-d')
    ]);
}
}
