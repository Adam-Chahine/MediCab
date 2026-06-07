<?php
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : "Une erreur inconnue est survenue.";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur | Dr. Ahmed Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/erreur.css">
</head>

<body>

    <!-- ── NAVIGATION ── -->
    <nav>
        <a href="index.html" class="logo"><i class="fa-solid fa-stethoscope"></i> Dr. Benali</a>
        <ul class="nav-links">
            <li><a href="index.html">Accueil</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="propos.html">À propos</a></li>
            <li><a href="rdv.php" class="rdv">Rendez-vous</a></li>
        </ul>
    </nav>

    <!-- ── CONTENU ── -->
    <main>
        <div class="error-card">

            <div class="icon-wrapper">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <p class="error-code">Erreur système</p>

            <h1>Oups, quelque chose s'est mal passé !</h1>

            <div class="divider"></div>

            <div class="error-message">
                <i class="fa-solid fa-circle-info"></i><?= $message ?>
            </div>

            <div class="btn-group">
                <a href="register.php" class="btn btn-primary">
                    <i class="fa-solid fa-rotate-left"></i> Retour à l'inscription
                </a>
                <a href="index.html" class="btn btn-outline">
                    <i class="fa-solid fa-house"></i> Accueil
                </a>
            </div>

        </div>
    </main>

    <!-- ── FOOTER ── -->
    <footer>
        &copy; 2026 Cabinet Médical <span>Dr. Ahmed Benali</span>. Tous droits réservés.
    </footer>

</body>

</html>