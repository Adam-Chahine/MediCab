# MediCab 🏥 — Plateforme de Gestion de Cabinet Médical

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" alt="HTML5">
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
</p>

---

### 📝 Présentation du Projet
**MediCab** est une application web dynamique basée sur une **architecture 3-Tiers**. Elle a été conçue pour digitaliser et optimiser l'ensemble des processus administratifs et médicaux d'un cabinet (suivi patient, consultations complexes, historique et ordonnances).

* 👨‍💻 **Développé par :** [Ton Nom], Benthami Marouane & Rabaibi Houssam
* 🎓 **Encadrant :** Pr. Zouheir Banou (ISGA Casablanca)
* 💼 **Cadre :** Projet de Fin d'Année (PFA) — Cursus Cycle Ingénieur

---

## 🛠️ Fonctionnalités Majeures

* 🔐 **Double Authentification Métier :** Espaces cloisonnés et sécurisés pour les Patients et le Praticien (Dr. Benali).
* 📅 **Portail Patient Intuitif :** Inscription par CIN unique, prise et suivi des rendez-vous en temps réel, téléversement de documents médicaux (Analyses, Imagerie au format PDF/Image).
* 🗂️ **Gestion Clinique (Espace Médecin) :** Tableau de bord dynamique, gestion complète des dossiers médicaux, module de consultation médicale avec prise de constantes (Tension, Pouls, Poids) et **génération automatique d'ordonnances**.

---

## ⚙️ Déploiement & Installation Locale

1. **Clonage :** Téléchargez ou clonez ce dépôt dans votre répertoire local `xampp/htdocs/`.
2. **Base de Données :** Créez une base de données nommée `medicab` sur **phpMyAdmin**.
3. **Importation :** Importez le script `medicab.sql` situé à la racine pour structurer automatiquement les 8 tables (Moteur InnoDB avec contraintes d'intégrité et suppressions en cascade).
4. **Lancement :** Activez Apache et MySQL sur votre panneau XAMPP, puis accédez à l'application via `localhost/MediCabFinal`.
