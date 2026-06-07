<?php
session_start();
require 'db.php';

if (!isset($_SESSION['medecin_id'])) {
    header("Location: login_medecin.php");
    exit();
}

$consultation_id = intval($_GET['id'] ?? 0);

if (!$consultation_id) {
    header("Location: dashboard_medecin.php");
    exit();
}

// ── Récupérer consultation ──
$stmt = $conn->prepare("
    SELECT c.*, u.nom, u.prenom, u.cin, u.email
    FROM consultations c
    JOIN users u ON c.user_id = u.id
    WHERE c.id = ?
");
$stmt->execute([$consultation_id]);
$consultation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consultation) {
    header("Location: dashboard_medecin.php");
    exit();
}

// ── Récupérer ordonnances existantes ──
$stmt = $conn->prepare("SELECT * FROM ordonnances WHERE consultation_id = ? ORDER BY id ASC");
$stmt->execute([$consultation_id]);
$ordonnances = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Récupérer documents existants ──
$stmt = $conn->prepare("SELECT * FROM consultation_documents WHERE consultation_id = ? ORDER BY created_at ASC");
$stmt->execute([$consultation_id]);
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ══════════════════════════════════════
// TRAITEMENT POST
// ══════════════════════════════════════
$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $date_consult = $_POST['date_consult'] ?? $consultation['date_consult'];
    $diagnostic   = trim($_POST['diagnostic'] ?? '');
    $notes        = trim($_POST['notes'] ?? '');
    $prochain_rdv = trim($_POST['prochain_rdv'] ?? '');
    $tension      = trim($_POST['tension'] ?? '');
    $temperature  = trim($_POST['temperature'] ?? '');
    $poids        = trim($_POST['poids'] ?? '');
    $pouls        = trim($_POST['pouls'] ?? '');

    if (empty($diagnostic)) {
        $error_msg = "Le diagnostic est obligatoire.";
    } else {

        // ── Mettre à jour consultation ──
        $stmt = $conn->prepare("
            UPDATE consultations SET
                date_consult  = ?,
                diagnostic    = ?,
                notes         = ?,
                prochain_rdv  = ?,
                tension       = ?,
                temperature   = ?,
                poids         = ?,
                pouls         = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $date_consult,
            $diagnostic,
            $notes,
            $prochain_rdv,
            $tension,
            $temperature,
            $poids,
            $pouls,
            $consultation_id
        ]);

        // ── Supprimer anciennes ordonnances et réinsérer ──
        $stmt = $conn->prepare("DELETE FROM ordonnances WHERE consultation_id = ?");
        $stmt->execute([$consultation_id]);

        $medicaments = $_POST['medicament'] ?? [];
        $dosages     = $_POST['dosage']     ?? [];
        $posologies  = $_POST['posologie']  ?? [];
        $durees      = $_POST['duree']      ?? [];

        foreach ($medicaments as $i => $med) {
            $med = trim($med);
            if (empty($med)) continue;
            $stmt = $conn->prepare("
                INSERT INTO ordonnances (consultation_id, medicament, dosage, posologie, duree)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $consultation_id,
                $med,
                trim($dosages[$i]    ?? ''),
                trim($posologies[$i] ?? ''),
                trim($durees[$i]     ?? '')
            ]);
        }

        // ── Supprimer documents cochés ──
        $docsSupprimer = $_POST['supprimer_doc'] ?? [];
        foreach ($docsSupprimer as $doc_id) {
            $stmt = $conn->prepare("SELECT chemin FROM consultation_documents WHERE id = ? AND consultation_id = ?");
            $stmt->execute([intval($doc_id), $consultation_id]);
            $doc = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($doc) {
                if (file_exists($doc['chemin'])) unlink($doc['chemin']);
                $stmt = $conn->prepare("DELETE FROM consultation_documents WHERE id = ?");
                $stmt->execute([intval($doc_id)]);
            }
        }

        // ── Upload nouveaux documents ──
        if (isset($_FILES['documents'])) {
            $files    = $_FILES['documents'];
            $allowed  = ['pdf', 'jpg', 'jpeg', 'png'];
            $uploadDir = "uploads/consultations/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            foreach ($files['name'] as $i => $fileName) {
                if ($files['error'][$i] !== 0) continue;
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) continue;
                if ($files['size'][$i] > 5 * 1024 * 1024) continue;

                $newName    = 'consult_' . $consultation_id . '_' . time() . '_' . $i . '.' . $ext;
                $uploadPath = $uploadDir . $newName;
                move_uploaded_file($files['tmp_name'][$i], $uploadPath);

                $stmt = $conn->prepare("
                    INSERT INTO consultation_documents (consultation_id, nom_fichier, chemin)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$consultation_id, $fileName, $uploadPath]);
            }
        }

        $_SESSION['success_msg'] = "Consultation modifiée avec succès !";
        header("Location: detail_consultation_medecin.php?id=" . $consultation_id);
        exit();
    }
}
include "views/modifier_consultation.view.php";
?>
