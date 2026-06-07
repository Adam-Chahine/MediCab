<?php
$host = "localhost";
$user = "root"; // changer selon ton setup
$pass = "";     // changer selon ton setup
$db   = "medicab";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion DB : " . $e->getMessage());
}
?>