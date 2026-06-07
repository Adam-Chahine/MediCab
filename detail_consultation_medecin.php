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

// ── Récupérer ordonnance ──
$stmt = $conn->prepare("
    SELECT * FROM ordonnances 
    WHERE consultation_id = ?
    ORDER BY id ASC
");
$stmt->execute([$consultation_id]);
$ordonnances = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Récupérer documents ──
$stmt = $conn->prepare("
    SELECT * FROM consultation_documents 
    WHERE consultation_id = ?
    ORDER BY created_at ASC
");
$stmt->execute([$consultation_id]);
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Récupérer historique consultations du patient ──
$stmt = $conn->prepare("
    SELECT id, date_consult, diagnostic
    FROM consultations
    WHERE user_id = ? AND id != ?
    ORDER BY date_consult DESC
    LIMIT 5
");
$stmt->execute([$consultation['user_id'], $consultation_id]);
$historique = $stmt->fetchAll(PDO::FETCH_ASSOC);
include "views/detail_consultation_medecin.view.php"
?>
