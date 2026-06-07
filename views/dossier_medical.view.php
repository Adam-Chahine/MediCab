<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier Médical | Dr. Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
     <link rel="stylesheet" href="css/dossier_medical.css">
</head>

<body>
    <div class="layout">

        <!-- ── Header ── -->
        <div class="page-header">
            <div>
                <h1>
                    <i class="fa-solid fa-folder-open" style="color:var(--secondary);margin-right:10px;"></i>
                    Dossier Médical
                </h1>
                <p><?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></p>
            </div>
            <a href="dashboard_medecin.php?section=patients" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- ── Bannière patient ── -->
        <div class="patient-banner">
            <div class="patient-avatar">
                <?= strtoupper(substr($patient['prenom'], 0, 1) . substr($patient['nom'], 0, 1)) ?>
            </div>
            <div style="flex:1;">
                <h3><?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></h3>
                <p>
                    <i class="fa-solid fa-id-card" style="margin-right:5px;"></i>
                    <?= htmlspecialchars($patient['cin']) ?>
                    &nbsp;·&nbsp;
                    <i class="fa-solid fa-envelope" style="margin-right:5px;"></i>
                    <?= htmlspecialchars($patient['email']) ?>
                </p>
            </div>
            <?php if ($dossier): ?>
                <span class="info-badge green">
                    <i class="fa-solid fa-circle-check"></i> Dossier existant
                </span>
            <?php else: ?>
                <span class="info-badge orange">
                    <i class="fa-solid fa-circle-exclamation"></i> Nouveau dossier
                </span>
            <?php endif; ?>
        </div>

        <!-- ── Alerts ── -->
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <?= htmlspecialchars($success_msg) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <!-- ════════ COLONNE PRINCIPALE ════════ -->
        <div class="layout-main">

            <!-- ══ FORMULAIRE DOSSIER ══ -->
            <form method="POST">
                <input type="hidden" name="action" value="sauvegarder_dossier">

                <!-- ── 1. Informations de base ── -->
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-user-injured"></i> Informations de base
                    </div>
                    <?php if ($dossier && !empty($dossier['updated_at'])): ?>
                        <div class="updated-at">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Dernière mise à jour : <?= date('d/m/Y à H:i', strtotime($dossier['updated_at'])) ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-grid-3">
                        <div class="form-group">
                            <label>Groupe sanguin</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-droplet fi"></i>
                                <select name="groupe_sanguin">
                                    <option value="">— Inconnu —</option>
                                    <?php
                                    $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                    foreach ($groupes as $g):
                                        $sel = ($dossier && $dossier['groupe_sanguin'] === $g) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $g ?>" <?= $sel ?>><?= $g ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Taille</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-ruler-vertical fi"></i>
                                <input type="number" name="taille" step="0.1"
                                    value="<?= $dossier['taille'] ?? '' ?>"
                                    placeholder="175">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Poids</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-weight-scale fi"></i>
                                <input type="number" name="poids" step="0.1"
                                    value="<?= $dossier['poids'] ?? '' ?>"
                                    placeholder="70">
                                <span class="unit">kg</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── 2. Habitudes de vie ── -->
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-heart"></i> Habitudes de vie
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label><i class="fa-solid fa-smoking" style="margin-right:5px;color:#aaa;"></i>Tabac</label>
                            <div class="radio-group">
                                <?php
                                $tabacVal = $dossier['tabac'] ?? 'non';
                                $tabacOptions = ['non' => 'Non fumeur', 'oui' => 'Fumeur', 'ancien' => 'Ancien fumeur'];
                                foreach ($tabacOptions as $val => $label):
                                ?>
                                    <label class="radio-item">
                                        <input type="radio" name="tabac" value="<?= $val ?>"
                                            <?= $tabacVal === $val ? 'checked' : '' ?>>
                                        <?= $label ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fa-solid fa-wine-glass" style="margin-right:5px;color:#aaa;"></i>Alcool</label>
                            <div class="radio-group">
                                <?php
                                $alcoolVal = $dossier['alcool'] ?? 'non';
                                $alcoolOptions = ['non' => 'Non', 'oui' => 'Oui', 'occasionnel' => 'Occasionnel'];
                                foreach ($alcoolOptions as $val => $label):
                                ?>
                                    <label class="radio-item">
                                        <input type="radio" name="alcool" value="<?= $val ?>"
                                            <?= $alcoolVal === $val ? 'checked' : '' ?>>
                                        <?= $label ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── 3. Antécédents médicaux ── -->
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-stethoscope"></i> Antécédents médicaux
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label><i class="fa-solid fa-triangle-exclamation" style="margin-right:5px;color:var(--danger);"></i>Allergies</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-triangle-exclamation fi"></i>
                                <textarea name="allergies"
                                    placeholder="Ex : Pénicilline, Aspirine..."><?= htmlspecialchars($dossier['allergies'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fa-solid fa-heart-pulse" style="margin-right:5px;color:var(--danger);"></i>Maladies chroniques</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-heart-pulse fi"></i>
                                <textarea name="maladies_chroniques"
                                    placeholder="Ex : Diabète type 2, Hypertension..."><?= htmlspecialchars($dossier['maladies_chroniques'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fa-solid fa-pills" style="margin-right:5px;color:var(--secondary);"></i>Traitements permanents</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-pills fi"></i>
                                <textarea name="traitements_permanents"
                                    placeholder="Ex : Metformine 500mg..."><?= htmlspecialchars($dossier['traitements_permanents'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fa-solid fa-people-roof" style="margin-right:5px;color:var(--warning);"></i>Antécédents familiaux</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-people-roof fi"></i>
                                <textarea name="antecedents_familiaux"
                                    placeholder="Ex : Père diabétique..."><?= htmlspecialchars($dossier['antecedents_familiaux'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="form-group full">
                            <label><i class="fa-solid fa-wheelchair" style="margin-right:5px;color:#aaa;"></i>Handicap / Mobilité</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-wheelchair fi"></i>
                                <input type="text" name="handicap"
                                    value="<?= htmlspecialchars($dossier['handicap'] ?? '') ?>"
                                    placeholder="Ex : Aucun, Fauteuil roulant...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Submit ── -->
                <div style="display:flex;justify-content:flex-end;margin-bottom:22px;">
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Sauvegarder le dossier
                    </button>
                </div>
            </form>

            <!-- ══ DOCUMENTS ══ -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-paperclip"></i> Documents du dossier
                    <span style="margin-left:auto;font-size:0.75rem;color:#aaa;font-weight:400;">
                        Gérés par le médecin
                    </span>
                </div>

                <!-- Liste des documents -->
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
                                <?php if (!empty($doc['description'])): ?>
                                    <div class="doc-desc"><?= htmlspecialchars($doc['description']) ?></div>
                                <?php endif; ?>
                                <div class="doc-date">
                                    <i class="fa-solid fa-calendar" style="margin-right:4px;"></i>
                                    <?= date('d/m/Y', strtotime($doc['created_at'])) ?>
                                </div>
                            </div>
                            <a href="<?= htmlspecialchars($doc['chemin']) ?>"
                                download="<?= htmlspecialchars($doc['nom_fichier']) ?>" class="btn-dl" title="Télécharger">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <form method="POST" style="display:inline;"
                                onsubmit="return confirm('Supprimer ce document ?')">
                                <input type="hidden" name="action" value="supprimer_document">
                                <input type="hidden" name="doc_id" value="<?= $doc['id'] ?>">
                                <button type="submit" class="btn-del" title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:24px;color:#ccc;font-size:0.85rem;">
                        <i class="fa-regular fa-folder-open"
                            style="font-size:1.8rem;display:block;margin-bottom:8px;"></i>
                        Aucun document dans le dossier.
                    </div>
                <?php endif; ?>

                <!-- Upload document -->
                <div style="border-top:2px solid var(--light);padding-top:20px;margin-top:16px;">
                    <div style="font-size:0.88rem;font-weight:700;color:var(--primary);margin-bottom:14px;">
                        <i class="fa-solid fa-cloud-arrow-up" style="color:var(--secondary);margin-right:8px;"></i>
                        Ajouter un document au dossier
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload_document">
                        <div class="upload-zone" style="margin-bottom:14px;">
                            <input type="file" name="document" id="docInput"
                                accept=".pdf,.jpg,.jpeg,.png"
                                onchange="showDocPreview(this)">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <p><span>Cliquez pour uploader</span> ou glissez-déposez</p>
                            <p style="margin-top:4px;font-size:0.75rem;">PDF, JPG, PNG — max 5 Mo</p>
                            <p id="docPreview"
                                style="color:var(--accent);font-weight:600;margin-top:6px;"></p>
                        </div>
                        <input type="text" name="description"
                            placeholder="Description (ex: Ancien dossier, Radio, Analyse...)"
                            style="width:100%;padding:11px 14px;border:2px solid #e8ecf0;
                           border-radius:12px;font-size:0.87rem;font-family:'Poppins',sans-serif;
                           color:var(--primary);background:#fafbfc;outline:none;
                           margin-bottom:14px;transition:.3s;display:block;"
                            onfocus="this.style.borderColor='var(--secondary)';"
                            onblur="this.style.borderColor='#e8ecf0';">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Uploader
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- ════════ COLONNE ASIDE ════════ -->
        <div class="layout-aside">

            <!-- ── Résumé dossier ── -->
            <?php if ($dossier): ?>
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-clipboard-list"></i> Résumé
                    </div>
                    <div style="display:flex;flex-direction:column;gap:12px;">

                        <?php if (!empty($dossier['groupe_sanguin'])): ?>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <i class="fa-solid fa-droplet" style="color:var(--danger);width:16px;"></i>
                                <span style="font-size:0.82rem;color:#555;">Groupe sanguin :</span>
                                <span class="info-badge red"><?= $dossier['groupe_sanguin'] ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($dossier['taille']) && !empty($dossier['poids'])): ?>
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                <i class="fa-solid fa-ruler-vertical" style="color:var(--secondary);width:16px;"></i>
                                <span style="font-size:0.82rem;color:#555;"><?= $dossier['taille'] ?>cm / <?= $dossier['poids'] ?>kg</span>
                                <?php
                                if ($dossier['taille'] > 0 && $dossier['poids'] > 0) {
                                    $imc      = $dossier['poids'] / pow($dossier['taille'] / 100, 2);
                                    $imcLabel = $imc < 18.5 ? 'Sous-poids' : ($imc < 25 ? 'Normal' : ($imc < 30 ? 'Surpoids' : 'Obésité'));
                                    $imcColor = $imc < 18.5 ? 'blue' : ($imc < 25 ? 'green' : ($imc < 30 ? 'orange' : 'red'));
                                    echo "<span class='info-badge $imcColor'>IMC : " . number_format($imc, 1) . "</span>";
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($dossier['allergies'])): ?>
                            <div>
                                <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:4px;">
                                    <i class="fa-solid fa-triangle-exclamation" style="color:var(--danger);margin-right:4px;"></i>Allergies
                                </div>
                                <div style="font-size:0.82rem;color:#555;"><?= nl2br(htmlspecialchars($dossier['allergies'])) ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($dossier['maladies_chroniques'])): ?>
                            <div>
                                <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:4px;">
                                    <i class="fa-solid fa-heart-pulse" style="color:var(--danger);margin-right:4px;"></i>Maladies chroniques
                                </div>
                                <div style="font-size:0.82rem;color:#555;"><?= nl2br(htmlspecialchars($dossier['maladies_chroniques'])) ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($dossier['traitements_permanents'])): ?>
                            <div>
                                <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:#aaa;margin-bottom:4px;">
                                    <i class="fa-solid fa-pills" style="color:var(--secondary);margin-right:4px;"></i>Traitements permanents
                                </div>
                                <div style="font-size:0.82rem;color:#555;"><?= nl2br(htmlspecialchars($dossier['traitements_permanents'])) ?></div>
                            </div>
                        <?php endif; ?>

                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:4px;">
                            <span class="info-badge <?= $dossier['tabac'] === 'non' ? 'green' : 'orange' ?>">
                                <i class="fa-solid fa-smoking"></i>
                                <?= $dossier['tabac'] === 'non' ? 'Non fumeur' : ($dossier['tabac'] === 'ancien' ? 'Ancien fumeur' : 'Fumeur') ?>
                            </span>
                            <span class="info-badge <?= $dossier['alcool'] === 'non' ? 'green' : 'orange' ?>">
                                <i class="fa-solid fa-wine-glass"></i>
                                <?= $dossier['alcool'] === 'non' ? 'Sans alcool' : ($dossier['alcool'] === 'occasionnel' ? 'Occasionnel' : 'Alcool') ?>
                            </span>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

            <!-- ── Consultations récentes ── -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-clock-rotate-left"></i> Consultations récentes
                </div>
                <?php if (!empty($consultations)): ?>
                    <?php foreach ($consultations as $c): ?>
                        <a href="detail_consultation_medecin.php?id=<?= $c['id'] ?>" class="hist-item">
                            <div class="hist-date">
                                <i class="fa-solid fa-calendar" style="margin-right:5px;"></i>
                                <?= date('d/m/Y', strtotime($c['date_consult'])) ?>
                            </div>
                            <div class="hist-diag"><?= htmlspecialchars($c['diagnostic']) ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:20px;color:#ccc;font-size:0.82rem;">
                        <i class="fa-solid fa-notes-medical"
                            style="font-size:1.5rem;display:block;margin-bottom:8px;"></i>
                        Aucune consultation.
                    </div>
                <?php endif; ?>
            </div>

            <!-- ── Actions rapides ── -->
            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-bolt"></i> Actions rapides
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="nouvelle_consultation.php?user_id=<?= $user_id ?>"
                        style="display:flex;align-items:center;gap:10px;padding:12px 16px;
                   background:rgba(52,152,219,0.08);border:2px solid rgba(52,152,219,0.15);
                   border-radius:12px;color:var(--secondary);font-size:0.85rem;
                   font-weight:700;text-decoration:none;transition:.25s;"
                        onmouseover="this.style.background='var(--secondary)';this.style.color='white';"
                        onmouseout="this.style.background='rgba(52,152,219,0.08)';this.style.color='var(--secondary)';">
                        <i class="fa-solid fa-notes-medical"></i> Nouvelle consultation
                    </a>
                    <a href="dashboard_medecin.php?section=patients"
                        style="display:flex;align-items:center;gap:10px;padding:12px 16px;
                   background:rgba(46,204,113,0.08);border:2px solid rgba(46,204,113,0.15);
                   border-radius:12px;color:var(--accent);font-size:0.85rem;
                   font-weight:700;text-decoration:none;transition:.25s;"
                        onmouseover="this.style.background='var(--accent)';this.style.color='white';"
                        onmouseout="this.style.background='rgba(46,204,113,0.08)';this.style.color='var(--accent)';">
                        <i class="fa-solid fa-users"></i> Retour aux patients
                    </a>
                </div>
            </div>

        </div>

    </div>

    <script src="js/dossier_medical.js"></script>

</body>

</html>