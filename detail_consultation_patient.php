<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$consultation_id = intval($_GET['id'] ?? 0);

if (!$consultation_id) {
    header("Location: dashboard.php");
    exit();
}

// ── Récupérer consultation (vérifier que c'est bien CE patient) ──
$stmt = $conn->prepare("
    SELECT c.*, u.nom, u.prenom, u.cin, u.email
    FROM consultations c
    JOIN users u ON c.user_id = u.id
    WHERE c.id = ? AND c.user_id = ?
");
$stmt->execute([$consultation_id, $_SESSION['user_id']]);
$consultation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consultation) {
    header("Location: dashboard.php");
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
include "views/detail_consultation_patient.view.php"
?>