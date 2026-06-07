<?php
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : "Une erreur est survenue lors de la mise à jour.";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur de Mise à jour | Cabinet Médical</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/erreur_update.css">
</head>

<body>

    <div class="update-card">
        <div class="icon-box">
            <i class="fa-solid fa-user-pen"></i>
        </div>
        <h2>Conflit de données</h2>
        <div class="msg-box">
            <i class="fa-solid fa-circle-exclamation"></i> <strong>Attention :</strong> <?= $message ?>
        </div>
        <p style="color: #666; margin-bottom: 25px;">Veuillez vérifier les informations du patient et réessayer.</p>
        <a href="dashboard_medecin.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Retour au Dashboard
        </a>
    </div>

</body>

</html>