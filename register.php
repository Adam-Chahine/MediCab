<?php
session_start();
require_once "db.php";

$step = $_GET['step'] ?? 1;
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($step == 1) {
        // Étape 1: infos perso
        $_SESSION['reg_nom'] = $_POST['nom'];
        $_SESSION['reg_prenom'] = $_POST['prenom'];
        $_SESSION['reg_cin'] = $_POST['cin'];
        $_SESSION['reg_adresse'] = $_POST['adresse'];
        $_SESSION['reg_dob'] = $_POST['dob'];
        $_SESSION['reg_tel'] = $_POST['telephone'];
        header("Location: register.php?step=2");
        exit();
    } elseif ($step == 2) {
        // Étape 2: email + password
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if ($password !== $confirm) {
            $error = "Les mots de passe ne correspondent pas !";
        } else {
            // Vérifier email existant
            $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $error = "Email déjà utilisé !";
            } else {
                try {
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("INSERT INTO users (nom, prenom, cin, adresse, dob, telephone, email, password)
                            VALUES (:nom,:prenom,:cin,:adresse,:dob,:telephone,:email,:password)");

                    $stmt->execute([
                        ":nom" => $_SESSION['reg_nom'],
                        ":prenom" => $_SESSION['reg_prenom'],
                        ":cin" => $_SESSION['reg_cin'],
                        ":adresse" => $_SESSION['reg_adresse'],
                        ":dob" => $_SESSION['reg_dob'],
                        ":telephone" => $_SESSION['reg_tel'],
                        ":email" => $email,
                        ":password" => $hash
                    ]);

                    // Tout va bien -> connecter automatiquement et rediriger
                    $_SESSION['user_id'] = $conn->lastInsertId();
                    $_SESSION['user_name'] = $_SESSION['reg_nom']; // ajoute cette ligne
                    header("Location: dashboard.php");
                    exit;
                } catch (PDOException $e) {

                    // Vérifier si c’est un duplicate entry (CIN ou email)
                    if ($e->errorInfo[1] == 1062) {
                        $error_message = "Ce CIN ou cet email existe déjà !";
                    } else {
                        $error_message = "Une erreur est survenue. Veuillez réessayer.";
                    }

                    // Rediriger vers une page custom ou afficher sur la page
                    header("Location: erreur.php?message=" . urlencode($error_message));
                    exit;
                }

                // Connexion auto
                $_SESSION['user_id'] = $conn->lastInsertId();
                $_SESSION['user_name'] = $_SESSION['reg_nom'];

                // Supprimer données temporaires
                unset($_SESSION['reg_nom'], $_SESSION['reg_prenom'], $_SESSION['reg_cin'], $_SESSION['reg_adresse'], $_SESSION['reg_dob'], $_SESSION['reg_tel']);

                header("Location: dashboard.php");
                exit();
            }
        }
    }
}
include "views/register.view.php";
?>
