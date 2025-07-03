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

# Environnement technique

# Versions logicielles

Composant Version Notes

Symfony 7.0 Framework PHP

PHP 8.2+ Version minimale requise

MySQL 8.0+ Base de données

Node.js 18.x+ Pour les dépendances frontend

Composer 2.5+ Gestion des dépendances PHP

IDE recommandé

    Visual Studio Code (version 1.85+)

        Extensions recommandées :

            Symfony Snippets

            PHP Intelephense

            Twig Language 2

            Prettier - Code formatter

            GitLens

            Docker

# Bibliothèques principales

Type Bibliothèque Version Description

Backend Symfony Framework 7.0 Core du système

Doctrine ORM 3.0 Gestion de la base de données

Frontend Bootstrap 5.3.0 Framework CSS

Font Awesome 6.4.0 Icônes

Flatpickr 4.6.13 Sélecteur de dates

Utilitaires KnpPaginatorBundle 5.9 Pagination

DoctrineExtensions 1.5 Fonctions MySQL avancées

# Structure du projet

TESTPRATIQUE_DEV_WEB_VIVETIC/
├── bin/ # Exécutables Symfony
├── config/ # Configuration de l'application
├── migrations/ # Migrations de base de données
|\_\_ node_modules/ # Dépendances Node.js
├── public/ # Racine web
│ ├── assets/ # Assets statiques
│ │ ├── css/ # Feuilles de style
│ │ │ └── styles.css # Style principal
logs.css # Styles de logs
│ │ └── js/ # JavaScript
│ │ └── collaborateurs.js # Scripts interactifs
app.js
logs.js
│ └── index.php # Point d'entrée de l'application
├── src/ # Code source PHP
│ ├── Controller/ # Contrôleurs
│ │ └── PointageController.php # Contrôleur principal
│ ├── Entity/ # Entités Doctrine
│ │ └── LogPortiques.php # Entité des logs de pointage
│ ├── Repository/ # Repository Doctrine
│ │ └── LogPortiquesRepository.php # Requêtes personnalisées
│ ├── Service/ # Services métier
│ │ └── PointageService.php # Traitement des pointages
│ └── Kernel.php # Noyau de l'application
├── templates/ # Templates Twig
│ ├── base.html.twig # Template de base
│ └── pointage/ # Templates spécifiques
│ ├── index.html.twig # Liste des collaborateurs
│ └── logs_jour.html.twig # Pointage journalier
├── var/ # Fichiers variables (cache, logs)
├── vendor/ # Dépendances Composer
├── .env # Variables d'environnement
├── .env.local # Surcharge locale (ignoré par Git)
├── .gitignore # Fichiers ignorés par Git
├── composer.json # Dépendances PHP
├── composer.lock # Versions exactes des dépendances
└── README.md # Ce fichier

# Installation

# Prérequis

    PHP 8.2+

    MySQL 8.0+

    Composer 2.5+

    Node.js 18.x+ (optionnel)

# Étapes d'installation

    1- Cloner le dépôt

git clone https://(https://github.com/Arlandonfah/TestPratique_DEV_WEB_VIVETIC)

cd TestPratique_DEV_WEB_VIVETIC

2- Installer les dépendances PHP

composer install

3- Configurer l'environnement

dans le fichier .env

4 - Modifier les variables :

.env

DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/vivetic?serverVersion=8.0&charset=utf8mb4"

5 - Créer la base de données

php bin/console doctrine:database:create

6- Importer les données initiales

mysql -u db_user -p db_name < chemin/vers/log_portiques.sql

7- Installer les dépendances frontend (optionnel)

npm install flatpickr

8- Lancer le serveur de développement

symfony server:start

# Utilisation

# Accès à l'application

    URL locale : http://localhost:8000 ou http://127.0.0.1:8000

    Identifiants par défaut : Aucune authentification nécessaire (peut être ajoutée)

# Page des collaborateurs

    URL : http://localhost:8000/ ou http://127.0.0.1:8000/

    1- Fonctionnalités :

        Recherche instantanée par nom, matricule ou carte

        Tri des colonnes par ordre croissant/décroissant

        Affichage des cartes associées à chaque collaborateur

        Statistiques globales


    2- Pointage journalier

    URL : http://localhost:8000/logs-jour ou http://127.0.0.1:8000/logs-jour

    Fonctionnalités :

        Sélection de date avec calendrier interactif

        Affichage des pointages par collaborateur

        Visualisation des plages horaires via timeline

        Calcul automatique des pauses

        Gestion des entrées/sorties nocturnes

        Statistiques journalières consolidées
