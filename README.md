# MediCab 🏥 - Plateforme de Gestion de Cabinet Médical

**MediCab** est une application web dynamique (architecture 3-Tiers) conçue pour digitaliser et optimiser la gestion quotidienne d'un cabinet médical (gestion des patients, des rendez-vous, des consultations et des ordonnances).

Projet tutoré de fin d'année réalisé par :
* **Chahine Adam**
* **Benthami Marouane**
* **Rabaibi Houssam**

**Encadré par :** Pr. Zouheir Banou (ISGA Casablanca)

---

## 🚀 Fonctionnalités Clés
* **Espace Patient :** Inscription par CIN unique, prise de rendez-vous en ligne, accès à l'historique médical et téléversement de documents (analyses, imagerie).
* **Espace Praticien (Dr. Benali) :** Tableau de bord dynamique avec filtrage des rendez-vous par statut (en attente, accepté, annulé), gestion des dossiers médicaux complets.
* **Module Consultation :** Saisie des constantes biométriques (tension, poids, température), diagnostics et génération automatique d'ordonnances électroniques.

## 🛠️ Technologies Utilisées
* **Front-End :** HTML5, CSS3 (Design System Responsive), JavaScript.
* **Back-End :** PHP (Gestion des sessions et architecture modulaire).
* **Base de données :** MariaDB / MySQL via phpMyAdmin (Moteur InnoDB avec contraintes d'intégrité et suppressions en cascade).

---

## ⚙️ Installation et Déploiement Local
1. Télécharger ou cloner ce dépôt dans votre répertoire `xampp/htdocs/`.
2. Ouvrir **phpMyAdmin** et créer une base de données nommée `medicab`.
3. Importer le fichier `medicab.sql` fourni à la racine du projet pour générer automatiquement les 8 tables.
4. Lancer votre serveur Apache/MySQL via XAMPP et accéder à l'application via `localhost/MediCabFinal`.
