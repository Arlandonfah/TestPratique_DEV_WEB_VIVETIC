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
    public function logsJour(
    Request $request,
    LogPortiquesRepository $repository,
    PointageService $service
   ): Response {
    $dateString = $request->query->get('date', date('Y-m-d'));
    $selectedDate = \DateTime::createFromFormat('Y-m-d', $dateString);

    // Récupération des données agrégées
    $dailySummary = $repository->getDailySummary($selectedDate);

    $processedLogs = $service->processDailySummary($dailySummary, $selectedDate);

    return $this->render('pointage/logs_jour.html.twig', [
        'logs' => $processedLogs,
        'selectedDate' => $selectedDate->format('Y-m-d')
    ]);
}

#[Route('/collaborateur/{pin}/{date}', name: 'app_collaborateur_details')]
public function collaborateurDetails(
    int $pin,
    string $date,
    LogPortiquesRepository $repository,
    PointageService $service
): Response {
    $selectedDate = \DateTime::createFromFormat('Y-m-d', $date);

    // Récupération des logs spécifiques au collaborateur
    $logs = $repository->findLogsByPinAndDate($pin, $selectedDate);

    // Traitement détaillé
    $details = $service->processCollaborateurDetails($logs, $selectedDate);

    return $this->render('pointage/collaborateur_details.html.twig', [
        'details' => $details,
        'collaborateur' => $logs[0]->getName() ?? 'Inconnu',
        'pin' => $pin,
        'date' => $date
    ]);
}
}
