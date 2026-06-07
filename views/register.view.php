
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte | Dr. Ahmed Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/register.css">
    
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
    <main class="register-wrapper">
        <div class="register-container">

            <!-- Panneau gauche -->
            <div class="register-left">
                <div class="brand-icon"><i class="fa-solid fa-user-plus"></i></div>
                <div>
                    <h2>Créez votre espace patient</h2>
                    <p>Deux étapes rapides pour accéder à tous nos services en ligne.</p>

                    <!-- Stepper affiché uniquement sur desktop -->
                    <div class="stepper">
                        <div class="step-item">
                            <div class="step-circle <?= ($step == 1) ? 'active' : 'done' ?>">
                                <?= ($step > 1) ? '<i class="fa-solid fa-check"></i>' : '1' ?>
                            </div>
                            <span class="step-label <?= ($step == 1) ? 'active' : 'done' ?>">Informations personnelles</span>
                        </div>
                        <div class="step-item">
                            <div class="step-circle <?= ($step == 2) ? 'active' : '' ?>">2</div>
                            <span class="step-label <?= ($step == 2) ? 'active' : '' ?>">Identifiants de connexion</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panneau droit : formulaire -->
            <div class="register-right">

                <!-- Barre de progression -->
                <div class="progress-bar-wrapper">
                    <div class="progress-info">
                        <span>Étape <?= $step ?> sur 2</span>
                        <span><?= ($step == 1) ? '50%' : '100%' ?></span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= ($step == 1) ? '50%' : '100%' ?>;"></div>
                    </div>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- ── ÉTAPE 1 ── -->
                <?php if ($step == 1): ?>

                    <h3>Informations personnelles</h3>
                    <p class="subtitle">Renseignez vos informations d'identité pour créer votre dossier patient.</p>

                    <form method="post" action="">
                        <div class="form-grid">

                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-user field-icon"></i>
                                    <input type="text" id="nom" name="nom" placeholder="Benali" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="prenom">Prénom</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-user field-icon"></i>
                                    <input type="text" id="prenom" name="prenom" placeholder="Ahmed" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cin">CIN</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-id-card field-icon"></i>
                                    <input type="text" id="cin" name="cin" placeholder="AB123456" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-phone field-icon"></i>
                                    <input type="text" id="telephone" name="telephone" placeholder="06 XX XX XX XX" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="dob">Date de naissance</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-calendar field-icon"></i>
                                    <input type="date" id="dob" name="dob" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="adresse">Adresse</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-location-dot field-icon"></i>
                                    <input type="text" id="adresse" name="adresse" placeholder="Casablanca, Maroc" required>
                                </div>
                            </div>

                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn-submit">
                                Étape suivante <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>

                    <!-- ── ÉTAPE 2 ── -->
                <?php elseif ($step == 2): ?>

                    <h3>Identifiants de connexion</h3>
                    <p class="subtitle">Choisissez un email et un mot de passe sécurisé pour votre compte.</p>

                    <form method="post" action="">
                        <div class="form-grid">

                            <div class="form-group full">
                                <label for="email">Adresse Email</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-envelope field-icon"></i>
                                    <input type="email" id="email" name="email" placeholder="exemple@email.com" required autocomplete="email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-lock field-icon"></i>
                                    <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                                    <span class="toggle-pwd" onclick="togglePwd('password','eyeIcon1')" title="Afficher / Masquer">
                                        <i class="fa-solid fa-eye" id="eyeIcon1"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirmer le mot de passe</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-lock field-icon"></i>
                                    <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required autocomplete="new-password">
                                    <span class="toggle-pwd" onclick="togglePwd('confirm_password','eyeIcon2')" title="Afficher / Masquer">
                                        <i class="fa-solid fa-eye" id="eyeIcon2"></i>
                                    </span>
                                </div>
                            </div>

                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn-submit green">
                                <i class="fa-solid fa-user-check"></i> Créer mon compte
                            </button>
                        </div>
                    </form>

                <?php endif; ?>

                <p class="login-link">
                    Vous avez déjà un compte ? <a href="login.php">Se connecter</a>
                </p>

            </div>
        </div>
    </main>

    <!-- ── FOOTER ── -->
    <footer>
        &copy; 2026 Cabinet Médical <span>Dr. Ahmed Benali</span>. Tous droits réservés.
    </footer>

    <script src="js/register.js">
    </script>

</body>

</html>