# TestPratique_DEV_WEB_VIVETIC

Une application d'affichage de liste de collaborateur

# VIVETIC - Application d'affichage de liste de collaborateur et Système de Gestion des Pointages

Solution complète de gestion des accès et pointages

# Table des matières

1- Description du projet

2- Fonctionnalités

3- Environnement technique

4- Structure du projet

5- Installation

6 - Utilisation

7 - Personnalisation

8 - Documentation technique

9 - Licence

10 - Support

# Description du projet

Ce projet est une application web développée avec Symfony 7 pour la gestion des pointages des collaborateurs via des portiques RFID. L'application permet de visualiser les collaborateurs avec leurs cartes associées et de consulter les pointages journaliers avec des statistiques détaillées, en tenant compte des cas particuliers comme les entrées/sorties nocturnes.
Fonctionnalités

1.  Gestion des collaborateurs et leurs cartes

    📋 Affichage de la liste des collaborateurs (nom et matricule) ainsi que la ou les cartes RFID qu'il ont associées

    💳 Visualisation des cartes RFID associées à chaque collaborateur

    🔍 Recherche et tri interactif

    📊 Statistiques globales (nombre de collaborateurs, cartes, multi-cartes, etc.)

2.  Pointage journalier

    📅 Filtrage des pointages par date

    👤 Affichage par collaborateur avec :

        💳 Cartes utilisées dans la journée

        ⏱ Heure de première entrée (avec gestion des entrées nocturnes)

        🚪 Heure de dernière sortie (avec gestion des sorties nocturnes)

        ☕ Nombre et durée des pauses

    📈 Visualisation graphique des plages horaires via une timeline

    📊 Statistiques journalières (nombre de collaborateurs, pauses, heures de pauses, sorties nocturnes)

    ⏱ Calcul automatique du temps travaillé
