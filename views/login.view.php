<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Dr. Ahmed Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
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

    <!-- ── CONTENU PRINCIPAL ── -->
    <main class="login-wrapper">
        <div class="login-container">

            <!-- Panneau gauche informatif -->
            <div class="login-left">
                <div class="brand-icon"><i class="fa-solid fa-hospital-user"></i></div>
                <h2>Bienvenue sur votre espace patient</h2>
                <p>Connectez-vous pour accéder à vos rendez-vous, votre dossier médical et votre historique de consultations.</p>
                <ul class="feature-list">
                    <li>
                        <i class="fa-solid fa-calendar-check"></i>
                        Prise de rendez-vous en ligne 24h/24
                    </li>
                    <li>
                        <i class="fa-solid fa-folder-open"></i>
                        Accès à votre dossier médical numérique
                    </li>
                    <li>
                        <i class="fa-solid fa-file-medical"></i>
                        Consultation de vos ordonnances
                    </li>
                    <li>
                        <i class="fa-solid fa-shield-halved"></i>
                        Données sécurisées et confidentielles
                    </li>
                </ul>
            </div>

            <!-- Panneau droit : formulaire -->
            <div class="login-right">
                <h3>Connexion</h3>
                <p class="subtitle">Entrez vos identifiants pour accéder à votre compte</p>

                <?php if (!empty($error)): ?>
                    <div class="alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="">

                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-envelope"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="exemple@email.com"
                                required
                                autocomplete="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password">
                            <span class="toggle-pwd" onclick="togglePwd()" title="Afficher / Masquer">
                                <i class="fa-solid fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Se connecter
                    </button>

                </form>

                <div class="divider">ou</div>

                <p class="register-link">
                    Pas encore de compte ?
                    <a href="register.php">Créer un compte gratuitement</a>
                </p>
            </div>

        </div>
    </main>

    <!-- ── FOOTER ── -->
    <footer>
        &copy; 2026 Cabinet Médical <span>Dr. Ahmed Benali</span>. Tous droits réservés.
    </footer>

    <script src="js/login.js"></script>

</body>

</html>