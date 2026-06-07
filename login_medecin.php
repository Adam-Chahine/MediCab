<?php
session_start();
require 'db.php';

// Si déjà connecté → rediriger
if (isset($_SESSION['medecin_id'])) {
    header("Location: dashboard_medecin.php");
    exit();
}

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error_msg = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM medecins WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $medecin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($medecin && $password === $medecin['password']) {
            $_SESSION['medecin_id']  = $medecin['id'];
            $_SESSION['medecin_nom'] = $medecin['nom'];
            $_SESSION['medecin_prenom'] = $medecin['prenom'];
            header("Location: dashboard_medecin.php");
            exit();
        } else {
            $error_msg = "Email ou mot de passe incorrect.";
        }
    }
}
include "views/login_medecin.view.php"
?>
