<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success_msg = "";
$error_msg   = "";

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* ─────────────────────────────
   TRAITEMENT : PRENDRE RDV SECTION 1
─────────────────────────────*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'prendre_rdv') {

    $user_id    = $_SESSION['user_id'];
    $date_rdv = !empty($_POST['date_rdv'])
        ? date('Y-m-d', strtotime($_POST['date_rdv']))
        : '';
    $heure_rdv  = $_POST['heure_rdv'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $maladies   = trim($_POST['maladies'] ?? '');
    $medicaments = trim($_POST['medicaments'] ?? '');

    // ── Validation basique ──
    if (empty($date_rdv) || empty($heure_rdv) || empty($description)) {
        $error_msg = "Veuillez remplir tous les champs obligatoires.";
    } else {

        // ── Vérifier dimanche ──
        $dayOfWeek = date('w', strtotime($date_rdv));
        if ($dayOfWeek == 0) { // 0 = dimanche
            $error_msg = "Le cabinet est fermé le dimanche.";
        } else {

            // ─────────────
            // GESTION UPLOAD
            // ─────────────
            $documentName = null;

            if (isset($_FILES['document']) && $_FILES['document']['error'] === 0) {

                $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
                $fileName = $_FILES['document']['name'];
                $fileTmp  = $_FILES['document']['tmp_name'];
                $fileSize = $_FILES['document']['size'];

                $nom_patient = htmlspecialchars($user['nom']);
                $prenom_patient = htmlspecialchars($user['prenom']);

                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    $error_msg = "Format fichier non autorisé.";
                } elseif ($fileSize > 5 * 1024 * 1024) {
                    $error_msg = "Fichier trop volumineux (max 5MB).";
                } else {

                    // nettoyer nom et prénom
                    $nom_clean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nom_patient));
                    $prenom_clean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $prenom_patient));

                    // temps en millisecondes
                    $timestamp = round(microtime(true) * 1000);

                    // nouveau nom
                    $newName = $nom_clean . "_" . $prenom_clean . "_" . $timestamp . "." . $ext;

                    $uploadPath = "uploads/" . $newName;

                    if (!is_dir("uploads")) {
                        mkdir("uploads", 0777, true);
                    }

                    move_uploaded_file($fileTmp, $uploadPath);

                    $documentName = 'uploads/' . $newName;
                }
            }
            //Verification et Insertion DB
            if (empty($error_msg)) {

                // NOUVEAU CODE — Double vérification

                // 1️⃣ Vérifier si CE patient a déjà un RDV ce jour-là
                $stmt = $conn->prepare("
                    SELECT date_rdv, statut
                    FROM rendez_vous 
                    WHERE user_id = ?
                    AND date_rdv = ?
                    AND statut NOT IN ('refuse', 'annule')
                    LIMIT 1
                ");
                $stmt->execute([$user_id, $date_rdv]);
                $rdv_patient_exist = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($rdv_patient_exist) {
                    $date = date('d/m/Y', strtotime($rdv_patient_exist['date_rdv']));
                    $message = urlencode("Vous avez déjà un rendez-vous actif pour la date du $date.");
                    header("Location: message.php?type=warning&message=" . $message);
                    exit();
                }

                // 2️⃣ Vérifier si le créneau (date + heure) est déjà pris par quelqu'un d'autre
                $stmt = $conn->prepare("
                    SELECT id
                    FROM rendez_vous
                    WHERE date_rdv = ?
                    AND heure_rdv = ?
                    AND statut NOT IN ('refuse', 'annule')
                    LIMIT 1
                ");
                $stmt->execute([$date_rdv, $heure_rdv]);
                $creneau_pris = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($creneau_pris) {
                    $message = urlencode("Ce créneau est déjà réservé. Veuillez choisir une autre heure.");
                    header("Location: message.php?type=warning&message=" . $message);
                    exit();
                } else {
                    $stmt = $conn->prepare("
                        INSERT INTO rendez_vous 
                        (user_id, date_rdv, heure_rdv, description, maladies, medicaments, document, statut, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'en_attente', NOW())
                    ");

                    $stmt->execute([
                        $user_id,
                        $date_rdv,
                        $heure_rdv,
                        $description,
                        $maladies,
                        $medicaments,
                        $documentName
                    ]);

                    // ✅ Stocker message en session
                    $_SESSION['success_msg'] = "Votre demande de rendez-vous a été envoyée avec succès !";

                    // ✅ Redirection (empêche double POST)
                    header("Location: " . $_SERVER['PHP_SELF'] . "?section=rdv");
                    exit();
                }
            }
        }
    }
}
/* ─────────────────────────────
   TRAITEMENT : ANNULER RDV
─────────────────────────────*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'annuler_rdv') {

    $rdv_id           = intval($_POST['rdv_id']);
    $raison_annulation = trim($_POST['raison_annulation'] ?? '');
    $user_id          = $_SESSION['user_id'];

    // Vérifier que ce RDV appartient bien à ce patient
    // et qu'il est en attente ou accepté
    $stmt = $conn->prepare("
        SELECT id, statut FROM rendez_vous
        WHERE id = ? 
        AND user_id = ?
        AND statut IN ('en_attente', 'accepte')
        LIMIT 1
    ");
    $stmt->execute([$rdv_id, $user_id]);
    $rdv = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rdv) {
        $stmt = $conn->prepare("
            UPDATE rendez_vous 
            SET statut = 'annule',
                raison_annulation = ?
            WHERE id = ?
        ");
        $stmt->execute([$raison_annulation, $rdv_id]);
        $_SESSION['success_msg'] = "Votre rendez-vous a été annulé avec succès.";
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?section=mes-rdv");
    exit();
}
?>

<?php
// RDV STATUTS
$stmt = $conn->prepare("
        SELECT 
        SUM(CASE WHEN statut='accepte' THEN 1 ELSE 0 END) AS accepte,
        SUM(CASE WHEN statut='en_attente' THEN 1 ELSE 0 END) AS en_attente,
        SUM(CASE WHEN statut='refuse' THEN 1 ELSE 0 END) AS refuse
        FROM rendez_vous
        WHERE user_id = ?
        ");
$stmt->execute([$_SESSION['user_id']]);
$rdvStats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php
// Récupérer les rendez-vous du patient et affiche dans section mes rdvs
$stmt = $conn->prepare("SELECT id, date_rdv, heure_rdv, description, statut, presence
    FROM rendez_vous 
    WHERE user_id = ? 
    ORDER BY date_rdv DESC, heure_rdv DESC");
$stmt->execute([$_SESSION['user_id']]);
$mesRdv = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$stmt = $conn->prepare("
        SELECT id, date_rdv, heure_rdv, description, 
        maladies, medicaments, document, statut, presence, created_at
        FROM rendez_vous
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
$stmt->execute([$_SESSION['user_id']]);
$rdvs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// ── Récupérer consultations du patient ──
$stmt = $conn->prepare("
    SELECT id, date_consult, diagnostic, prochain_rdv
    FROM consultations
    WHERE user_id = ?
    ORDER BY date_consult DESC
");
$stmt->execute([$_SESSION['user_id']]);
$mesConsultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
include "views/dashboard.view.php";
?>
