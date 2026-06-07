<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Consultation | Dr. Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
     <link rel="stylesheet" href="css/modifier_consultation.css">
</head>

<body>
    <div class="container">

        <div class="page-header">
            <div>
                <h1>
                    <i class="fa-solid fa-pen" style="color:var(--warning);margin-right:10px;"></i>
                    Modifier la Consultation
                </h1>
                <p>Consultation du <?= date('d/m/Y', strtotime($consultation['date_consult'])) ?></p>
            </div>
            <a href="detail_consultation_medecin.php?id=<?= $consultation_id ?>" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Retour
            </a>
        </div>

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
                </p>
            </div>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="editConsultForm">

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-calendar-day"></i> Informations générales
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Date de consultation</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-calendar fi"></i>
                            <input type="date" name="date_consult" value="<?= $consultation['date_consult'] ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Prochain RDV suggéré</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-calendar-plus fi"></i>
                            <input type="text" name="prochain_rdv" value="<?= htmlspecialchars($consultation['prochain_rdv'] ?? '') ?>" placeholder="Ex : Dans 3 semaines...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-heart-pulse"></i> Constantes vitales
                </div>
                <div class="form-grid-4">
                    <div class="form-group">
                        <label>Tension</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-gauge fi"></i>
                            <input type="text" name="tension" value="<?= htmlspecialchars($consultation['tension'] ?? '') ?>" placeholder="Ex : 12/8">
                            <span class="vital-unit">mmHg</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Température</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-thermometer fi"></i>
                            <input type="number" name="temperature" step="0.1" value="<?= $consultation['temperature'] ?? '' ?>" placeholder="37.0">
                            <span class="vital-unit">°C</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Poids</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-weight-scale fi"></i>
                            <input type="number" name="poids" step="0.1" value="<?= $consultation['poids'] ?? '' ?>" placeholder="70.0">
                            <span class="vital-unit">kg</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pouls</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-heart fi"></i>
                            <input type="number" name="pouls" value="<?= $consultation['pouls'] ?? '' ?>" placeholder="72">
                            <span class="vital-unit">bpm</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-stethoscope"></i> Diagnostic & Notes
                </div>
                <div class="form-grid-2">
                    <div class="form-group full">
                        <label>Diagnostic <span style="color:var(--danger)">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-stethoscope fi" style="top:18px;transform:none;"></i>
                            <textarea name="diagnostic" required><?= htmlspecialchars($consultation['diagnostic']) ?></textarea>
                        </div>
                    </div>
                    <div class="form-group full">
                        <label>Notes privées <span style="font-size:0.72rem;color:var(--warning);font-weight:400;margin-left:8px;"><i class="fa-solid fa-lock"></i> Non visible par le patient</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-pen fi" style="top:18px;transform:none;"></i>
                            <textarea name="notes" placeholder="Observations privées..."><?= htmlspecialchars($consultation['notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-prescription"></i> Ordonnance
                </div>
                <div style="display:grid;grid-template-columns:2fr 1fr 2fr 1fr 36px; gap:10px;padding:0 16px;margin-bottom:8px;">
                    <span style="font-size:0.72rem;font-weight:700;color:#aaa;text-transform:uppercase;">Médicament</span>
                    <span style="font-size:0.72rem;font-weight:700;color:#aaa;text-transform:uppercase;">Dosage</span>
                    <span style="font-size:0.72rem;font-weight:700;color:#aaa;text-transform:uppercase;">Posologie</span>
                    <span style="font-size:0.72rem;font-weight:700;color:#aaa;text-transform:uppercase;">Durée</span>
                    <span></span>
                </div>
                <div id="medicamentsList">
                    <?php if (!empty($ordonnances)): foreach ($ordonnances as $ordo): ?>
                            <div class="med-row">
                                <div class="form-group"><input type="text" name="medicament[]" value="<?= htmlspecialchars($ordo['medicament']) ?>"></div>
                                <div class="form-group"><input type="text" name="dosage[]" value="<?= htmlspecialchars($ordo['dosage']) ?>"></div>
                                <div class="form-group"><input type="text" name="posologie[]" value="<?= htmlspecialchars($ordo['posologie']) ?>"></div>
                                <div class="form-group"><input type="text" name="duree[]" value="<?= htmlspecialchars($ordo['duree']) ?>"></div>
                                <button type="button" class="btn-remove-med" onclick="removeMed(this)"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <div class="med-row">
                            <div class="form-group"><input type="text" name="medicament[]" placeholder="Ex : Amoxicilline"></div>
                            <div class="form-group"><input type="text" name="dosage[]" placeholder="Ex : 500mg"></div>
                            <div class="form-group"><input type="text" name="posologie[]" placeholder="Ex : 1 cp matin et soir"></div>
                            <div class="form-group"><input type="text" name="duree[]" placeholder="Ex : 7 jours"></div>
                            <button type="button" class="btn-remove-med" onclick="removeMed(this)"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn-add-med" onclick="addMed()"><i class="fa-solid fa-plus"></i> Ajouter un médicament</button>
            </div>

            <?php if (!empty($documents)): ?>
                <div class="card">
                    <div class="card-title">
                        <i class="fa-solid fa-paperclip"></i> Documents déjà enregistrés
                    </div>
                    <p style="font-size:0.8rem;color:#888;margin-bottom:14px;">
                        <i class="fa-solid fa-triangle-exclamation" style="color:var(--warning);margin-right:5px;"></i>
                        Cochez les documents à supprimer définitivement.
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <?php foreach ($documents as $doc):
                            $ext = strtolower(pathinfo($doc['nom_fichier'], PATHINFO_EXTENSION));
                            $iconClass = ($ext === 'pdf') ? 'fa-file-pdf pdf' : 'fa-file-image image';
                        ?>
                            <div class="doc-existing" id="doc-<?= $doc['id'] ?>" style="display:flex; align-items:center; justify-content:space-between; padding:10px; background:#fcfcfc; border:1px solid #eee; border-radius:8px;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <i class="fa-solid <?= $iconClass ?>" style="font-size:1.2rem; width:25px; text-align:center;"></i>
                                    <span style="font-size:0.9rem; color:var(--primary);"><?= htmlspecialchars($doc['nom_fichier']) ?></span>
                                </div>
                                <label style="cursor:pointer; color:var(--danger); font-size:0.85rem; display:flex; align-items:center; gap:5px;">
                                    <input type="checkbox" name="supprimer_doc[]" value="<?= $doc['id'] ?>" onchange="markDoc(this, <?= $doc['id'] ?>)">
                                    <i class="fa-solid fa-trash"></i> Supprimer
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Ajouter de nouveaux documents
                </div>
                <div class="upload-zone" onclick="triggerFileInput()" style="cursor:pointer;">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p><span>Cliquez pour ajouter des fichiers</span></p>
                    <p style="margin-top:4px;font-size:0.75rem;">PDF, JPG, PNG — max 5 Mo</p>
                </div>
                <div id="inputsContainer" style="display:none;">
                    <input type="file" name="documents[]" class="doc-input" multiple accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileSelection(this)">
                </div>
                <div id="fileList" style="margin-top:15px; display:flex; flex-wrap:wrap; gap:10px;"></div>
            </div>

            <div class="form-actions">
                <a href="detail_consultation_medecin.php?id=<?= $consultation_id ?>" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-floppy-disk"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <style>
        /* Styles pour la gestion visuelle */
        .doc-existing.marked {
            background-color: #fff5f5 !important;
            border-color: #feb2b2 !important;
            opacity: 0.7;
        }

        .file-tag {
            background: #f8f9fa;
            color: #2c3e50;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.82rem;
            border: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-remove-file {
            cursor: pointer;
            color: #e74c3c;
            border: none;
            background: none;
            font-size: 1rem;
        }

        .pdf {
            color: #e74c3c;
        }

        .image {
            color: #3498db;
        }
    </style>

    <script src="js/modifier_consultation.js"></script>
</body>

</html>