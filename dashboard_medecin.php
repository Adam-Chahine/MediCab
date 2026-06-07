<?php
session_start();
require 'db.php';

if (!isset($_SESSION['medecin_id'])) {
    header("Location: login_medecin.php");
    exit();
}

// ── Infos médecin ──
$stmt = $conn->prepare("SELECT * FROM medecins WHERE id = ?");
$stmt->execute([$_SESSION['medecin_id']]);
$medecin = $stmt->fetch(PDO::FETCH_ASSOC);

// ── Statistiques ──
$stmt = $conn->query("
    SELECT 
    SUM(CASE WHEN statut='en_attente'                  THEN 1 ELSE 0 END) AS en_attente,
    SUM(CASE WHEN statut='accepte' OR statut='termine' THEN 1 ELSE 0 END) AS accepte,
    SUM(CASE WHEN statut='refuse'                      THEN 1 ELSE 0 END) AS refuse,
    SUM(CASE WHEN statut='absent'                      THEN 1 ELSE 0 END) AS absent,
    SUM(CASE WHEN statut='annule'                      THEN 1 ELSE 0 END) AS annule
    FROM rendez_vous
");
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// ── Total patients ──
$stmt = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalPatients = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// ── RDV aujourd'hui ──
$stmt = $conn->prepare("
    SELECT r.*, u.nom, u.prenom, u.cin
    FROM rendez_vous r
    JOIN users u ON r.user_id = u.id
    WHERE r.date_rdv = CURDATE()
    AND r.statut IN ('accepte', 'termine', 'absent')
    ORDER BY r.heure_rdv ASC
");
$stmt->execute();
$rdvAujourdhui = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Tous les RDV ──
$stmt = $conn->prepare("
    SELECT r.*, u.nom, u.prenom, u.cin, u.email
    FROM rendez_vous r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.date_rdv DESC, r.heure_rdv DESC
");
$stmt->execute();
$tousRdv = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Liste patients ──
$stmt = $conn->query("
    SELECT u.*, COUNT(r.id) AS nb_rdv
    FROM users u
    LEFT JOIN rendez_vous r ON u.id = r.user_id
    GROUP BY u.id
    ORDER BY u.nom ASC
");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ══════════════════════════════════════
// TRAITEMENTS POST
// ══════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // ── Accepter ──
    if ($_POST['action'] === 'accepter') {
        $rdv_id = intval($_POST['rdv_id']);
        $stmt = $conn->prepare("UPDATE rendez_vous SET statut='accepte' WHERE id=?");
        $stmt->execute([$rdv_id]);
        $_SESSION['success_msg'] = "Rendez-vous accepté avec succès.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?section=rdv");
        exit();
    }

    // ── Refuser ──
    if ($_POST['action'] === 'refuser') {
        $rdv_id = intval($_POST['rdv_id']);
        $stmt = $conn->prepare("UPDATE rendez_vous SET statut='refuse' WHERE id=?");
        $stmt->execute([$rdv_id]);
        $_SESSION['success_msg'] = "Rendez-vous refusé.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?section=rdv");
        exit();
    }

    // ── Annuler ──
    if ($_POST['action'] === 'annuler_rdv') {
        $rdv_id            = intval($_POST['rdv_id']);
        $raison_annulation = trim($_POST['raison_annulation'] ?? '');
        $stmt = $conn->prepare("
            UPDATE rendez_vous 
            SET statut = 'annule', raison_annulation = ?
            WHERE id = ? AND statut IN ('en_attente', 'accepte')
        ");
        $stmt->execute([$raison_annulation, $rdv_id]);
        $_SESSION['success_msg'] = "Rendez-vous annulé avec succès.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?section=rdv");
        exit();
    }
}

// ── Traitement présence ──
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'])
    && $_POST['action'] === 'marquer_presence'
) {

    $rdv_id   = intval($_POST['rdv_id']);
    $presence = $_POST['presence'];

    if ($presence === 'present') {
        $stmt = $conn->prepare("UPDATE rendez_vous SET presence='present' WHERE id=?");
        $stmt->execute([$rdv_id]);
        header("Location: nouvelle_consultation.php?rdv_id=" . $rdv_id);
        exit();
    }

    if ($presence === 'absent') {
        $stmt = $conn->prepare("
            UPDATE rendez_vous SET presence='absent', statut='absent' WHERE id=?
        ");
        $stmt->execute([$rdv_id]);
        header("Location: dashboard_medecin.php");
        exit();
    }
}
include "views/dashboard_medecin.view.php"
?>
