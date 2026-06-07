<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_patient'];
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $cin = trim($_POST['cin']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);

    try {
        // 1. Vérification de l'existence du CIN ou de l'Email (chez les AUTRES utilisateurs)
        $checkStmt = $conn->prepare("SELECT cin, email FROM users WHERE (cin = ? OR email = ?) AND id != ? LIMIT 1");
        $checkStmt->execute([$cin, $email, $id]);
        $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            if ($existingUser['cin'] === $cin) {
                $msg = "Ce CIN est déjà utilisé par un autre patient.";
            } else {
                $msg = "Cette adresse email est déjà utilisée par un autre patient.";
            }
            // Redirection vers erreur_update.php avec le message personnalisé
            header("Location: erreur_update.php?message=" . urlencode($msg));
            exit();
        }

        // 2. Si tout est bon, on procède à l'UPDATE
        $sql = "UPDATE users SET 
                nom = :nom, 
                prenom = :prenom, 
                cin = :cin, 
                email = :email, 
                adresse = :adresse 
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $result = $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':cin' => $cin,
            ':email' => $email,
            ':adresse' => $adresse,
            ':id' => $id
        ]);

        if ($result) {
            header("Location: dashboard_medecin.php?msg=update_success");
            exit();
        } else {
            header("Location: erreur_update.php?message=" . urlencode("Erreur lors de la mise à jour."));
            exit();
        }
    } catch (PDOException $e) {
        // En production, évite de die() avec l'erreur brute, redirige plutôt vers erreur_update.php
        header("Location: erreur_update.php?message=" . urlencode("Erreur base de données : " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: dashboard_medecin.php");
    exit();
}
