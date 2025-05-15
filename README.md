# Projet Réservation d’Événements - Architecture & Cluster MariaDB

## Contexte

Ce projet est une application de réservation de places pour des événements (concerts, conférences, expositions).  
Il intègre une contrainte métier forte : gestion en temps réel du taux de remplissage avec une base de données en cluster pour garantir la haute disponibilité.

---

## Objectifs

- Mettre en place une architecture modulaire respectant DDD, SOLID, KISS, et TDD.  
- Utiliser un cluster MariaDB Galera pour assurer la haute disponibilité.  
- Développer une application Symfony conteneurisée avec Docker.  
- Tester la résilience du cluster par des scénarios de failover.

---

## Architecture

- **Monolithe modulaire** en Symfony 6 (PHP 8.1+)  
- Couche Domaine, Application, Infrastructure et Présentation bien séparées  
- Domain Driven Design : bounded contexts `Event`, `Reservation`, `User`  
- Cluster MariaDB Galera à 3 nœuds en Docker Compose  
- Tests automatisés PHPUnit (unitaires et fonctionnels)

---

## Prérequis

- Docker & Docker Compose installés  
- PHP 8.1+, Composer (pour développement local)  
- MariaDB 10.9+ (géré via Docker)

---

## Installation et Lancement

1. **Cloner le dépôt**

   ```bash
   git clone <url-du-repo>
   cd reservation-system
Lancer les conteneurs Docker

bash
Copier
Modifier
docker-compose up -d --build
Initialiser la base de données

bash
Copier
Modifier
docker exec -it reservation-app php bin/console doctrine:migrations:migrate
Structure du projet

bash
Copier
Modifier
/src
  /Controller
  /Entity
  /Form
  /Repository
  /Service
/tests
  /Controller
  /Service
/docker
  docker-compose.yml
  Dockerfile
/migrations
Exécution des tests

bash
Copier
Modifier
docker exec -it reservation-app php bin/phpunit
Fonctionnalités principales
Gestion des événements (CRUD)

Réservation de places avec contrôle du taux de remplissage en temps réel

Utilisation d’un cluster MariaDB Galera pour assurer la haute disponibilité et la tolérance aux pannes

Architecture modulaire respectant DDD, avec séparation claire des responsabilités

Tests TDD pour garantir la robustesse du code

Docker & Cluster MariaDB Galera
Le cluster MariaDB est déployé avec Docker Compose, composé de 3 nœuds pour garantir :

Réplication synchrone des données

Haute disponibilité et tolérance aux pannes

Possibilité de failover automatique ou manuel

Le fichier docker-compose.yml définit :

3 services mariadb-node1, mariadb-node2, mariadb-node3 configurés pour Galera

Un service reservation-app pour l’application Symfony

Un réseau Docker dédié

Failover et haute disponibilité
Le cluster MariaDB Galera à 3 nœuds permet :

Réplication synchrone des données entre les nœuds

Tolérance aux pannes en cas de défaillance d’un nœud

Scénarios de bascule testés pour garantir la continuité de service

Tests réalisés :

Arrêt brutal d’un nœud MariaDB et vérification de la continuité d’accès

Coupure réseau simulée

Redémarrage automatique et resynchronisation

Décisions architecturales (ADR)
Monolithe modulaire : pour maîtriser la complexité métier avec Symfony

DDD : découpage en bounded contexts Event, Reservation, User

SOLID et KISS : code clair, maintenable, évolutif

TDD : tests unitaires et fonctionnels pour assurer la qualité

Cluster Galera : haute disponibilité et cohérence forte des données

Développement
Symfony 6 avec PHP 8.1+

Doctrine ORM pour la gestion des entités et migrations

Formulaires Symfony pour la gestion des entrées utilisateur

PHPUnit pour les tests

Utilisation de services injectés via Dependency Injection

Tests
Tests unitaires pour les services métier

Tests fonctionnels pour les contrôleurs (vérification des réponses HTTP et contenu HTML)

Tests d’intégration avec base de données (environnement Docker)

Validation du failover du cluster via tests de robustesse

