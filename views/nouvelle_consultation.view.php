<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Consultation | Dr. Benali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
     <link rel="stylesheet" href="css/nouvelle_consultation.css">
</head>

<body>
    <div class="container">

        <div class="page-header">
            <div>
                <h1><i class="fa-solid fa-notes-medical" style="color:var(--secondary);margin-right:10px;"></i>Nouvelle Consultation</h1>
                <p>Remplissez le dossier de consultation du patient</p>
            </div>
            <a href="dashboard_medecin.php" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Retour au dashboard
            </a>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i><?= htmlspecialchars($error_msg) ?></div>
        <?php endif; ?>

        <?php if ($patient): ?>
            <div class="patient-banner">
                <div class="patient-avatar">
                    <?= strtoupper(substr($patient['prenom'], 0, 1) . substr($patient['nom'], 0, 1)) ?>
                </div>
                <div>
                    <h3><?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></h3>
                    <p>
                        <i class="fa-solid fa-id-card" style="margin-right:5px;"></i><?= htmlspecialchars($patient['cin']) ?>
                        &nbsp;·&nbsp;
                        <i class="fa-solid fa-envelope" style="margin-right:5px;"></i><?= htmlspecialchars($patient['email']) ?>
                        <?php if ($rdv): ?>
                            &nbsp;·&nbsp;
                            <i class="fa-solid fa-calendar" style="margin-right:5px;"></i>
                            RDV du <?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?> à <?= substr($rdv['heure_rdv'], 0, 5) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="consultationForm">
            <input type="hidden" name="user_id" value="<?= $patient['patient_id'] ?>">
            <input type="hidden" name="rdv_id" value="<?= $rdv['id'] ?? '' ?>">

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-calendar-day"></i> Informations générales
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Date de consultation <span style="color:var(--danger)">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-calendar fi"></i>
                            <input type="date" name="date_consult" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Prochain RDV suggéré</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-calendar-plus fi"></i>
                            <input type="text" name="prochain_rdv" placeholder="Ex : Dans 3 semaines, 15/04/2026...">
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
                        <label>Tension artérielle</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-gauge fi"></i>
                            <input type="text" name="tension" placeholder="Ex : 12/8">
                            <span class="vital-unit">mmHg</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Température</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-thermometer fi"></i>
                            <input type="number" name="temperature" step="0.1" placeholder="37.0">
                            <span class="vital-unit">°C</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Poids</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-weight-scale fi"></i>
                            <input type="number" name="poids" step="0.1" placeholder="70.0">
                            <span class="vital-unit">kg</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pouls</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-heart fi"></i>
                            <input type="number" name="pouls" placeholder="72">
                            <span class="vital-unit">bpm</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-stethoscope"></i> Diagnostic & Observations
                </div>
                <div class="form-grid-2">
                    <div class="form-group full">
                        <label>Diagnostic <span style="color:var(--danger)">*</span></label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-stethoscope fi" style="top:18px;transform:none;"></i>
                            <textarea name="diagnostic" placeholder="Diagnostic principal..." required></textarea>
                        </div>
                    </div>
                    <div class="form-group full">
                        <label>Notes & Observations</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-pen fi" style="top:18px;transform:none;"></i>
                            <textarea name="notes" placeholder="Observations, conseils au patient, recommandations..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-prescription"></i> Ordonnance
                </div>

                <div style="display:grid;grid-template-columns:2fr 1fr 2fr 1fr 36px;gap:10px;padding:0 16px;margin-bottom:8px;">
                    <span style="font-size:0.72rem;font-weight:700;color:#aaa;text-transform:uppercase;">Médicament</span>
                    <span style="font-size:0.72rem;font-weight:700;color:#aaa;text-transform:uppercase;">Dosage</span>
                    <span style="font-size:0.72rem;font-weight:700;color:#aaa;text-transform:uppercase;">Posologie</span>
                    <span style="font-size:0.72rem;font-weight:700;color:#aaa;text-transform:uppercase;">Durée</span>
                    <span></span>
                </div>

                <div id="medicamentsList">
                    <div class="med-row">
                        <div class="form-group"><input type="text" name="medicament[]" placeholder="Ex : Amoxicilline"></div>
                        <div class="form-group"><input type="text" name="dosage[]" placeholder="Ex : 500mg"></div>
                        <div class="form-group"><input type="text" name="posologie[]" placeholder="Ex : 1 cp matin et soir"></div>
                        <div class="form-group"><input type="text" name="duree[]" placeholder="Ex : 7 jours"></div>
                        <button type="button" class="btn-remove-med" onclick="removeMed(this)"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>

                <button type="button" class="btn-add-med" onclick="addMed()">
                    <i class="fa-solid fa-plus"></i> Ajouter un médicament
                </button>
            </div>

            <div class="card">
                <div class="card-title">
                    <i class="fa-solid fa-paperclip"></i> Documents à joindre
                </div>

                <div class="upload-zone" onclick="triggerFileInput()" style="cursor:pointer;">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p><span>Cliquez pour ajouter des fichiers</span></p>
                    <p style="margin-top:4px;font-size:0.75rem;">Les fichiers sélectionnés s'ajoutent à la liste ci-dessous</p>
                </div>

                <div id="inputsContainer" style="display:none;">
                    <input type="file" name="documents[]" class="doc-input" accept=".pdf,.jpg,.jpeg,.png" multiple onchange="handleFileSelection(this)">
                </div>

                <div id="fileList" style="margin-top:15px; display:flex; flex-wrap:wrap; gap:10px; padding:0 5px;"></div>
            </div>

            <div class="form-actions">
                <a href="dashboard_medecin.php" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-floppy-disk"></i> Enregistrer la consultation
                </button>
            </div>
        </form>
    </div>

    <style>
        /* Style pour les badges de fichiers accumulés */
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .file-tag i {
            color: #3498db;
        }

        .btn-remove-file {
            cursor: pointer;
            color: #e74c3c;
            border: none;
            background: none;
            padding: 0 2px;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .btn-remove-file:hover {
            color: #c0392b;
        }
    </style>

    <script src="js/nouvelle_consultation.js"></script>
</body>

</html>