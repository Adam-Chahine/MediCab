<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? '';

if (empty($date)) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT heure_rdv 
    FROM rendez_vous 
    WHERE date_rdv = ? 
    AND statut NOT IN ('refuse', 'annule')
");
$stmt->execute([$date]);
$rows = $stmt->fetchAll(PDO::FETCH_COLUMN);

// ✅ Supprimer les secondes : "09:30:00" → "09:30"
$rows = array_map(function ($heure) {
    return substr($heure, 0, 5);
}, $rows);

echo json_encode($rows);
