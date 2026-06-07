<?php
session_start();
require 'db.php';

if (!isset($_SESSION['medecin_id'])) {
    header("Location: login_medecin.php");
    exit();
}

$error_msg   = "";
$success_msg = "";

// ── Récupérer infos RDV ou Patient ──
$rdv     = null;
$patient = null;

if (isset($_GET['rdv_id'])) {
    $stmt = $conn->prepare("
        SELECT r.*, u.nom, u.prenom, u.id AS patient_id, u.cin, u.email
        FROM rendez_vous r
        JOIN users u ON r.user_id = u.id
        WHERE r.id = ? AND r.statut = 'accepte'
    ");
    $stmt->execute([$_GET['rdv_id']]);
    $rdv = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rdv) {
        header("Location: dashboard_medecin.php?section=rdv");
        exit();
    }
    $patient = $rdv;
} elseif (isset($_GET['user_id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['user_id']]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
    $patient['patient_id'] = $patient['id'];

    if (!$patient) {
        header("Location: dashboard_medecin.php?section=patients");
        exit();
    }
}

// ══════════════════════════════════════
// TRAITEMENT POST — Créer consultation
// ══════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id      = intval($_POST['user_id']);
    $rdv_id       = !empty($_POST['rdv_id']) ? intval($_POST['rdv_id']) : null;
    $date_consult = $_POST['date_consult'] ?? date('Y-m-d');
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

        // ── Insérer consultation ──
        $stmt = $conn->prepare("
            INSERT INTO consultations 
            (rdv_id, user_id, date_consult, diagnostic, notes, 
             tension, temperature, poids, pouls, prochain_rdv, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $rdv_id,
            $user_id,
            $date_consult,
            $diagnostic,
            $notes,
            $tension,
            $temperature,
            $poids,
            $pouls,
            $prochain_rdv
        ]);
        $consultation_id = $conn->lastInsertId();

        // ── Insérer médicaments ──
        $medicaments = $_POST['medicament'] ?? [];
        $dosages     = $_POST['dosage']     ?? [];
        $posologies  = $_POST['posologie']  ?? [];
        $durees      = $_POST['duree']      ?? [];

        foreach ($medicaments as $i => $med) {
            $med = trim($med);
            if (empty($med)) continue;

            $stmt = $conn->prepare("
                INSERT INTO ordonnances 
                (consultation_id, medicament, dosage, posologie, duree)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $consultation_id,
                $med,
                trim($dosages[$i]     ?? ''),
                trim($posologies[$i]  ?? ''),
                trim($durees[$i]      ?? '')
            ]);
        }

        // ── Upload documents ──
        if (isset($_FILES['documents'])) {
            $files    = $_FILES['documents'];
            $allowed  = ['pdf', 'jpg', 'jpeg', 'png'];
            $uploadDir = "uploads/consultations/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($files['name'] as $i => $fileName) {
                if ($files['error'][$i] !== 0) continue;

                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) continue;
                if ($files['size'][$i] > 5 * 1024 * 1024) continue;

                $newName   = 'consult_' . $consultation_id . '_' . time() . '_' . $i . '.' . $ext;
                $uploadPath = $uploadDir . $newName;
                move_uploaded_file($files['tmp_name'][$i], $uploadPath);

                $stmt = $conn->prepare("
                    INSERT INTO consultation_documents 
                    (consultation_id, nom_fichier, chemin)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$consultation_id, $fileName, $uploadPath]);
            }
        }

        // ── Marquer RDV comme terminé ──
        if ($rdv_id) {
            $stmt = $conn->prepare("UPDATE rendez_vous SET statut='termine' WHERE id=?");
            $stmt->execute([$rdv_id]);
        }

        $_SESSION['success_msg'] = "Consultation enregistrée avec succès !";
        header("Location: dashboard_medecin.php?section=consultations");
        exit();
    }
}
include "views/nouvelle_consultation.view.php";
?>