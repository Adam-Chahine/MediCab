<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Consultation | Dr. Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/detail_consultation_patient.css">
</head>

<body>
    <div class="container">

        <!-- ── Header ── -->
        <div class="page-header">
            <div>
                <h1>
                    <i class="fa-solid fa-file-medical" style="color:var(--secondary);margin-right:10px;"></i>
                    Détail de la Consultation
                </h1>
                <p>Consultation du <?= date('d/m/Y', strtotime($consultation['date_consult'])) ?></p>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="dashboard.php" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Retour
                </a>
                <button class="btn-print" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Imprimer
                </button>
            </div>
        </div>

        <!-- ── Bannière patient ── -->
        <div class="patient-banner">
            <div class="patient-banner-left">
                <div class="patient-avatar">
                    <?= strtoupper(substr($consultation['prenom'], 0, 1) . substr($consultation['nom'], 0, 1)) ?>
                </div>
                <div>
                    <h3><?= htmlspecialchars($consultation['prenom'] . ' ' . $consultation['nom']) ?></h3>
                    <p>
                        <i class="fa-solid fa-id-card" style="margin-right:5px;"></i>
                        <?= htmlspecialchars($consultation['cin']) ?>
                        &nbsp;·&nbsp;
                        <i class="fa-solid fa-calendar" style="margin-right:5px;"></i>
                        Consultation du <?= date('d/m/Y', strtotime($consultation['date_consult'])) ?>
                        &nbsp;·&nbsp;
                        <i class="fa-solid fa-user-doctor" style="margin-right:5px;"></i>
                        Dr. Benali
                    </p>
                </div>
            </div>
        </div>

        <!-- ── 1. Constantes vitales ── -->
        <div class="card">
            <div class="card-title">
                <i class="fa-solid fa-heart-pulse"></i> Constantes vitales
            </div>
            <div class="vitals-grid">

                <!-- Tension -->
                <div class="vital-item">
                    <span class="vital-icon" style="color:#e74c3c;">
                        <i class="fa-solid fa-gauge"></i>
                    </span>
                    <?php if (!empty($consultation['tension'])): ?>
                        <div class="vital-value"><?= htmlspecialchars($consultation['tension']) ?></div>
                        <div class="vital-unit">mmHg</div>
                    <?php else: ?>
                        <div class="vital-empty">—</div>
                    <?php endif; ?>
                    <div class="vital-label">Tension</div>
                </div>

                <!-- Température -->
                <div class="vital-item">
                    <span class="vital-icon" style="color:var(--warning);">
                        <i class="fa-solid fa-thermometer"></i>
                    </span>
                    <?php if (!empty($consultation['temperature'])): ?>
                        <div class="vital-value"><?= $consultation['temperature'] ?>°</div>
                        <div class="vital-unit">Celsius</div>
                    <?php else: ?>
                        <div class="vital-empty">—</div>
                    <?php endif; ?>
                    <div class="vital-label">Température</div>
                </div>

                <!-- Poids -->
                <div class="vital-item">
                    <span class="vital-icon" style="color:var(--secondary);">
                        <i class="fa-solid fa-weight-scale"></i>
                    </span>
                    <?php if (!empty($consultation['poids'])): ?>
                        <div class="vital-value"><?= $consultation['poids'] ?></div>
                        <div class="vital-unit">kg</div>
                    <?php else: ?>
                        <div class="vital-empty">—</div>
                    <?php endif; ?>
                    <div class="vital-label">Poids</div>
                </div>

                <!-- Pouls -->
                <div class="vital-item">
                    <span class="vital-icon" style="color:#e74c3c;">
                        <i class="fa-solid fa-heart"></i>
                    </span>
                    <?php if (!empty($consultation['pouls'])): ?>
                        <div class="vital-value"><?= $consultation['pouls'] ?></div>
                        <div class="vital-unit">bpm</div>
                    <?php else: ?>
                        <div class="vital-empty">—</div>
                    <?php endif; ?>
                    <div class="vital-label">Pouls</div>
                </div>

            </div>
        </div>

        <!-- ── 2. Diagnostic ── -->
        <div class="card">
            <div class="card-title">
                <i class="fa-solid fa-stethoscope"></i> Diagnostic
            </div>
            <div class="diag-box">
                <?= nl2br(htmlspecialchars($consultation['diagnostic'])) ?>
            </div>

            <?php if (!empty($consultation['prochain_rdv'])): ?>
                <div class="next-rdv-box">
                    <i class="fa-solid fa-calendar-plus"></i>
                    Prochain rendez-vous suggéré :
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
                                <th><i class="fa-solid fa-pills" style="margin-right:6px;"></i>Médicament</th>
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
                    <i class="fa-solid fa-prescription"></i>
                    Aucun médicament prescrit pour cette consultation.
                </div>
            <?php endif; ?>
        </div>

        <!-- ── 4. Documents ── -->
        <div class="card">
            <div class="card-title">
                <i class="fa-solid fa-paperclip"></i> Documents joints
            </div>
            <?php if (!empty($documents)): ?>
                <div class="doc-grid">
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
                </div>
            <?php else: ?>
                <div class="doc-empty">
                    <i class="fa-regular fa-folder-open"></i>
                    Aucun document joint à cette consultation.
                </div>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>