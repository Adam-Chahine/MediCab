<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Médecin | Dr. Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/login_medecin.css">
</head>

<body>

    <div class="login-card">

        <div class="login-header">
            <div class="login-icon">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <div class="medecin-badge">
                <i class="fa-solid fa-shield-halved"></i> Accès Médecin
            </div>
            <h1>Dr. Benali</h1>
            <p>Connectez-vous à votre espace médecin</p>
        </div>

        <div class="divider">
            <span>IDENTIFIANTS DE CONNEXION</span>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label><i class="fa-solid fa-envelope" style="margin-right:6px;color:#94a3b8;"></i>Adresse email</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="benali@cabinet.ma"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-lock" style="margin-right:6px;color:#94a3b8;"></i>Mot de passe</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" id="passwordInput" placeholder="••••••••" required>
                    <button type="button" class="toggle-pwd" onclick="togglePassword()">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fa-solid fa-right-to-bracket"></i> Se connecter
            </button>
        </form>

        <div class="login-footer">
            <p>Espace patient ? <a href="login.php">Connexion patient</a></p>
        </div>

    </div>

    <script src="js/login_medecin.js"></script>

</body>

</html>