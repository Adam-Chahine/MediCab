       // ── GESTION MÉDICAMENTS ──
        function addMed() {
            const list = document.getElementById('medicamentsList');
            const row = document.createElement('div');
            row.className = 'med-row';
            row.innerHTML = `
            <div class="form-group"><input type="text" name="medicament[]" placeholder="Ex : Amoxicilline"></div>
            <div class="form-group"><input type="text" name="dosage[]" placeholder="Ex : 500mg"></div>
            <div class="form-group"><input type="text" name="posologie[]" placeholder="Ex : 1 cp matin et soir"></div>
            <div class="form-group"><input type="text" name="duree[]" placeholder="Ex : 7 jours"></div>
            <button type="button" class="btn-remove-med" onclick="removeMed(this)"><i class="fa-solid fa-xmark"></i></button>
        `;
            list.appendChild(row);
            row.querySelector('input').focus();
        }

        function removeMed(btn) {
            const rows = document.querySelectorAll('.med-row');
            if (rows.length === 1) {
                btn.closest('.med-row').querySelectorAll('input').forEach(i => i.value = '');
                return;
            }
            btn.closest('.med-row').remove();
        }

        // ── GESTION FICHIERS (ACCUMULATION) ──
        let inputCounter = 0;

        function triggerFileInput() {
            // On cible toujours l'input qui n'a pas encore servi (le dernier du conteneur)
            const inputs = document.querySelectorAll('.doc-input');
            inputs[inputs.length - 1].click();
        }

        function handleFileSelection(input) {
            if (input.files.length > 0) {
                const list = document.getElementById('fileList');
                const currentInputId = "input_group_" + inputCounter;

                // On donne un ID unique à l'input qui vient d'être rempli
                input.setAttribute('id', currentInputId);

                // Affichage des fichiers de cet input
                Array.from(input.files).forEach((file, index) => {
                    const tag = document.createElement('div');
                    tag.className = 'file-tag';
                    // On marque le tag pour savoir de quel input il provient
                    tag.setAttribute('data-target-input', currentInputId);

                    const icon = file.name.toLowerCase().endsWith('.pdf') ? 'fa-file-pdf' : 'fa-file-image';
                    tag.innerHTML = `
                    <i class="fa-solid ${icon}"></i> 
                    <span>${file.name}</span>
                    <button type="button" class="btn-remove-file" onclick="removeFileGroup('${currentInputId}')" title="Supprimer ce lot">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                `;
                    list.appendChild(tag);
                });

                // On incrémente et on crée un NOUVEL input vide pour la prochaine fois
                inputCounter++;
                const container = document.getElementById('inputsContainer');
                const newInput = document.createElement('input');
                newInput.type = 'file';
                newInput.name = 'documents[]';
                newInput.className = 'doc-input';
                newInput.multiple = true;
                newInput.accept = '.pdf,.jpg,.jpeg,.png';
                newInput.onchange = function() {
                    handleFileSelection(this);
                };
                container.appendChild(newInput);
            }
        }

        // Fonction pour supprimer un lot de fichiers si erreur
        function removeFileGroup(inputId) {
            const inputToRemove = document.getElementById(inputId);
            if (inputToRemove) inputToRemove.remove();

            // Supprimer les tags visuels associés
            const tags = document.querySelectorAll(`.file-tag[data-target-input="${inputId}"]`);
            tags.forEach(t => t.remove());
        }