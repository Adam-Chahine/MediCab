<?php
session_start();
require 'db.php';

if (!isset($_SESSION['medecin_id'])) {
    header("Location: login_medecin.php");
    exit();
}

$user_id = intval($_GET['user_id'] ?? 0);

if (!$user_id) {
    header("Location: dashboard_medecin.php?section=patients");
    exit();
}

// ── Récupérer patient ──
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    header("Location: dashboard_medecin.php?section=patients");
    exit();
}

// ── Récupérer dossier existant ──
$stmt = $conn->prepare("SELECT * FROM dossier_medical WHERE user_id = ?");
$stmt->execute([$user_id]);
$dossier = $stmt->fetch(PDO::FETCH_ASSOC);

// ── Récupérer documents du dossier ──
$stmt = $conn->prepare("
    SELECT * FROM dossier_documents 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$user_id]);
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Récupérer consultations du patient ──
$stmt = $conn->prepare("
    SELECT id, date_consult, diagnostic
    FROM consultations
    WHERE user_id = ?
    ORDER BY date_consult DESC
    LIMIT 5
");
$stmt->execute([$user_id]);
$consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ══════════════════════════════════════
// TRAITEMENT POST
// ══════════════════════════════════════
$error_msg   = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ── Sauvegarder dossier ──
    if ($action === 'sauvegarder_dossier') {
        $groupe_sanguin         = trim($_POST['groupe_sanguin'] ?? '');
        $taille                 = floatval($_POST['taille'] ?? 0);
        $poids                  = floatval($_POST['poids'] ?? 0);
        $allergies              = trim($_POST['allergies'] ?? '');
        $maladies_chroniques    = trim($_POST['maladies_chroniques'] ?? '');
        $traitements_permanents = trim($_POST['traitements_permanents'] ?? '');
        $antecedents_familiaux  = trim($_POST['antecedents_familiaux'] ?? '');
        $tabac                  = $_POST['tabac'] ?? 'non';
        $alcool                 = $_POST['alcool'] ?? 'non';
        $handicap               = trim($_POST['handicap'] ?? '');

        if ($dossier) {
            $stmt = $conn->prepare("
                UPDATE dossier_medical SET
                    groupe_sanguin         = ?,
                    taille                 = ?,
                    poids                  = ?,
                    allergies              = ?,
                    maladies_chroniques    = ?,
                    traitements_permanents = ?,
                    antecedents_familiaux  = ?,
                    tabac                  = ?,
                    alcool                 = ?,
                    handicap               = ?,
                    updated_at             = NOW()
                WHERE user_id = ?
            ");
            $stmt->execute([
                $groupe_sanguin,
                $taille,
                $poids,
                $allergies,
                $maladies_chroniques,
                $traitements_permanents,
                $antecedents_familiaux,
                $tabac,
                $alcool,
                $handicap,
                $user_id
            ]);
        } else {
            $stmt = $conn->prepare("
                INSERT INTO dossier_medical 
                (user_id, groupe_sanguin, taille, poids, allergies,
                 maladies_chroniques, traitements_permanents,
                 antecedents_familiaux, tabac, alcool, handicap)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id,
                $groupe_sanguin,
                $taille,
                $poids,
                $allergies,
                $maladies_chroniques,
                $traitements_permanents,
                $antecedents_familiaux,
                $tabac,
                $alcool,
                $handicap
            ]);
        }

        $stmt = $conn->prepare("SELECT * FROM dossier_medical WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $dossier = $stmt->fetch(PDO::FETCH_ASSOC);
        $success_msg = "Dossier médical mis à jour avec succès !";
    }

    // ── Supprimer document ──
    if ($action === 'supprimer_document') {
        $doc_id = intval($_POST['doc_id']);
        $stmt = $conn->prepare("
            SELECT chemin FROM dossier_documents 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$doc_id, $user_id]);
        $doc = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doc) {
            if (file_exists($doc['chemin'])) unlink($doc['chemin']);
            $stmt = $conn->prepare("DELETE FROM dossier_documents WHERE id = ?");
            $stmt->execute([$doc_id]);
            $success_msg = "Document supprimé.";
        }

        $stmt = $conn->prepare("
            SELECT * FROM dossier_documents 
            WHERE user_id = ? ORDER BY created_at DESC
        ");
        $stmt->execute([$user_id]);
        $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Upload document ──
    if ($action === 'upload_document') {
        if (isset($_FILES['document']) && $_FILES['document']['error'] === 0) {
            $allowed     = ['pdf', 'jpg', 'jpeg', 'png'];
            $fileName    = $_FILES['document']['name'];
            $fileTmp     = $_FILES['document']['tmp_name'];
            $fileSize    = $_FILES['document']['size'];
            $description = trim($_POST['description'] ?? '');
            $ext         = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error_msg = "Format non autorisé. PDF, JPG, PNG uniquement.";
            } elseif ($fileSize > 5 * 1024 * 1024) {
                $error_msg = "Fichier trop volumineux (max 5MB).";
            } else {
                $uploadDir    = "uploads/dossiers/";
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $nom_clean    = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $patient['nom']));
                $prenom_clean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $patient['prenom']));
                $timestamp    = round(microtime(true) * 1000);
                $newName      = $nom_clean . "_" . $prenom_clean . "_" . $timestamp . "." . $ext;
                $uploadPath   = $uploadDir . $newName;

                move_uploaded_file($fileTmp, $uploadPath);

                $stmt = $conn->prepare("
                    INSERT INTO dossier_documents 
                    (user_id, nom_fichier, chemin, description)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$user_id, $fileName, $uploadPath, $description]);
                $success_msg = "Document ajouté avec succès !";

                $stmt = $conn->prepare("
                    SELECT * FROM dossier_documents 
                    WHERE user_id = ? ORDER BY created_at DESC
                ");
                $stmt->execute([$user_id]);
                $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            $error_msg = "Veuillez sélectionner un fichier.";
        }
    }
}
include "views/dossier_medical.view.php"
?>
