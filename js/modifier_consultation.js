       // ── MÉDICAMENTS ──
        function addMed() {
            const list = document.getElementById('medicamentsList');
            const row = document.createElement('div');
            row.className = 'med-row';
            row.innerHTML = `
            <div class="form-group"><input type="text" name="medicament[]"></div>
            <div class="form-group"><input type="text" name="dosage[]"></div>
            <div class="form-group"><input type="text" name="posologie[]"></div>
            <div class="form-group"><input type="text" name="duree[]"></div>
            <button type="button" class="btn-remove-med" onclick="removeMed(this)"><i class="fa-solid fa-xmark"></i></button>
        `;
            list.appendChild(row);
        }

        function removeMed(btn) {
            const rows = document.querySelectorAll('.med-row');
            if (rows.length === 1) {
                btn.closest('.med-row').querySelectorAll('input').forEach(i => i.value = '');
                return;
            }
            btn.closest('.med-row').remove();
        }

        // ── DOCUMENTS EXISTANTS (SUPPRESSION) ──
        function markDoc(checkbox, id) {
            const docEl = document.getElementById('doc-' + id);
            if (checkbox.checked) docEl.classList.add('marked');
            else docEl.classList.remove('marked');
        }

        // ── NOUVEAUX DOCUMENTS (ACCUMULATION) ──
        let inputCounter = 0;

        function triggerFileInput() {
            const inputs = document.querySelectorAll('.doc-input');
            inputs[inputs.length - 1].click();
        }

        function handleFileSelection(input) {
            if (input.files.length > 0) {
                const list = document.getElementById('fileList');
                const currentId = "input_edit_" + inputCounter;
                input.setAttribute('id', currentId);

                Array.from(input.files).forEach(file => {
                    const tag = document.createElement('div');
                    tag.className = 'file-tag';
                    tag.setAttribute('data-parent', currentId);
                    const icon = file.name.toLowerCase().endsWith('.pdf') ? 'fa-file-pdf pdf' : 'fa-file-image image';
                    tag.innerHTML = `
                    <i class="fa-solid ${icon}"></i> 
                    <span>${file.name}</span>
                    <button type="button" class="btn-remove-file" onclick="removeNewFileGroup('${currentId}')"><i class="fa-solid fa-circle-xmark"></i></button>
                `;
                    list.appendChild(tag);
                });

                inputCounter++;
                const newInput = document.createElement('input');
                newInput.type = 'file';
                newInput.name = 'documents[]';
                newInput.className = 'doc-input';
                newInput.multiple = true;
                newInput.onchange = function() {
                    handleFileSelection(this);
                };
                document.getElementById('inputsContainer').appendChild(newInput);
            }
        }

        function removeNewFileGroup(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
            document.querySelectorAll(`.file-tag[data-parent="${id}"]`).forEach(t => t.remove());
        }