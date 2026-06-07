<?php
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : "Information indisponible.";
$type = $_GET['type'] ?? 'info'; // success | error | warning | info
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information | Cabinet Dr. Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/message.css">
</head>

<body>

    <?php
    /* ── Config visuelle selon $type ── */
    $config = [
        'success' => [
            'icon'       => 'fa-circle-check',
            'titre'      => 'Succès',
            'label'      => 'Succès',
            'card_class' => 'card-success',
            'icon_class' => 'icon-success',
            'lbl_class'  => 'label-success',
            'div_class'  => 'div-success',
            'msg_class'  => 'msg-success',
        ],
        'error' => [
            'icon'       => 'fa-circle-exclamation',
            'titre'      => 'Erreur',
            'label'      => 'Erreur',
            'card_class' => 'card-error',
            'icon_class' => 'icon-error',
            'lbl_class'  => 'label-error',
            'div_class'  => 'div-error',
            'msg_class'  => 'msg-error',
        ],
        'warning' => [
            'icon'       => 'fa-triangle-exclamation',
            'titre'      => 'Attention',
            'label'      => 'Avertissement',
            'card_class' => 'card-warning',
            'icon_class' => 'icon-warning',
            'lbl_class'  => 'label-warning',
            'div_class'  => 'div-warning',
            'msg_class'  => 'msg-warning',
        ],
        'info' => [
            'icon'       => 'fa-circle-info',
            'titre'      => 'Information',
            'label'      => 'Information',
            'card_class' => 'card-info',
            'icon_class' => 'icon-info',
            'lbl_class'  => 'label-info',
            'div_class'  => 'div-info',
            'msg_class'  => 'msg-info',
        ],
    ];
    $c = $config[$type] ?? $config['info'];
    ?>

    <!-- ── NAV ── -->
    <nav>
        <a href="index.html" class="logo">
            <i class="fa-solid fa-stethoscope"></i> Dr. Benali
        </a>
        <a href="javascript:history.back()" class="nav-back">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </nav>

    <!-- ── MAIN ── -->
    <main>
        <div class="info-card <?= $c['card_class'] ?>">

            <div class="icon-wrapper <?= $c['icon_class'] ?>">
                <i class="fa-solid <?= $c['icon'] ?>"></i>
            </div>

            <p class="type-label <?= $c['lbl_class'] ?>"><?= $c['label'] ?></p>

            <h1><?= $c['titre'] ?></h1>

            <div class="divider <?= $c['div_class'] ?>"></div>

            <div class="message-box <?= $c['msg_class'] ?>">
                <i class="fa-solid <?= $c['icon'] ?>"></i>
                <?= $message ?>
            </div>

            <div class="btns">
                <a href="dashboard.php?section=rdv" class="btn btn-primary">
                    <i class="fa-solid fa-calendar"></i> Mes rendez-vous
                </a>
                <a href="index.html" class="btn btn-outline">
                    <i class="fa-solid fa-house"></i> Accueil
                </a>
            </div>

        </div>
    </main>

    <!-- ── FOOTER ── -->
    <footer>
        &copy; 2026 Cabinet Dr. <span>Ahmed Benali</span>. Tous droits réservés.
    </footer>

</body>

</html>