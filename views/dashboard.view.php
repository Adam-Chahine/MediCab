<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Patient | <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

    <div class="overlay" id="overlay" onclick="closeSidebar()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:150;"></div>

    <!-- ════════════════════════════ SIDEBAR ════════════════════════════ -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="logo-text"><i class="fa-solid fa-stethoscope"></i> Dr. Benali</div>
            <div class="logo-sub">Espace Patient</div>
        </div>
        <div class="sidebar-avatar">
            <div class="avatar-circle">
                <?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?>
            </div>
            <div class="avatar-info">
                <div class="name">
                    <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                </div>
                <div class="role">Patient</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a class="nav-item active" onclick="showSection('rdv', this)">
                <i class="fa-solid fa-calendar-plus"></i> Prendre RDV
            </a>
            <a class="nav-item" onclick="showSection('mes-rdv', this)">
                <i class="fa-solid fa-clock-rotate-left"></i> Mes Rendez-vous
            </a>
            <a class="nav-item" onclick="showSection('historique', this)">
                <i class="fa-solid fa-rectangle-list"></i> Historique
            </a>
            <a class="nav-item" onclick="showSection('dossier', this)">
                <i class="fa-solid fa-folder-open"></i> Mon Dossier Médical
            </a>
            <a class="nav-item" onclick="showSection('consultations', this)">
                <i class="fa-solid fa-notes-medical"></i> Mes Consultations
            </a>
            <a class="nav-item" onclick="showSection('profil', this)">
                <i class="fa-solid fa-user"></i> Mon Profil
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php" class="nav-item logout">
                <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
            </a>
        </div>
    </aside>

    <!-- ════════════════════════════ TOPBAR ════════════════════════════ -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="menu-btn" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="page-title" id="topbarTitle">Prendre un rendez-vous</span>
        </div>
        <div class="topbar-right">
            <div class="topbar-badge">
                <span class="dot"></span> <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
            </div>
        </div>
    </header>

    <!-- ════════════════════════════ MAIN ════════════════════════════ -->
    <main class="main">

        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-calendar-check"></i></div>
                <div>
                    <div class="stat-val" id="statAccepte"><?= $rdvStats['accepte'] ?? 0 ?></div>
                    <div class="stat-label">RDV acceptés</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fa-solid fa-hourglass-half"></i></div>
                <div>
                    <div class="stat-val" id="statAttente"><?= $rdvStats['en_attente'] ?? 0 ?></div>
                    <div class="stat-label">En attente</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="fa-solid fa-calendar-xmark"></i></div>
                <div>
                    <div class="stat-val" id="statRefuse"><?= $rdvStats['refuse'] ?? 0 ?></div>
                    <div class="stat-label">RDV refusés</div>
                </div>
            </div>
        </div>

        <!-- ══════════════════ SECTION 1 : PRENDRE RDV ══════════════════ -->
        <div class="section active" id="section-rdv">
            <div class="section-header">
                <h2><i class="fa-solid fa-calendar-plus" style="color:var(--secondary);margin-right:10px;"></i>Prendre un rendez-vous</h2>
                <p>Remplissez le formulaire ci-dessous. Votre demande sera examinée par le Dr. Benali.</p>
            </div>

            <?php if (!empty($_SESSION['success_msg'])): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <?= htmlspecialchars($_SESSION['success_msg']) ?>
                </div>
            <?php unset($_SESSION['success_msg']);
            endif; ?>

            <div class="alert alert-error" id="rdvError" style="display:none;">
                <i class="fa-solid fa-circle-exclamation"></i> Veuillez remplir tous les champs obligatoires.
            </div>

            <div class="card">
                <div class="card-title"><i class="fa-solid fa-notes-medical"></i> Informations de consultation</div>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="prendre_rdv">
                    <div class="form-grid-2">

                        <div class="form-group">
                            <label>Date souhaitée <span style="color:var(--danger)">*</span></label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-calendar fi"></i>
                                <input type="date" name="date_rdv" id="dateRdv" required>
                            </div>
                            <small id="dateWarning">
                                <i class="fa-solid fa-triangle-exclamation"></i> Le cabinet est fermé le dimanche.
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Heure souhaitée <span style="color:var(--danger)">*</span></label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-clock fi"></i>
                                <select name="heure_rdv" required>
                                    <option value="" disabled selected>Choisir un créneau</option>
                                    <option>09:00</option>
                                    <option>09:30</option>
                                    <option>10:00</option>
                                    <option>10:30</option>
                                    <option>11:00</option>
                                    <option>11:30</option>
                                    <option>14:00</option>
                                    <option>14:30</option>
                                    <option>15:00</option>
                                    <option>15:30</option>
                                    <option>16:00</option>
                                    <option>16:30</option>
                                    <option>17:00</option>
                                    <option>17:30</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group full">
                            <label>Motif de consultation <span style="color:var(--danger)">*</span></label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-comment-medical fi" style="top:20px;transform:none;"></i>
                                <textarea name="description" placeholder="Décrivez brièvement votre problème de santé..." required></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Maladies chroniques</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-heart-pulse fi"></i>
                                <input type="text" name="maladies" placeholder="Ex : Diabète, Hypertension, Asthme...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Médicaments actuels</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-pills fi"></i>
                                <input type="text" name="medicaments" placeholder="Ex : Metformine 500mg, Amlodipine...">
                            </div>
                        </div>

                        <div class="form-group full">
                            <label>Document médical <span style="color:#aaa;font-weight:400;">(optionnel)</span></label>
                            <div class="upload-zone" id="uploadZone">
                                <input type="file" name="document" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" onchange="updateFileName(this)">
                                <i class="fa-solid fa-cloud-arrow-up up-icon" id="uploadIcon"></i>
                                <p><span>Cliquez pour uploader</span> ou glissez-déposez (un seul Doc)</p>
                                <p style="margin-top:4px;font-size:0.75rem;">PDF, JPG, PNG — max 5 Mo</p>
                                <p id="fileName"></p>
                            </div>
                        </div>

                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Envoyer la demande
                    </button>
                </form>
            </div>
        </div>

        <!-- ══════════════════ SECTION 2 : MES RDV ══════════════════ -->
        <div class="section" id="section-mes-rdv">
            <div class="section-header">
                <h2><i class="fa-solid fa-clock-rotate-left" style="color:var(--secondary);margin-right:10px;"></i>Mes Rendez-vous</h2>
                <p>Consultez l'état de vos demandes de rendez-vous.</p>
            </div>

            <?php if (!empty($_SESSION['success_msg'])): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <?= htmlspecialchars($_SESSION['success_msg']) ?>
                </div>
            <?php unset($_SESSION['success_msg']);
            endif; ?>

            <div class="card">
                <div class="card-title"><i class="fa-solid fa-list-check"></i> Historique des rendez-vous</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($mesRdv)): ?>
                                <?php foreach ($mesRdv as $rdv): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?></td>
                                        <td><?= substr(htmlspecialchars($rdv['heure_rdv']), 0, 5) ?></td>
                                        <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            <?= htmlspecialchars($rdv['description']) ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($rdv['statut'] === 'en_attente') {
                                                $badgeClass = 'badge-waiting';
                                                $icon       = 'fa-hourglass-half';
                                                $label      = 'En attente';
                                            } elseif ($rdv['statut'] === 'accepte' && ($rdv['presence'] ?? '') === 'absent') {
                                                $badgeClass = 'badge-refused';
                                                $icon       = 'fa-person-walking-arrow-right';
                                                $label      = 'Absent';
                                            } elseif ($rdv['statut'] === 'accepte') {
                                                $badgeClass = 'badge-accepted';
                                                $icon       = 'fa-check';
                                                $label      = 'Accepté';
                                            } elseif ($rdv['statut'] === 'refuse') {
                                                $badgeClass = 'badge-refused';
                                                $icon       = 'fa-xmark';
                                                $label      = 'Refusé';
                                            } elseif ($rdv['statut'] === 'termine') {
                                                $badgeClass = 'badge-accepted';
                                                $icon       = 'fa-circle-check';
                                                $label      = 'Passé';
                                            } elseif ($rdv['statut'] === 'annule') {
                                                $badgeClass = 'badge-refused';
                                                $icon       = 'fa-ban';
                                                $label      = 'Annulé';
                                            } elseif ($rdv['statut'] === 'absent') {
                                                $badgeClass = 'badge-refused';
                                                $icon       = 'fa-person-walking-arrow-right';
                                                $label      = 'Absent';
                                            } else {
                                                $badgeClass = 'badge-waiting';
                                                $icon       = 'fa-hourglass-half';
                                                $label      = 'En attente';
                                            }
                                            ?>
                                            <span class="badge <?= $badgeClass ?>">
                                                <i class="fa-solid <?= $icon ?>"></i> <?= $label ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (in_array($rdv['statut'], ['en_attente', 'accepte'])): ?>
                                                <?php
                                                $rdv_id_val    = isset($rdv['id']) ? intval($rdv['id']) : 0;
                                                $rdv_date_fmt  = date('d/m/Y', strtotime($rdv['date_rdv']));
                                                $rdv_heure_fmt = substr($rdv['heure_rdv'], 0, 5);
                                                ?>
                                                <button class="btn-detail"
                                                    style="color:var(--danger);border-color:rgba(231,76,60,0.3);background:rgba(231,76,60,0.08);"
                                                    onclick="openAnnulModal(<?= $rdv_id_val ?>, '<?= htmlspecialchars($rdv_date_fmt, ENT_QUOTES) ?>', '<?= htmlspecialchars($rdv_heure_fmt, ENT_QUOTES) ?>')">
                                                    <i class="fa-solid fa-ban"></i> Annuler
                                                </button>
                                            <?php else: ?>
                                                <span style="font-size:0.78rem;color:#ccc;">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="empty-row">
                                    <td colspan="5">
                                        <i class="fa-solid fa-calendar-days"></i>
                                        Aucun rendez-vous trouvé.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ══ SECTION HISTORIQUE ══ -->
        <div class="section" id="section-historique">

            <div class="section-header">
                <h2>
                    <i class="fa-solid fa-rectangle-list" style="color:var(--secondary);margin-right:10px;"></i>
                    Historique de mes demandes
                </h2>
                <p>Consultez le détail complet de chacune de vos demandes de rendez-vous.</p>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Toutes mes demandes
                </div>

                <div class="filters">
                    <button class="filter-btn active" onclick="filterRdv('all', this)">
                        <i class="fa-solid fa-list"></i> Tous
                    </button>
                    <button class="filter-btn f-waiting" onclick="filterRdv('en_attente', this)">
                        <i class="fa-solid fa-hourglass-half"></i> En attente
                    </button>
                    <button class="filter-btn f-accepted" onclick="filterRdv('accepte', this)">
                        <i class="fa-solid fa-check"></i> Acceptés
                    </button>
                    <button class="filter-btn f-refused" onclick="filterRdv('refuse', this)">
                        <i class="fa-solid fa-xmark"></i> Refusés
                    </button>
                    <button class="filter-btn f-accepted" onclick="filterRdv('termine', this)">
                        <i class="fa-solid fa-check"></i> Passé
                    </button>
                    <button class="filter-btn f-absent" onclick="filterRdv('absent', this)">
                        <i class="fa-solid fa-person-walking-arrow-right"></i> Absent
                    </button>
                </div>

                <div class="table-wrap">
                    <table id="histTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="histBody">
                            <?php foreach ($rdvs as $rdv): ?>
                                <tr data-status="<?= $rdv['statut'] ?>">
                                    <td><?= htmlspecialchars($rdv['date_rdv']) ?></td>
                                    <td><?= htmlspecialchars($rdv['heure_rdv']) ?></td>
                                    <td><?= htmlspecialchars(substr($rdv['description'], 0, 30)) ?>...</td>
                                    <td>
                                        <span class="badge badge-<?= $rdv['statut'] ?>">
                                            <?= ucfirst($rdv['statut']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-detail" onclick="openModal(<?= $rdv['id'] ?>)">
                                            Détail
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="empty-state" id="emptyState" style="display:none;">
                        <i class="fa-regular fa-calendar-xmark"></i>
                        <p>Aucun rendez-vous dans cette catégorie.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ══ FIN SECTION HISTORIQUE ══ -->

        <div class="modal-overlay" id="modalOverlay" onclick="closeModalOutside(event)">
            <div class="modal" id="detailModal">

                <div class="modal-header">
                    <div class="modal-header-left">
                        <div class="modal-icon">
                            <i class="fa-solid fa-file-medical"></i>
                        </div>
                        <div>
                            <h3>Détail de la demande</h3>
                            <p id="modalSubtitle">—</p>
                        </div>
                    </div>
                    <button class="modal-close" onclick="closeModal()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="modal-status">
                        <span class="badge" id="modalBadge"></span>
                    </div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label"><i class="fa-solid fa-calendar"></i> Date</div>
                            <div class="detail-value" id="mDate">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fa-solid fa-clock"></i> Heure</div>
                            <div class="detail-value" id="mHeure">—</div>
                        </div>
                        <div class="detail-item full">
                            <div class="detail-label"><i class="fa-solid fa-tag"></i> Motif</div>
                            <div class="detail-value" id="mMotif">—</div>
                        </div>
                        <div class="detail-item full">
                            <div class="detail-label"><i class="fa-solid fa-comment-medical"></i> Description complète</div>
                            <div class="detail-value light" id="mDesc">—</div>
                        </div>
                        <div class="detail-item full">
                            <div class="detail-label"><i class="fa-solid fa-heart-pulse"></i> Maladies déclarées</div>
                            <div class="tag-list" id="mMaladies"></div>
                        </div>
                        <div class="detail-item full">
                            <div class="detail-label"><i class="fa-solid fa-pills"></i> Médicaments déclarés</div>
                            <div class="tag-list" id="mMeds"></div>
                        </div>
                        <div class="detail-item full">
                            <div class="detail-label"><i class="fa-solid fa-paperclip"></i> Document uploadé</div>
                            <div id="mFile"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ══════════════════ SECTION 3 : DOSSIER ══════════════════ -->
        <div class="section" id="section-dossier">
            <div class="section-header">
                <h2><i class="fa-solid fa-folder-open" style="color:var(--secondary);margin-right:10px;"></i>Mon Dossier Médical</h2>
                <p>Consultez vos informations médicales enregistrées par Dr. Benali.</p>
            </div>

            <?php
            // ── Récupérer dossier ──
            $stmtD = $conn->prepare("SELECT * FROM dossier_medical WHERE user_id = ?");
            $stmtD->execute([$_SESSION['user_id']]);
            $dossier = $stmtD->fetch(PDO::FETCH_ASSOC);

            // ── Récupérer documents dossier ──
            $stmtDocs = $conn->prepare("SELECT * FROM dossier_documents WHERE user_id = ? ORDER BY created_at DESC");
            $stmtDocs->execute([$_SESSION['user_id']]);
            $dossierDocs = $stmtDocs->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($dossier): ?>

                <div class="dossier-grid">

                    <!-- ── Infos de base ── -->
                    <div class="card">
                        <div class="card-title"><i class="fa-solid fa-user-injured"></i> Informations de base</div>
                        <ul class="dossier-list">
                            <li>
                                <i class="fa-solid fa-droplet" style="color:var(--danger);"></i>
                                Groupe sanguin :
                                <strong><?= !empty($dossier['groupe_sanguin']) ? htmlspecialchars($dossier['groupe_sanguin']) : '—' ?></strong>
                            </li>
                            <li>
                                <i class="fa-solid fa-ruler-vertical" style="color:var(--secondary);"></i>
                                Taille :
                                <strong><?= !empty($dossier['taille']) ? $dossier['taille'] . ' cm' : '—' ?></strong>
                            </li>
                            <li>
                                <i class="fa-solid fa-weight-scale" style="color:var(--warning);"></i>
                                Poids :
                                <strong><?= !empty($dossier['poids']) ? $dossier['poids'] . ' kg' : '—' ?></strong>
                            </li>
                            <?php if (!empty($dossier['taille']) && !empty($dossier['poids'])): ?>
                                <li>
                                    <i class="fa-solid fa-calculator" style="color:var(--accent);"></i>
                                    IMC :
                                    <?php
                                    $imc = $dossier['poids'] / pow($dossier['taille'] / 100, 2);
                                    $imcLabel = $imc < 18.5 ? 'Sous-poids' : ($imc < 25 ? 'Normal' : ($imc < 30 ? 'Surpoids' : 'Obésité'));
                                    $imcColor = $imc < 18.5 ? 'var(--secondary)' : ($imc < 25 ? 'var(--accent)' : ($imc < 30 ? 'var(--warning)' : 'var(--danger)'));
                                    ?>
                                    <strong style="color:<?= $imcColor ?>;">
                                        <?= number_format($imc, 1) ?> — <?= $imcLabel ?>
                                    </strong>
                                </li>
                            <?php endif; ?>
                            <li>
                                <i class="fa-solid fa-smoking"></i>
                                Tabac :
                                <strong><?= $dossier['tabac'] === 'non' ? 'Non fumeur' : ($dossier['tabac'] === 'ancien' ? 'Ancien fumeur' : 'Fumeur') ?></strong>
                            </li>
                            <li>
                                <i class="fa-solid fa-wine-glass"></i>
                                Alcool :
                                <strong><?= $dossier['alcool'] === 'non' ? 'Non' : ($dossier['alcool'] === 'occasionnel' ? 'Occasionnel' : 'Oui') ?></strong>
                            </li>
                            <?php if (!empty($dossier['handicap'])): ?>
                                <li>
                                    <i class="fa-solid fa-wheelchair"></i>
                                    Handicap :
                                    <strong><?= htmlspecialchars($dossier['handicap']) ?></strong>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- ── Maladies chroniques ── -->
                    <div class="card">
                        <div class="card-title"><i class="fa-solid fa-heart-pulse"></i> Maladies chroniques</div>
                        <?php if (!empty($dossier['maladies_chroniques'])): ?>
                            <ul class="dossier-list">
                                <?php foreach (explode("\n", trim($dossier['maladies_chroniques'])) as $maladie): ?>
                                    <?php if (trim($maladie)): ?>
                                        <li>
                                            <i class="fa-solid fa-circle-dot" style="color:var(--danger);"></i>
                                            <?= htmlspecialchars(trim($maladie)) ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="dossier-empty">Aucune maladie chronique déclarée.</p>
                        <?php endif; ?>
                    </div>

                    <!-- ── Traitements permanents ── -->
                    <div class="card">
                        <div class="card-title"><i class="fa-solid fa-pills"></i> Traitements permanents</div>
                        <?php if (!empty($dossier['traitements_permanents'])): ?>
                            <ul class="dossier-list">
                                <?php foreach (explode("\n", trim($dossier['traitements_permanents'])) as $traitement): ?>
                                    <?php if (trim($traitement)): ?>
                                        <li>
                                            <i class="fa-solid fa-capsules" style="color:var(--secondary);"></i>
                                            <?= htmlspecialchars(trim($traitement)) ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="dossier-empty">Aucun traitement permanent.</p>
                        <?php endif; ?>
                    </div>

                    <!-- ── Allergies + Antécédents ── -->
                    <div class="card">
                        <div class="card-title"><i class="fa-solid fa-triangle-exclamation"></i> Allergies & Antécédents</div>
                        <ul class="dossier-list">
                            <li style="flex-direction:column;align-items:flex-start;gap:4px;">
                                <span style="font-size:0.72rem;font-weight:700;text-transform:uppercase;color:#aaa;">
                                    <i class="fa-solid fa-triangle-exclamation" style="color:var(--danger);margin-right:4px;"></i>Allergies
                                </span>
                                <span style="font-size:0.87rem;">
                                    <?= !empty($dossier['allergies']) ? nl2br(htmlspecialchars($dossier['allergies'])) : '<em style="color:#ccc;">Aucune allergie déclarée</em>' ?>
                                </span>
                            </li>
                            <li style="flex-direction:column;align-items:flex-start;gap:4px;">
                                <span style="font-size:0.72rem;font-weight:700;text-transform:uppercase;color:#aaa;">
                                    <i class="fa-solid fa-people-roof" style="color:var(--warning);margin-right:4px;"></i>Antécédents familiaux
                                </span>
                                <span style="font-size:0.87rem;">
                                    <?= !empty($dossier['antecedents_familiaux']) ? nl2br(htmlspecialchars($dossier['antecedents_familiaux'])) : '<em style="color:#ccc;">Aucun antécédent familial</em>' ?>
                                </span>
                            </li>
                        </ul>
                    </div>

                    <!-- ── Documents ── -->
                    <div class="card" style="grid-column:1 / -1;">
                        <div class="card-title">
                            <i class="fa-solid fa-file-medical"></i> Mes Documents
                        </div>

                        <?php if (!empty($dossierDocs)): ?>
                            <?php foreach ($dossierDocs as $doc): ?>
                                <?php
                                $ext = strtolower(pathinfo($doc['nom_fichier'], PATHINFO_EXTENSION));
                                $isPdf = ($ext === 'pdf');
                                ?>
                                <div class="file-item">
                                    <i class="fa-solid <?= $isPdf ? 'fa-file-pdf' : 'fa-file-image' ?>"
                                        style="color:<?= $isPdf ? 'var(--danger)' : 'var(--secondary)' ?>;font-size:1.3rem;"></i>
                                    <div>
                                        <div class="file-name"><?= htmlspecialchars($doc['nom_fichier']) ?></div>
                                        <?php if (!empty($doc['description'])): ?>
                                            <div class="file-meta"><?= htmlspecialchars($doc['description']) ?></div>
                                        <?php endif; ?>
                                        <div class="file-meta"><?= date('d/m/Y', strtotime($doc['created_at'])) ?></div>
                                    </div>
                                    <a href="<?= htmlspecialchars($doc['chemin']) ?>" download="<?= htmlspecialchars($doc['nom_fichier']) ?>" class="btn-download">
                                        <i class="fa-solid fa-download"></i> Télécharger
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center;padding:30px;color:#ccc;">
                                <i class="fa-regular fa-folder-open"
                                    style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                                <p style="font-size:0.85rem;">Aucun document dans votre dossier.</p>
                                <p style="font-size:0.78rem;margin-top:8px;color:#bbb;">
                                    <i class="fa-solid fa-circle-info" style="margin-right:4px;"></i>
                                    Les documents sont ajoutés par Dr. Benali lors de vos consultations.
                                </p>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

            <?php else: ?>

                <!-- ── Dossier pas encore créé ── -->
                <div class="card">
                    <div style="text-align:center;padding:50px 20px;color:#ccc;">
                        <i class="fa-solid fa-folder-open" style="font-size:3rem;display:block;margin-bottom:16px;"></i>
                        <p style="font-size:0.95rem;font-weight:600;color:#aaa;">
                            Votre dossier médical n'a pas encore été créé.
                        </p>
                        <p style="font-size:0.82rem;margin-top:8px;">
                            Il sera rempli par Dr. Benali lors de votre prochaine consultation.
                        </p>
                    </div>
                </div>

            <?php endif; ?>

        </div>

        <!-- ══ SECTION CONSULTATIONS ══ -->
        <div class="section" id="section-consultations">
            <div class="section-header">
                <h2>
                    <i class="fa-solid fa-notes-medical"
                        style="color:var(--secondary);margin-right:10px;"></i>
                    Mes Consultations
                </h2>
                <p>Historique de vos consultations avec Dr. Benali.</p>
            </div>
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Toutes mes consultations
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Diagnostic</th>
                                <th>Prochain RDV</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($mesConsultations)): ?>
                                <?php foreach ($mesConsultations as $c): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($c['date_consult'])) ?></td>
                                        <td style="max-width:220px;overflow:hidden;
                                text-overflow:ellipsis;white-space:nowrap;">
                                            <?= htmlspecialchars($c['diagnostic']) ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($c['prochain_rdv'])): ?>
                                                <span style="color:var(--accent);font-weight:600;">
                                                    <i class="fa-solid fa-calendar-plus"
                                                        style="margin-right:5px;"></i>
                                                    <?= htmlspecialchars($c['prochain_rdv']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span style="color:#ccc;">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="detail_consultation_patient.php?id=<?= $c['id'] ?>"
                                                class="btn-detail">
                                                <i class="fa-solid fa-eye"></i> Voir détail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align:center;
                                    padding:40px;color:#ccc;">
                                        <i class="fa-solid fa-notes-medical"
                                            style="font-size:2rem;display:block;
                                    margin-bottom:10px;"></i>
                                        Aucune consultation enregistrée.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ══════════════════ SECTION 4 : PROFIL ══════════════════ -->
        <div class="section" id="section-profil">
            <div class="section-header">
                <h2><i class="fa-solid fa-user" style="color:var(--secondary);margin-right:10px;"></i>Mon Profil</h2>
                <p>Vos informations personnelles enregistrées.</p>
            </div>
            <div class="card">
                <div class="profil-header">
                    <div class="profil-avatar"><?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?></div>
                    <div class="profil-meta">
                        <h3><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h3>
                        <p>Patient enregistré · Cabinet Dr. Benali</p>
                    </div>
                </div>
                <div class="profil-grid">
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-user" style="margin-right:5px;"></i>Nom</div>
                        <div class="pf-value"><?= htmlspecialchars($user['nom']) ?></div>
                    </div>
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-user" style="margin-right:5px;"></i>Prénom</div>
                        <div class="pf-value"><?= htmlspecialchars($user['prenom']) ?></div>
                    </div>
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-envelope" style="margin-right:5px;"></i>Adresse</div>
                        <div class="pf-value"><?= htmlspecialchars($user['adresse']) ?></div>
                    </div>
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-envelope" style="margin-right:5px;"></i>Telephone</div>
                        <div class="pf-value"><?= htmlspecialchars($user['telephone']) ?></div>
                    </div>
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-id-card" style="margin-right:5px;"></i>CIN</div>
                        <div class="pf-value"><?= htmlspecialchars($user['cin']) ?></div>
                    </div>
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-envelope" style="margin-right:5px;"></i>Email</div>
                        <div class="pf-value"><?= htmlspecialchars($user['email']) ?></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ══ MODAL ANNULATION RDV ══ -->
        <div id="annulModalOverlay"
            style="display:none;position:fixed;inset:0;background:rgba(30,40,60,0.5);
     z-index:999;align-items:center;justify-content:center;padding:20px;
     backdrop-filter:blur(3px);"
            onclick="closeAnnulModal(event)">

            <div style="background:white;border-radius:24px;width:100%;max-width:460px;
         box-shadow:0 30px 80px rgba(0,0,0,0.18);animation:modalIn .3s ease;">

                <!-- Header -->
                <div style="padding:24px 28px 18px;display:flex;align-items:center;
             justify-content:space-between;border-bottom:2px solid #f0f4f8;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;border-radius:14px;
                     background:rgba(231,76,60,0.1);display:flex;align-items:center;
                     justify-content:center;font-size:1.2rem;color:var(--danger);">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                        <div>
                            <h3 style="font-size:1.05rem;font-weight:700;color:var(--primary);">
                                Annuler le rendez-vous
                            </h3>
                            <p id="annulModalSubtitle" style="font-size:0.75rem;color:#aaa;margin-top:2px;"></p>
                        </div>
                    </div>
                    <button onclick="closeAnnulModal()"
                        style="width:34px;height:34px;border-radius:50%;border:none;
                    background:#f0f4f8;font-size:1rem;color:#888;cursor:pointer;
                    display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Body -->
                <div style="padding:22px 28px 28px;">

                    <!-- Warning -->
                    <div style="background:rgba(231,76,60,0.06);border:1px solid rgba(231,76,60,0.15);
                 border-radius:12px;padding:14px 16px;margin-bottom:20px;
                 display:flex;align-items:center;gap:10px;">
                        <i class="fa-solid fa-triangle-exclamation" style="color:var(--danger);font-size:1.1rem;"></i>
                        <p style="font-size:0.83rem;color:#c0392b;">
                            Cette action est <strong>irréversible</strong>.
                            Vous devrez refaire une demande si vous changez d'avis.
                        </p>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="action" value="annuler_rdv">
                        <input type="hidden" name="rdv_id" id="annulRdvId">

                        <div style="margin-bottom:18px;">
                            <label style="font-size:0.78rem;font-weight:600;
                        color:var(--primary);display:block;margin-bottom:8px;">
                                Raison de l'annulation
                                <span style="color:#aaa;font-weight:400;"> (optionnel)</span>
                            </label>
                            <textarea name="raison_annulation" id="annulRaison"
                                placeholder="Ex : Je ne suis plus disponible ce jour-là..."
                                style="width:100%;padding:12px 14px;border:2px solid #e8ecf0;
                              border-radius:12px;font-size:0.87rem;font-family:'Poppins',sans-serif;
                              color:var(--primary);background:#fafbfc;outline:none;
                              resize:vertical;min-height:90px;transition:.3s;"
                                onfocus="this.style.borderColor='var(--secondary)';"
                                onblur="this.style.borderColor='#e8ecf0';"></textarea>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <button type="button" onclick="closeAnnulModal()"
                                style="padding:13px;background:white;
                            border:2px solid #e8ecf0;border-radius:12px;
                            font-size:0.88rem;font-weight:600;
                            font-family:'Poppins',sans-serif;
                            cursor:pointer;color:#666;transition:.25s;"
                                onmouseover="this.style.borderColor='var(--secondary)';this.style.color='var(--secondary)';"
                                onmouseout="this.style.borderColor='#e8ecf0';this.style.color='#666';">
                                <i class="fa-solid fa-xmark" style="margin-right:6px;"></i>
                                Garder le RDV
                            </button>
                            <button type="submit"
                                style="padding:13px;background:var(--danger);color:white;
                            border:none;border-radius:12px;font-size:0.88rem;font-weight:600;
                            font-family:'Poppins',sans-serif;cursor:pointer;transition:.25s;"
                                onmouseover="this.style.background='#c0392b';"
                                onmouseout="this.style.background='var(--danger)';">
                                <i class="fa-solid fa-ban" style="margin-right:6px;"></i>
                                Confirmer l'annulation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
    <script>
        const rdvData = <?php echo json_encode($rdvs); ?>;
    </script>
    <script src="js/dashboard.js"></script>

</body>

</html>