<?php
session_start();
require 'db.php'; // Connexion à la base de données (PDO)

// 1. SÉCURITÉ : Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 403 Forbidden");
    die("Accès refusé : Veuillez vous connecter.");
}

// 2. RÉCUPÉRATION DE L'ID DU RENDEZ-VOUS
$rdv_id = $_GET['id'] ?? null;
$current_user_id = $_SESSION['user_id'];

if (!$rdv_id) {
    die("ID de rendez-vous manquant.");
}

try {
    // 3. REQUÊTE SÉCURISÉE : On cherche le doc UNIQUEMENT s'il appartient à l'utilisateur connecté
    // Si un patient change l'ID dans l'URL pour voir le doc d'un autre, cette requête ne renverra RIEN.
    $stmt = $conn->prepare("
        SELECT document 
        FROM rendez_vous 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$rdv_id, $current_user_id]);
    $rdv = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rdv) {
        // Soit le RDV n'existe pas, soit il n'appartient pas à cet utilisateur
        die("Accès refusé ou document introuvable.");
    }

    $fileName = $rdv['document'];

    // 4. VÉRIFICATION DU FICHIER SUR LE SERVEUR
    if (empty($fileName)) {
        die("Aucun document n'a été joint à ce rendez-vous.");
    }

    $filePath = "uploads/" . $fileName;

    if (!file_exists($filePath)) {
        die("Erreur physique : Le fichier est introuvable sur le serveur.");
    }

    // 5. ENVOI DU FICHIER AU NAVIGATEUR (Force le téléchargement)
    // On récupère le type MIME (pdf, png, jpg...) pour que le navigateur comprenne
    $mimeType = mime_content_type($filePath);

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));

    // Nettoyer le tampon de sortie pour éviter de corrompre le fichier
    ob_clean();
    flush();

    // Lecture du fichier (PHP passe par-dessus le .htaccess car il lit en local)
    readfile($filePath);
    exit;
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
