# TestPratique_DEV_WEB_VIVETIC

Une application web d'affichage de liste de collaborateur, gestion des accès et pointages

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

Ce projet est une application web développée avec Symfony 7, php 8 et Mysql pour la gestion des pointages des collaborateurs via des portiques RFID. L'application permet de visualiser les collaborateurs avec leurs cartes associées et de consulter les pointages journaliers avec des statistiques détaillées, en tenant compte des cas particuliers comme les entrées/sorties nocturnes.

# Fonctionnalités

1.  Gestion des collaborateurs et leurs cartes

    📋 Affichage de la liste des collaborateurs (nom et matricule) ainsi que la ou les cartes RFID qu'il ont associées

    ![alt text](<1- Affichage de la liste des collaborateurs (nom et matricule) ainsi que la ou les cartes RFID qu'il ont associées.png>)

    💳 Visualisation des cartes RFID associées à chaque collaborateur

    🔍 Recherche et tri interactif

    ![alt text](<2- Recherche et tri interactif.png>)

    📊 Statistiques globales (nombre de collaborateurs, cartes, multi-cartes, etc.)

    ![alt text](<3 - Statistiques globales (nombre de collaborateurs, cartes, multi-cartes, etc.).png>)

2.  Affichage de la liste des logs, filtré par date et affichage des collaborateurs qui ont un ou des logs pour la date selectionnée

    📅 Filtrage des pointages par date indiqué dans la base de données (table log_portiques)

    ![alt text](<6 - filtrage des pointers par date.png>)

    👤 Affichage par collaborateur avec :

        💳 Cartes utilisées dans la journée

        ⏱ Heure de première entrée (avec gestion des entrées nocturnes)

        🚪 Heure de dernière sortie (avec gestion des sorties nocturnes)

        ☕ Nombre et durée des pauses

    📈 Visualisation graphique des plages horaires via une timeline

    ![alt text](<5 - Affichage par collaborateur avec  Visualisation graphique des plages horaires via une timeline.png>)

    📊 Statistiques journalières (nombre de collaborateurs, pauses, heures de pauses, sorties nocturnes)

    ⏱ Calcul automatique du temps travaillé

    ![alt text](<7 - affichage d'un details d'un collaborateur.png>)

# Environnement technique

# Versions logicielles

Composant Version Notes

Symfony 7.0 Framework PHP

PHP 8.2+ Version minimale requise

MySQL 8.0+ Base de données

Node.js 18.x+ Pour les dépendances frontend

Composer 2.5+ Gestion des dépendances PHP

javascript Langage de programmation

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

![alt text](<Capture d’écran 2025-07-03 194511.png>)

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

    . Veuillez activer votre server WAMP ou XAMMP (verifier que les services sont demarré).

    . Creer une base de données MySQL nommée vivetic et creer la tables log_portiques puis importer les proprietes de cette table.

    . Copiez le dossier source apres extraction dans le dossier C:\wamp64\www

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

    3- Exemple de Pointage journalier d'un collaborateurs specifique (date = 2021-03-01 indiqué dans la base de données uniquement)

    URL: http://127.0.0.1:8000/logs-jour?date=2021-03-01

# Documentation technique

# Licence

Ce projet est sous licence opensource.

# Support

Pour tout problème ou question, veuillez ouvrir une issue sur le dépôt GitHub.

TestPratique_DEV_WEB_VIVETIC © 2025 - Solution complète de gestion des accès et pointages
