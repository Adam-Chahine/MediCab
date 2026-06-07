<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Consultation | Dr. Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/detail_consultation_medecin.css"> 
</head>

<body>

    <div class="layout">

        <!-- ── Header ── -->
        <div class="page-header">
            <div>
                <h1>
                    <i class="fa-solid fa-file-medical" style="color:var(--secondary);margin-right:10px;"></i>
                    Détail de la Consultation
                </h1>
                <p>
                    <?= htmlspecialchars($consultation['prenom'] . ' ' . $consultation['nom']) ?>
                    — <?= date('d/m/Y', strtotime($consultation['date_consult'])) ?>
                </p>
            </div>
            <div class="header-actions">
                <a href="dashboard_medecin.php?section=consultations" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Retour
                </a>
                <a href="modifier_consultation.php?id=<?= $consultation_id ?>" class="btn-edit">
                    <i class="fa-solid fa-pen"></i> Modifier
                </a>
                <button class="btn-print" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Imprimer
                </button>
            </div>
        </div>

        <!-- ── Bannière patient ── -->
        <div class="patient-banner">
            <div class="patient-avatar">
                <?= strtoupper(substr($consultation['prenom'], 0, 1) . substr($consultation['nom'], 0, 1)) ?>
            </div>
            <div>
                <h3><?= htmlspecialchars($consultation['prenom'] . ' ' . $consultation['nom']) ?></h3>
                <p>
                    <i class="fa-solid fa-id-card" style="margin-right:5px;"></i>
                    <?= htmlspecialchars($consultation['cin']) ?>
                    &nbsp;·&nbsp;
                    <i class="fa-solid fa-envelope" style="margin-right:5px;"></i>
                    <?= htmlspecialchars($consultation['email']) ?>
                    &nbsp;·&nbsp;
                    <i class="fa-solid fa-calendar" style="margin-right:5px;"></i>
                    Consultation du <?= date('d/m/Y', strtotime($consultation['date_consult'])) ?>
                </p>
            </div>
        </div>

        <!-- ════════ COLONNE PRINCIPALE ════════ -->
        <div class="layout-main">

            <!-- ── 1. Constantes vitales ── -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-heart-pulse"></i> Constantes vitales
                </div>
                <div class="vitals-grid">
                    <div class="vital-item">
                        <span class="vital-icon" style="color:#e74c3c;"><i class="fa-solid fa-gauge"></i></span>
                        <?php if (!empty($consultation['tension'])): ?>
                            <div class="vital-value"><?= htmlspecialchars($consultation['tension']) ?></div>
                            <div class="vital-unit">mmHg</div>
                        <?php else: ?><div class="vital-empty">—</div><?php endif; ?>
                        <div class="vital-label">Tension</div>
                    </div>
                    <div class="vital-item">
                        <span class="vital-icon" style="color:var(--warning);"><i class="fa-solid fa-thermometer"></i></span>
                        <?php if (!empty($consultation['temperature'])): ?>
                            <div class="vital-value"><?= $consultation['temperature'] ?>°</div>
                            <div class="vital-unit">Celsius</div>
                        <?php else: ?><div class="vital-empty">—</div><?php endif; ?>
                        <div class="vital-label">Température</div>
                    </div>
                    <div class="vital-item">
                        <span class="vital-icon" style="color:var(--secondary);"><i class="fa-solid fa-weight-scale"></i></span>
                        <?php if (!empty($consultation['poids'])): ?>
                            <div class="vital-value"><?= $consultation['poids'] ?></div>
                            <div class="vital-unit">kg</div>
                        <?php else: ?><div class="vital-empty">—</div><?php endif; ?>
                        <div class="vital-label">Poids</div>
                    </div>
                    <div class="vital-item">
                        <span class="vital-icon" style="color:#e74c3c;"><i class="fa-solid fa-heart"></i></span>
                        <?php if (!empty($consultation['pouls'])): ?>
                            <div class="vital-value"><?= $consultation['pouls'] ?></div>
                            <div class="vital-unit">bpm</div>
                        <?php else: ?><div class="vital-empty">—</div><?php endif; ?>
                        <div class="vital-label">Pouls</div>
                    </div>
                </div>
            </div>

            <!-- ── 2. Diagnostic + Notes ── -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-stethoscope"></i> Diagnostic & Notes
                </div>

                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;
                letter-spacing:0.8px;color:#aaa;margin-bottom:8px;">
                    <i class="fa-solid fa-stethoscope" style="margin-right:5px;"></i>Diagnostic
                </div>
                <div class="diag-box">
                    <?= nl2br(htmlspecialchars($consultation['diagnostic'])) ?>
                </div>

                <?php if (!empty($consultation['notes'])): ?>
                    <div class="notes-box">
                        <div class="notes-label">
                            <i class="fa-solid fa-lock"></i>
                            Notes privées — visibles uniquement par le médecin
                            <span class="private-badge">
                                <i class="fa-solid fa-eye-slash"></i> Privé
                            </span>
                        </div>
                        <?= nl2br(htmlspecialchars($consultation['notes'])) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($consultation['prochain_rdv'])): ?>
                    <div class="next-rdv-box">
                        <i class="fa-solid fa-calendar-plus"></i>
                        Prochain RDV suggéré :
                        <strong><?= htmlspecialchars($consultation['prochain_rdv']) ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ── 3. Ordonnance ── -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-prescription"></i> Ordonnance
                </div>
                <?php if (!empty($ordonnances)): ?>
                    <div style="overflow-x:auto;">
                        <table class="ordo-table">
                            <thead>
                                <tr>
                                    <th><i class="fa-solid fa-pills" style="margin-right:5px;"></i>Médicament</th>
                                    <th>Dosage</th>
                                    <th>Posologie</th>
                                    <th>Durée</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ordonnances as $ordo): ?>
                                    <tr>
                                        <td>
                                            <span class="med-badge">
                                                <i class="fa-solid fa-capsules"></i>
                                                <?= htmlspecialchars($ordo['medicament']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($ordo['dosage'] ?: '—') ?></td>
                                        <td><?= htmlspecialchars($ordo['posologie'] ?: '—') ?></td>
                                        <td><?= htmlspecialchars($ordo['duree'] ?: '—') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="ordo-empty">
                        <i class="fa-solid fa-prescription" style="font-size:1.8rem;display:block;margin-bottom:8px;"></i>
                        Aucun médicament prescrit.
                    </div>
                <?php endif; ?>
            </div>

            <!-- ── 4. Documents ── -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-paperclip"></i> Documents joints
                </div>
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $doc): ?>
                        <?php
                        $ext = strtolower(pathinfo($doc['nom_fichier'], PATHINFO_EXTENSION));
                        $isPdf = ($ext === 'pdf');
                        $iconClass = $isPdf ? 'fa-file-pdf pdf' : 'fa-file-image image';
                        ?>
                        <div class="doc-item">
                            <i class="fa-solid <?= $iconClass ?> doc-icon"></i>
                            <div style="flex:1;min-width:0;">
                                <div class="doc-name"><?= htmlspecialchars($doc['nom_fichier']) ?></div>
                                <div class="doc-date">
                                    <i class="fa-solid fa-calendar" style="margin-right:4px;"></i>
                                    <?= date('d/m/Y', strtotime($doc['created_at'])) ?>
                                </div>
                            </div>
                            <a href="<?= htmlspecialchars($doc['chemin']) ?>" " download=" <?= htmlspecialchars($doc['nom_fichier']) ?>"class="btn-dl" title="Télécharger">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="doc-empty">
                        <i class="fa-regular fa-folder-open" style="font-size:1.8rem;display:block;margin-bottom:8px;"></i>
                        Aucun document joint.
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- ════════ COLONNE ASIDE ════════ -->
        <div class="layout-aside">

            <!-- ── Infos patient ── -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-user"></i> Informations patient
                </div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div style="background:var(--light);border-radius:10px;padding:12px 14px;">
                        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:4px;">Nom complet</div>
                        <div style="font-size:0.88rem;font-weight:600;">
                            <?= htmlspecialchars($consultation['prenom'] . ' ' . $consultation['nom']) ?>
                        </div>
                    </div>
                    <div style="background:var(--light);border-radius:10px;padding:12px 14px;">
                        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:4px;">CIN</div>
                        <div style="font-size:0.88rem;font-weight:600;">
                            <?= htmlspecialchars($consultation['cin']) ?>
                        </div>
                    </div>
                    <div style="background:var(--light);border-radius:10px;padding:12px 14px;">
                        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:4px;">Email</div>
                        <div style="font-size:0.85rem;font-weight:600;word-break:break-all;">
                            <?= htmlspecialchars($consultation['email']) ?>
                        </div>
                    </div>
                    <a href="nouvelle_consultation.php?user_id=<?= $consultation['user_id'] ?>"
                        style="display:flex;align-items:center;justify-content:center;gap:8px;
                   padding:11px;background:rgba(52,152,219,0.1);border:2px solid rgba(52,152,219,0.2);
                   border-radius:12px;color:var(--secondary);font-size:0.82rem;font-weight:700;
                   text-decoration:none;transition:.25s;"
                        onmouseover="this.style.background='var(--secondary)';this.style.color='white';"
                        onmouseout="this.style.background='rgba(52,152,219,0.1)';this.style.color='var(--secondary)';">
                        <i class="fa-solid fa-notes-medical"></i> Nouvelle consultation
                    </a>
                </div>
            </div>

            <!-- ── Historique patient ── -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-clock-rotate-left"></i> Consultations précédentes
                </div>
                <?php if (!empty($historique)): ?>
                    <?php foreach ($historique as $h): ?>
                        <a href="detail_consultation_medecin.php?id=<?= $h['id'] ?>" class="hist-item">
                            <div class="hist-date">
                                <i class="fa-solid fa-calendar" style="margin-right:5px;"></i>
                                <?= date('d/m/Y', strtotime($h['date_consult'])) ?>
                            </div>
                            <div class="hist-diag"><?= htmlspecialchars($h['diagnostic']) ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="hist-empty">
                        <i class="fa-solid fa-clock-rotate-left" style="font-size:1.5rem;display:block;margin-bottom:8px;"></i>
                        Aucune consultation précédente.
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>
</body>

</html>