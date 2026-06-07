<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Médecin | Dr. Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard_medecin.css">    
</head>

<body>

    <div class="overlay" id="overlay" onclick="closeSidebar()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:150;"></div>

    <!-- ════════ SIDEBAR ════════ -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="logo-text"><i class="fa-solid fa-stethoscope"></i> Dr. Benali</div>
            <div class="logo-sub">Espace Médecin</div>
        </div>
        <div class="sidebar-avatar">
            <div class="avatar-circle">
                <?= strtoupper(substr($medecin['prenom'], 0, 1) . substr($medecin['nom'], 0, 1)) ?>
            </div>
            <div class="avatar-info">
                <div class="name"><?= htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) ?></div>
                <div class="role"><?= htmlspecialchars($medecin['specialite']) ?></div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a class="nav-item active" onclick="showSection('dashboard', this)">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
            <a class="nav-item" onclick="showSection('rdv', this)">
                <i class="fa-solid fa-calendar-days"></i> Rendez-vous
                <?php if ($stats['en_attente'] > 0): ?>
                    <span class="nav-badge"><?= $stats['en_attente'] ?></span>
                <?php endif; ?>
            </a>
            <a class="nav-item" onclick="showSection('patients', this)">
                <i class="fa-solid fa-users"></i> Patients
            </a>
            <a class="nav-item" onclick="showSection('consultations', this)">
                <i class="fa-solid fa-notes-medical"></i> Consultations
            </a>
            <a class="nav-item" onclick="showSection('modifierpatient', this)">
                <i class="fa-solid fa-user-pen"></i> Modifier Patients
            </a>
            <a class="nav-item" onclick="showSection('profil', this)">
                <i class="fa-solid fa-user-doctor"></i> Mon Profil
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout_medecin.php" class="nav-item logout">
                <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
            </a>
        </div>
    </aside>

    <!-- ════════ TOPBAR ════════ -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="menu-btn" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="page-title" id="topbarTitle">Dashboard</span>
        </div>
        <div class="topbar-right">
            <div class="topbar-badge">
                <span class="dot"></span>
                Dr. <?= htmlspecialchars($medecin['nom']) ?>
            </div>
        </div>
    </header>

    <!-- ════════ MAIN ════════ -->
    <main class="main">

        <!-- ══ SECTION DASHBOARD ══ -->
        <div class="section active" id="section-dashboard">
            <div class="section-header">
                <h2><i class="fa-solid fa-gauge" style="color:var(--secondary);margin-right:10px;"></i>Dashboard</h2>
                <p>Bienvenue Dr. <?= htmlspecialchars($medecin['nom']) ?> — <?= date('l d F Y') ?></p>
            </div>

            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <div class="stat-val"><?= $totalPatients ?></div>
                        <div class="stat-label">Total patients</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fa-solid fa-hourglass-half"></i></div>
                    <div>
                        <div class="stat-val"><?= $stats['en_attente'] ?></div>
                        <div class="stat-label">En attente</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fa-solid fa-calendar-check"></i></div>
                    <div>
                        <div class="stat-val"><?= $stats['accepte'] ?></div>
                        <div class="stat-label">RDV acceptés</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fa-solid fa-calendar-xmark"></i></div>
                    <div>
                        <div class="stat-val"><?= $stats['refuse'] ?></div>
                        <div class="stat-label">RDV refusés</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gray"><i class="fa-solid fa-ban"></i></div>
                    <div>
                        <div class="stat-val"><?= $stats['annule'] ?? 0 ?></div>
                        <div class="stat-label">RDV annulés</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fa-solid fa-calendar-day"></i></div>
                    <div>
                        <div class="stat-val"><?= count($rdvAujourdhui) ?></div>
                        <div class="stat-label">RDV aujourd'hui</div>
                    </div>
                </div>
            </div>

            <!-- RDV aujourd'hui -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-calendar-day"></i>
                    RDV d'aujourd'hui — <?= date('d/m/Y') ?>
                </div>
                <?php if (!empty($rdvAujourdhui)): ?>
                    <div class="today-grid">
                        <?php foreach ($rdvAujourdhui as $rdv): ?>
                            <div class="today-card" id="rdvcard-<?= $rdv['id'] ?>"
                                style="<?= $rdv['statut'] === 'termine' ? 'opacity:0.75;' : '' ?>">

                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                                    <div class="today-time">
                                        <i class="fa-solid fa-clock" style="margin-right:6px;"></i>
                                        <?= substr($rdv['heure_rdv'], 0, 5) ?>
                                    </div>
                                    <?php if ($rdv['statut'] === 'termine'): ?>
                                        <span style="background:rgba(46,204,113,0.12);color:var(--accent);
                                    border-radius:50px;padding:3px 10px;font-size:0.7rem;font-weight:700;
                                    display:flex;align-items:center;gap:5px;">
                                            <i class="fa-solid fa-circle-check"></i> Passé
                                        </span>
                                    <?php elseif ($rdv['statut'] === 'absent'): ?>
                                        <span style="background:rgba(231,76,60,0.1);color:var(--danger);
                                    border-radius:50px;padding:3px 10px;font-size:0.7rem;font-weight:700;
                                    display:flex;align-items:center;gap:5px;">
                                            <i class="fa-solid fa-circle-xmark"></i> Absent
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="today-patient">
                                    <i class="fa-solid fa-user" style="margin-right:6px;color:#888;"></i>
                                    <?= htmlspecialchars($rdv['prenom'] . ' ' . $rdv['nom']) ?>
                                </div>
                                <div class="today-motif">
                                    <?= htmlspecialchars(substr($rdv['description'], 0, 50)) ?>...
                                </div>

                                <?php if ($rdv['statut'] === 'termine'): ?>
                                    <?php
                                    $stmtC = $conn->prepare("SELECT id FROM consultations WHERE rdv_id=? LIMIT 1");
                                    $stmtC->execute([$rdv['id']]);
                                    $consult = $stmtC->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <?php if ($consult): ?>
                                        <div style="margin-top:14px;">
                                            <a href="detail_consultation_medecin.php?id=<?= $consult['id'] ?>"
                                                style="display:flex;align-items:center;justify-content:center;
                                   gap:8px;padding:10px;background:rgba(52,152,219,0.1);
                                   border:2px solid rgba(52,152,219,0.2);border-radius:10px;
                                   font-size:0.82rem;font-weight:700;color:var(--secondary);
                                   text-decoration:none;transition:.25s;"
                                                onmouseover="this.style.background='var(--secondary)';this.style.color='white';"
                                                onmouseout="this.style.background='rgba(52,152,219,0.1)';this.style.color='var(--secondary)';">
                                                <i class="fa-solid fa-eye"></i> Voir consultation
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                <?php elseif ($rdv['statut'] === 'absent'): ?>
                                    <div style="margin-top:12px;font-size:0.78rem;color:#bbb;font-style:italic;text-align:center;">
                                        RDV archivé
                                    </div>

                                <?php elseif ($rdv['presence'] === 'present'): ?>
                                    <div style="margin-top:14px;">
                                        <div style="display:flex;align-items:center;gap:7px;
                                    font-size:0.78rem;font-weight:700;color:var(--accent);margin-bottom:10px;">
                                            <i class="fa-solid fa-circle-check"></i> Présent
                                        </div>
                                        <a href="nouvelle_consultation.php?rdv_id=<?= $rdv['id'] ?>"
                                            style="display:flex;align-items:center;justify-content:center;
                                   gap:8px;padding:10px;background:var(--accent);color:white;
                                   border-radius:10px;font-size:0.82rem;font-weight:700;
                                   text-decoration:none;transition:.25s;"
                                            onmouseover="this.style.background='#27ae60';"
                                            onmouseout="this.style.background='var(--accent)';">
                                            <i class="fa-solid fa-notes-medical"></i> Démarrer consultation
                                        </a>
                                    </div>

                                <?php else: ?>
                                    <div style="margin-top:14px;">
                                        <div style="font-size:0.75rem;font-weight:600;color:#aaa;margin-bottom:8px;text-align:center;">
                                            <i class="fa-solid fa-circle-question" style="margin-right:4px;color:var(--warning);"></i>
                                            Patient présent ?
                                        </div>
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                            <form method="POST">
                                                <input type="hidden" name="action" value="marquer_presence">
                                                <input type="hidden" name="rdv_id" value="<?= $rdv['id'] ?>">
                                                <input type="hidden" name="presence" value="present">
                                                <button type="submit" style="width:100%;padding:10px 6px;
                                                    background:rgba(46,204,113,0.1);border:2px solid rgba(46,204,113,0.3);
                                                    border-radius:10px;color:var(--accent);font-size:0.8rem;font-weight:700;
                                                    font-family:'Poppins',sans-serif;cursor:pointer;transition:.25s;
                                                    display:flex;align-items:center;justify-content:center;gap:6px;"
                                                    onmouseover="this.style.background='var(--accent)';this.style.color='white';"
                                                    onmouseout="this.style.background='rgba(46,204,113,0.1)';this.style.color='var(--accent)';">
                                                    <i class="fa-solid fa-check"></i> Présent
                                                </button>
                                            </form>
                                            <form method="POST">
                                                <input type="hidden" name="action" value="marquer_presence">
                                                <input type="hidden" name="rdv_id" value="<?= $rdv['id'] ?>">
                                                <input type="hidden" name="presence" value="absent">
                                                <button type="submit" style="width:100%;padding:10px 6px;
                                                    background:rgba(231,76,60,0.1);border:2px solid rgba(231,76,60,0.3);
                                                    border-radius:10px;color:var(--danger);font-size:0.8rem;font-weight:700;
                                                    font-family:'Poppins',sans-serif;cursor:pointer;transition:.25s;
                                                    display:flex;align-items:center;justify-content:center;gap:6px;"
                                                    onmouseover="this.style.background='var(--danger)';this.style.color='white';"
                                                    onmouseout="this.style.background='rgba(231,76,60,0.1)';this.style.color='var(--danger)';">
                                                    <i class="fa-solid fa-xmark"></i> Absent
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="today-empty">
                        <i class="fa-regular fa-calendar-check"></i>
                        Aucun rendez-vous prévu aujourd'hui.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ══ SECTION RDV ══ -->
        <div class="section" id="section-rdv">
            <div class="section-header">
                <h2><i class="fa-solid fa-calendar-days" style="color:var(--secondary);margin-right:10px;"></i>Gestion des Rendez-vous</h2>
                <p>Gérez les demandes de rendez-vous.</p>
            </div>

            <?php if (!empty($_SESSION['success_msg'])): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <?= htmlspecialchars($_SESSION['success_msg']) ?>
                </div>
            <?php unset($_SESSION['success_msg']);
            endif; ?>

            <div class="card">
                <div class="card-title"><i class="fa-solid fa-list-check"></i> Tous les rendez-vous</div>

                <div class="filters">
                    <button class="filter-btn active" onclick="filterRdv('all', this)">
                        <i class="fa-solid fa-list"></i> Tous
                    </button>
                    <button class="filter-btn" onclick="filterRdv('en_attente', this)">
                        <i class="fa-solid fa-hourglass-half"></i> En attente
                    </button>
                    <button class="filter-btn" onclick="filterRdv('accepte', this)">
                        <i class="fa-solid fa-check"></i> Acceptés
                    </button>
                    <button class="filter-btn" onclick="filterRdv('refuse', this)">
                        <i class="fa-solid fa-xmark"></i> Refusés
                    </button>
                    <button class="filter-btn" onclick="filterRdv('termine', this)">
                        <i class="fa-solid fa-circle-check"></i> Passés
                    </button>
                    <button class="filter-btn" onclick="filterRdv('annule', this)">
                        <i class="fa-solid fa-ban"></i> Annulés
                    </button>
                </div>

                <div class="table-wrap">
                    <table id="rdvTable">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="rdvBody">
                            <?php foreach ($tousRdv as $rdv): ?>
                                <tr data-status="<?= $rdv['statut'] ?>">
                                    <td><?= htmlspecialchars($rdv['prenom'] . ' ' . $rdv['nom']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?></td>
                                    <td><?= substr($rdv['heure_rdv'], 0, 5) ?></td>
                                    <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        <?= htmlspecialchars($rdv['description']) ?>
                                    </td>
                                    <td>
                                        <?php if ($rdv['statut'] === 'en_attente'): ?>
                                            <span class="badge badge-waiting">
                                                <i class="fa-solid fa-hourglass-half"></i> En attente
                                            </span>
                                        <?php elseif ($rdv['statut'] === 'accepte'): ?>
                                            <span class="badge badge-accepted">
                                                <i class="fa-solid fa-check"></i> Accepté
                                            </span>
                                        <?php elseif ($rdv['statut'] === 'refuse'): ?>
                                            <span class="badge badge-refused">
                                                <i class="fa-solid fa-xmark"></i> Refusé
                                            </span>
                                        <?php elseif ($rdv['statut'] === 'termine'): ?>
                                            <span class="badge" style="background:rgba(46,204,113,0.12);color:var(--accent);">
                                                <i class="fa-solid fa-circle-check"></i> Passé
                                            </span>
                                        <?php elseif ($rdv['statut'] === 'absent'): ?>
                                            <span class="badge" style="background:rgba(231,76,60,0.1);color:var(--danger);">
                                                <i class="fa-solid fa-person-walking-arrow-right"></i> Absent
                                            </span>
                                        <?php elseif ($rdv['statut'] === 'annule'): ?>
                                            <span class="badge" style="background:rgba(150,150,150,0.1);color:#888;">
                                                <i class="fa-solid fa-ban"></i> Annulé
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px;flex-wrap:wrap;">

                                            <?php if ($rdv['statut'] === 'en_attente'): ?>
                                                <?php
                                                $m_id          = intval($rdv['id']);
                                                $m_date        = date('d/m/Y', strtotime($rdv['date_rdv']));
                                                $m_heure       = substr($rdv['heure_rdv'], 0, 5);
                                                ?>
                                                <button class="btn-detail btn-open-rdv"
                                                    data-id="<?= $m_id ?>"
                                                    data-patient="<?= htmlspecialchars($rdv['prenom'] . ' ' . $rdv['nom'], ENT_QUOTES) ?>"
                                                    data-date="<?= $m_date ?>"
                                                    data-heure="<?= $m_heure ?>"
                                                    data-motif="<?= htmlspecialchars($rdv['description'], ENT_QUOTES) ?>"
                                                    data-maladies="<?= htmlspecialchars($rdv['maladies'] ?? '', ENT_QUOTES) ?>"
                                                    data-medicaments="<?= htmlspecialchars($rdv['medicaments'] ?? '', ENT_QUOTES) ?>"
                                                    data-document="<?= htmlspecialchars($rdv['document'] ?? '', ENT_QUOTES) ?>">
                                                    <i class="fa-solid fa-eye"></i> Détail
                                                </button>

                                                <button class="btn-refuse btn-open-annul"
                                                    data-id="<?= $m_id ?>"
                                                    data-date="<?= $m_date ?>"
                                                    data-heure="<?= $m_heure ?>"
                                                    data-patient="<?= htmlspecialchars($rdv['prenom'] . ' ' . $rdv['nom'], ENT_QUOTES) ?>">
                                                    <i class="fa-solid fa-ban"></i> Annuler
                                                </button>

                                            <?php elseif ($rdv['statut'] === 'accepte'): ?>
                                                <?php if ($rdv['presence'] === 'present'): ?>
                                                    <a href="nouvelle_consultation.php?rdv_id=<?= $rdv['id'] ?>"
                                                        class="btn-consult">
                                                        <i class="fa-solid fa-notes-medical"></i> Consultation
                                                    </a>
                                                <?php else: ?>
                                                    <span style="display:inline-flex;align-items:center;gap:6px;
                                                padding:6px 12px;border-radius:50px;font-size:0.75rem;
                                                font-weight:600;color:var(--warning);background:rgba(243,156,18,0.08);
                                                border:1px solid rgba(243,156,18,0.2);">
                                                        <i class="fa-solid fa-hourglass-half"></i>
                                                        En attente de présence
                                                    </span>
                                                <?php endif; ?>
                                                <!-- ✅ Bouton annuler pour accepté -->
                                                <button class="btn-refuse"
                                                    onclick="openAnnulModalMed(
                                                    <?= $rdv['id'] ?>,
                                                    '<?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?>',
                                                    '<?= substr($rdv['heure_rdv'], 0, 5) ?>',
                                                    '<?= htmlspecialchars($rdv['prenom'] . ' ' . $rdv['nom'], ENT_QUOTES) ?>'
                                                    )">
                                                    <i class="fa-solid fa-ban"></i> Annuler
                                                </button>

                                            <?php elseif ($rdv['statut'] === 'absent'): ?>
                                                <span style="display:inline-flex;align-items:center;gap:6px;
                                            padding:6px 12px;border-radius:50px;font-size:0.75rem;
                                            font-weight:600;color:var(--danger);background:rgba(231,76,60,0.08);
                                            border:1px solid rgba(231,76,60,0.2);">
                                                    <i class="fa-solid fa-person-walking-arrow-right"></i>
                                                    Patient absent
                                                </span>

                                            <?php elseif ($rdv['statut'] === 'termine'): ?>
                                                <span style="display:inline-flex;align-items:center;gap:6px;
                                            padding:6px 12px;border-radius:50px;font-size:0.75rem;
                                            font-weight:600;color:#aaa;background:#f5f5f5;border:1px solid #eee;">
                                                    <i class="fa-solid fa-calendar-check"></i>
                                                    Consultation terminée
                                                </span>

                                            <?php elseif ($rdv['statut'] === 'refuse'): ?>
                                                <span style="display:inline-flex;align-items:center;gap:6px;
                                            padding:6px 12px;border-radius:50px;font-size:0.75rem;
                                            font-weight:600;color:#aaa;background:#f5f5f5;border:1px solid #eee;">
                                                    <i class="fa-solid fa-ban"></i> RDV refusé
                                                </span>

                                            <?php elseif ($rdv['statut'] === 'annule'): ?>
                                                <span style="display:inline-flex;align-items:center;gap:6px;
                                            padding:6px 12px;border-radius:50px;font-size:0.75rem;
                                            font-weight:600;color:#888;background:rgba(150,150,150,0.08);
                                            border:1px solid rgba(150,150,150,0.2);">
                                                    <i class="fa-solid fa-ban"></i> RDV annulé
                                                </span>

                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ══ MODAL DÉTAIL RDV ══ -->
        <div id="rdvModalOverlay"
            style="display:none;position:fixed;inset:0;background:rgba(30,40,60,0.5);
         z-index:999;align-items:center;justify-content:center;padding:20px;
         backdrop-filter:blur(3px);"
            onclick="closeRdvModal(event)">
            <div style="background:white;border-radius:24px;width:100%;max-width:560px;
             max-height:90vh;overflow-y:auto;box-shadow:0 30px 80px rgba(0,0,0,0.18);
             animation:modalIn .3s ease;">
                <div style="padding:24px 28px 18px;display:flex;align-items:center;
                 justify-content:space-between;border-bottom:2px solid #f0f4f8;
                 position:sticky;top:0;background:white;border-radius:24px 24px 0 0;z-index:1;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;border-radius:14px;
                         background:rgba(52,152,219,0.1);display:flex;align-items:center;
                         justify-content:center;font-size:1.2rem;color:var(--secondary);">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <h3 style="font-size:1.05rem;font-weight:700;color:var(--primary);">Détail du rendez-vous</h3>
                            <p id="rdvModalSubtitle" style="font-size:0.75rem;color:#aaa;margin-top:2px;"></p>
                        </div>
                    </div>
                    <button onclick="closeRdvModal()"
                        style="width:34px;height:34px;border-radius:50%;border:none;
                        background:#f0f4f8;font-size:1rem;color:#888;cursor:pointer;
                        display:flex;align-items:center;justify-content:center;"
                        onmouseover="this.style.background='#fdf0f0';this.style.color='var(--danger)';"
                        onmouseout="this.style.background='#f0f4f8';this.style.color='#888';">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div style="padding:22px 28px 28px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                        <div style="background:#f0f4f8;border-radius:12px;padding:14px 16px;">
                            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:5px;">
                                <i class="fa-solid fa-user" style="margin-right:4px;"></i>Patient
                            </div>
                            <div id="rdvModalPatient" style="font-size:0.9rem;font-weight:700;color:var(--primary);"></div>
                        </div>
                        <div style="background:#f0f4f8;border-radius:12px;padding:14px 16px;">
                            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:5px;">
                                <i class="fa-solid fa-calendar" style="margin-right:4px;"></i>Date & Heure
                            </div>
                            <div id="rdvModalDate" style="font-size:0.9rem;font-weight:700;color:var(--primary);"></div>
                        </div>
                    </div>
                    <div style="background:#f0f4f8;border-radius:12px;padding:14px 16px;margin-bottom:16px;">
                        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:8px;">
                            <i class="fa-solid fa-comment-medical" style="margin-right:4px;"></i>Motif
                        </div>
                        <div id="rdvModalMotif" style="font-size:0.88rem;color:var(--primary);line-height:1.6;"></div>
                    </div>
                    <div style="background:#f0f4f8;border-radius:12px;padding:14px 16px;margin-bottom:16px;">
                        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:8px;">
                            <i class="fa-solid fa-heart-pulse" style="margin-right:4px;color:var(--danger);"></i>Maladies
                        </div>
                        <div id="rdvModalMaladies"></div>
                    </div>
                    <div style="background:#f0f4f8;border-radius:12px;padding:14px 16px;margin-bottom:16px;">
                        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:8px;">
                            <i class="fa-solid fa-pills" style="margin-right:4px;color:var(--secondary);"></i>Médicaments
                        </div>
                        <div id="rdvModalMedicaments"></div>
                    </div>
                    <div style="background:#f0f4f8;border-radius:12px;padding:14px 16px;margin-bottom:22px;">
                        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:8px;">
                            <i class="fa-solid fa-paperclip" style="margin-right:4px;"></i>Document
                        </div>
                        <div id="rdvModalDoc"></div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <form method="POST">
                            <input type="hidden" name="action" value="accepter">
                            <input type="hidden" name="rdv_id" id="modalRdvId">
                            <button type="submit"
                                style="width:100%;padding:14px;background:rgba(46,204,113,0.1);
                                border:2px solid rgba(46,204,113,0.3);border-radius:14px;
                                color:var(--accent);font-size:0.9rem;font-weight:700;
                                font-family:'Poppins',sans-serif;cursor:pointer;transition:.3s;
                                display:flex;align-items:center;justify-content:center;gap:8px;"
                                onmouseover="this.style.background='var(--accent)';this.style.color='white';"
                                onmouseout="this.style.background='rgba(46,204,113,0.1)';this.style.color='var(--accent)';">
                                <i class="fa-solid fa-check"></i> Accepter
                            </button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="action" value="refuser">
                            <input type="hidden" name="rdv_id" id="modalRdvId2">
                            <button type="submit"
                                style="width:100%;padding:14px;background:rgba(231,76,60,0.1);
                                border:2px solid rgba(231,76,60,0.3);border-radius:14px;
                                color:var(--danger);font-size:0.9rem;font-weight:700;
                                font-family:'Poppins',sans-serif;cursor:pointer;transition:.3s;
                                display:flex;align-items:center;justify-content:center;gap:8px;"
                                onmouseover="this.style.background='var(--danger)';this.style.color='white';"
                                onmouseout="this.style.background='rgba(231,76,60,0.1)';this.style.color='var(--danger)';">
                                <i class="fa-solid fa-xmark"></i> Refuser
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ MODAL ANNULATION MÉDECIN ══ -->
        <div id="annulMedOverlay"
            style="display:none;position:fixed;inset:0;background:rgba(30,40,60,0.5);
         z-index:999;align-items:center;justify-content:center;padding:20px;
         backdrop-filter:blur(3px);"
            onclick="closeAnnulModalMed(event)">
            <div style="background:white;border-radius:24px;width:100%;max-width:460px;
             box-shadow:0 30px 80px rgba(0,0,0,0.18);animation:modalIn .3s ease;">
                <div style="padding:24px 28px 18px;display:flex;align-items:center;
                 justify-content:space-between;border-bottom:2px solid #f0f4f8;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;border-radius:14px;
                         background:rgba(231,76,60,0.1);display:flex;align-items:center;
                         justify-content:center;font-size:1.2rem;color:var(--danger);">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                        <div>
                            <h3 style="font-size:1.05rem;font-weight:700;color:var(--primary);">Annuler le rendez-vous</h3>
                            <p id="annulMedSubtitle" style="font-size:0.75rem;color:#aaa;margin-top:2px;"></p>
                        </div>
                    </div>
                    <button onclick="closeAnnulModalMed()"
                        style="width:34px;height:34px;border-radius:50%;border:none;
                        background:#f0f4f8;font-size:1rem;color:#888;cursor:pointer;
                        display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div style="padding:22px 28px 28px;">
                    <form method="POST">
                        <input type="hidden" name="action" value="annuler_rdv">
                        <input type="hidden" name="rdv_id" id="annulMedRdvId">
                        <div style="margin-bottom:18px;">
                            <label style="font-size:0.78rem;font-weight:600;color:var(--primary);
                            display:block;margin-bottom:8px;">
                                Raison de l'annulation
                                <span style="color:#aaa;font-weight:400;"> (optionnel)</span>
                            </label>
                            <textarea name="raison_annulation"
                                placeholder="Ex : Médecin indisponible, urgence..."
                                style="width:100%;padding:12px 14px;border:2px solid #e8ecf0;
                                  border-radius:12px;font-size:0.87rem;font-family:'Poppins',sans-serif;
                                  color:var(--primary);background:#fafbfc;outline:none;
                                  resize:vertical;min-height:90px;transition:.3s;"
                                onfocus="this.style.borderColor='var(--secondary)';"
                                onblur="this.style.borderColor='#e8ecf0';"></textarea>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <button type="button" onclick="closeAnnulModalMed()"
                                style="padding:13px;background:white;border:2px solid #e8ecf0;
                                border-radius:12px;font-size:0.88rem;font-weight:600;
                                font-family:'Poppins',sans-serif;cursor:pointer;color:#666;transition:.25s;"
                                onmouseover="this.style.borderColor='var(--secondary)';this.style.color='var(--secondary)';"
                                onmouseout="this.style.borderColor='#e8ecf0';this.style.color='#666';">
                                <i class="fa-solid fa-xmark" style="margin-right:6px;"></i>Garder le RDV
                            </button>
                            <button type="submit"
                                style="padding:13px;background:var(--danger);color:white;
                                border:none;border-radius:12px;font-size:0.88rem;font-weight:600;
                                font-family:'Poppins',sans-serif;cursor:pointer;transition:.25s;"
                                onmouseover="this.style.background='#c0392b';"
                                onmouseout="this.style.background='var(--danger)';">
                                <i class="fa-solid fa-ban" style="margin-right:6px;"></i>Confirmer l'annulation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ══ SECTION PATIENTS ══ -->
        <div class="section" id="section-patients">
            <div class="section-header">
                <h2><i class="fa-solid fa-users" style="color:var(--secondary);margin-right:10px;"></i>Mes Patients</h2>
                <p>Liste de tous les patients enregistrés.</p>
            </div>
            <div class="card">
                <div class="card-title"><i class="fa-solid fa-user-group"></i> <?= count($patients) ?> patients enregistrés</div>
                <div class="search-container" style="margin-bottom:20px;">
                    <input type="text" id="patientSearch" placeholder="Chercher par nom, prénom ou CIN..."
                        style="width:100%;max-width:400px;padding:12px 16px;border-radius:10px;
                       border:2px solid #e8ecf0;font-family:'Poppins',sans-serif;
                       font-size:0.87rem;outline:none;"
                        onfocus="this.style.borderColor='var(--secondary)';"
                        onblur="this.style.borderColor='#e8ecf0';">
                </div>
                <div class="patient-grid">
                    <?php foreach ($patients as $patient): ?>
                        <div class="patient-card">
                            <div class="patient-card-header">
                                <div class="patient-avatar">
                                    <?= strtoupper(substr($patient['prenom'], 0, 1) . substr($patient['nom'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="patient-name"><?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></div>
                                    <div class="patient-cin">CIN : <?= htmlspecialchars($patient['cin']) ?></div>
                                </div>
                            </div>
                            <div class="patient-info"><i class="fa-solid fa-envelope"></i><?= htmlspecialchars($patient['email']) ?></div>
                            <div class="patient-info"><i class="fa-solid fa-calendar-check"></i><?= $patient['nb_rdv'] ?> rendez-vous au total</div>
                            <div class="patient-actions">
                                <a href="nouvelle_consultation.php?user_id=<?= $patient['id'] ?>" class="btn-consult">
                                    <i class="fa-solid fa-notes-medical"></i> Nouvelle consultation
                                </a>
                                <a href="dossier_medical.php?user_id=<?= $patient['id'] ?>"
                                    style="padding:7px 14px;border-radius:50px;font-size:0.75rem;
                           font-weight:700;font-family:'Poppins',sans-serif;cursor:pointer;
                           transition:.25s;display:inline-flex;align-items:center;gap:6px;
                           background:rgba(243,156,18,0.1);color:var(--warning);
                           border:2px solid rgba(243,156,18,0.3);text-decoration:none;"
                                    onmouseover="this.style.background='var(--warning)';this.style.color='white';"
                                    onmouseout="this.style.background='rgba(243,156,18,0.1)';this.style.color='var(--warning)';">
                                    <i class="fa-solid fa-folder-open"></i> Dossier médical
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ══ SECTION CONSULTATIONS ══ -->
        <div class="section" id="section-consultations">
            <div class="section-header">
                <h2><i class="fa-solid fa-notes-medical" style="color:var(--secondary);margin-right:10px;"></i>Consultations</h2>
                <p>Historique de toutes les consultations.</p>
            </div>
            <div class="card">
                <div class="card-title"><i class="fa-solid fa-clock-rotate-left"></i> Toutes les consultations</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Diagnostic</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("
                            SELECT c.*, u.nom, u.prenom
                            FROM consultations c
                            JOIN users u ON c.user_id = u.id
                            ORDER BY c.date_consult DESC
                        ");
                            $consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (!empty($consultations)):
                                foreach ($consultations as $c):
                            ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($c['date_consult'])) ?></td>
                                        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            <?= htmlspecialchars($c['diagnostic']) ?>
                                        </td>
                                        <td>
                                            <a href="detail_consultation_medecin.php?id=<?= $c['id'] ?>" class="btn-detail">
                                                <i class="fa-solid fa-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td colspan="4" style="text-align:center;padding:40px;color:#ccc;">
                                        <i class="fa-solid fa-notes-medical" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                                        Aucune consultation enregistrée.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ══ SECTION MODIFIER PATIENT ══ -->
        <div class="section" id="section-modifierpatient">
            <div class="section-header">
                <h2><i class="fa-solid fa-user-pen" style="color:var(--primary);margin-right:10px;"></i>Gestion des Informations</h2>
                <p>Sélectionnez un patient pour mettre à jour ses données personnelles.</p>
            </div>
            <div class="card">
                <div class="card-title"><i class="fa-solid fa-user-group"></i> <?= count($patients) ?> patients enregistrés</div>
                <div class="patient-grid">
                    <?php foreach ($patients as $patient): ?>
                        <div class="patient-card modifier-card">
                            <div class="patient-card-header">
                                <div class="patient-avatar">
                                    <?= strtoupper(substr($patient['prenom'], 0, 1) . substr($patient['nom'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="patient-name"><?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></div>
                                    <div class="patient-cin">CIN : <?= htmlspecialchars($patient['cin']) ?></div>
                                </div>
                            </div>
                            <div class="patient-info"><i class="fa-solid fa-envelope"></i><?= htmlspecialchars($patient['email']) ?></div>
                            <div class="patient-info"><i class="fa-solid fa-calendar-check"></i><?= $patient['nb_rdv'] ?> rendez-vous au total</div>
                            <div class="patient-actions">
                                <button onclick='openEditModal(<?= json_encode($patient) ?>)'
                                    style="width:100%;padding:10px;border-radius:50px;background:var(--primary);
                                color:white;border:none;cursor:pointer;font-weight:600;
                                font-family:'Poppins',sans-serif;display:flex;align-items:center;
                                justify-content:center;gap:8px;transition:0.3s;">
                                    <i class="fa-solid fa-pen-to-square"></i> Modifier le patient
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ══ SECTION PROFIL ══ -->
        <div class="section" id="section-profil">
            <div class="section-header">
                <h2><i class="fa-solid fa-user-doctor" style="color:var(--secondary);margin-right:10px;"></i>Mon Profil</h2>
                <p>Vos informations personnelles.</p>
            </div>
            <div class="card">
                <div style="display:flex;align-items:center;gap:24px;margin-bottom:30px;">
                    <div style="width:80px;height:80px;border-radius:50%;
                     background:linear-gradient(135deg,#0ea5e9,#10b981);
                     display:flex;align-items:center;justify-content:center;
                     font-size:2rem;font-weight:700;color:white;">
                        <?= strtoupper(substr($medecin['prenom'], 0, 1) . substr($medecin['nom'], 0, 1)) ?>
                    </div>
                    <div>
                        <h3 style="font-size:1.3rem;font-weight:700;">Dr. <?= htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) ?></h3>
                        <p style="font-size:0.82rem;color:#999;"><?= htmlspecialchars($medecin['specialite']) ?></p>
                    </div>
                </div>
                <div class="profil-grid">
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-user" style="margin-right:5px;"></i>Nom</div>
                        <div class="pf-value"><?= htmlspecialchars($medecin['nom']) ?></div>
                    </div>
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-user" style="margin-right:5px;"></i>Prénom</div>
                        <div class="pf-value"><?= htmlspecialchars($medecin['prenom']) ?></div>
                    </div>
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-envelope" style="margin-right:5px;"></i>Email</div>
                        <div class="pf-value"><?= htmlspecialchars($medecin['email']) ?></div>
                    </div>
                    <div class="profil-field">
                        <div class="pf-label"><i class="fa-solid fa-stethoscope" style="margin-right:5px;"></i>Spécialité</div>
                        <div class="pf-value"><?= htmlspecialchars($medecin['specialite']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ MODAL MODIFIER PATIENT ══ -->
        <div id="modalModifier" class="modal-edit-overlay" style="display:none;">
            <div class="modal-edit-content">
                <div class="modal-edit-header">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div class="icon-circle"><i class="fa-solid fa-user-gear"></i></div>
                        <div>
                            <h3 style="margin:0;font-size:1.1rem;">Modifier les informations</h3>
                            <span style="font-size:0.75rem;color:#888;" id="date_affichage">Patient enregistré</span>
                        </div>
                    </div>
                    <button class="btn-close-modal" onclick="fermerModale()">&times;</button>
                </div>
                <form action="update_patient_process.php" method="POST" class="modal-edit-body">
                    <input type="hidden" name="id_patient" id="mod_id">
                    <div class="modal-row">
                        <div class="modal-input-group">
                            <label><i class="fa-solid fa-user"></i> NOM</label>
                            <input type="text" name="nom" id="mod_nom" required>
                        </div>
                        <div class="modal-input-group">
                            <label><i class="fa-solid fa-user"></i> PRÉNOM</label>
                            <input type="text" name="prenom" id="mod_prenom" required>
                        </div>
                    </div>
                    <div class="modal-input-group">
                        <label><i class="fa-solid fa-id-card"></i> CIN</label>
                        <input type="text" name="cin" id="mod_cin" required>
                    </div>
                    <div class="modal-input-group">
                        <label><i class="fa-solid fa-envelope"></i> EMAIL</label>
                        <input type="email" name="email" id="mod_email" required>
                    </div>
                    <div class="modal-input-group">
                        <label><i class="fa-solid fa-location-dot"></i> ADRESSE</label>
                        <input type="text" name="adresse" id="mod_adresse">
                    </div>
                    <div class="modal-edit-footer">
                        <button type="button" class="btn-annuler" onclick="fermerModale()">Annuler</button>
                        <button type="submit" class="btn-enregistrer">
                            <i class="fa-solid fa-floppy-disk"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <script src="js/dashboard_medecin.js"></script>

</body>

</html>