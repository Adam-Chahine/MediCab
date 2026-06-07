        const titles = {
            'dashboard': 'Dashboard',
            'rdv': 'Gestion des Rendez-vous',
            'patients': 'Mes Patients',
            'consultations': 'Consultations',
            'modifierpatient': 'Modifier Patients',
            'profil': 'Mon Profil',
        };

        function showSection(id, el) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            document.getElementById('section-' + id).classList.add('active');
            if (el) el.classList.add('active');
            document.getElementById('topbarTitle').textContent = titles[id] || '';
            closeSidebar();
        }

        function toggleSidebar() {
            const s = document.getElementById('sidebar');
            const o = document.getElementById('overlay');
            s.classList.toggle('open');
            o.style.display = s.classList.contains('open') ? 'block' : 'none';
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').style.display = 'none';
        }

        // ── Filtres RDV ──
        function filterRdv(statut, btn) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('#rdvBody tr').forEach(tr => {
                if (statut === 'all' || tr.dataset.status === statut) {
                    tr.style.display = '';
                } else {
                    tr.style.display = 'none';
                }
            });
        }

        // ── Redirection section depuis URL ──
        const params = new URLSearchParams(window.location.search);
        const section = params.get('section');
        if (section) {
            const navItem = document.querySelector(`.nav-item[onclick*="'${section}'"]`);
            showSection(section, navItem);
        }

        // ══ MODAL DÉTAIL RDV ══
        function openRdvModal(patient, date, heure, motif, maladies, medicaments, docPath, rdvId) {
            document.getElementById('rdvModalSubtitle').textContent = date + ' à ' + heure;
            document.getElementById('rdvModalPatient').textContent = patient;
            document.getElementById('rdvModalDate').textContent = date + ' à ' + heure;
            document.getElementById('rdvModalMotif').textContent = motif;

            const maladiesEl = document.getElementById('rdvModalMaladies');
            if (maladies && maladies.trim() !== '') {
                maladiesEl.innerHTML = maladies.split(',')
                    .map(m => `<span style="display:inline-flex;align-items:center;gap:5px;
                    background:rgba(52,152,219,0.08);border:1px solid rgba(52,152,219,0.2);
                    border-radius:50px;padding:3px 10px;font-size:0.78rem;font-weight:600;
                    color:var(--secondary);margin:2px;">${m.trim()}</span>`)
                    .join('');
            } else {
                maladiesEl.innerHTML = '<span style="font-size:0.82rem;color:#bbb;font-style:italic;">Aucune maladie déclarée</span>';
            }

            const medsEl = document.getElementById('rdvModalMedicaments');
            if (medicaments && medicaments.trim() !== '') {
                medsEl.innerHTML = medicaments.split(',')
                    .map(m => `<span style="display:inline-flex;align-items:center;gap:5px;
                    background:rgba(46,204,113,0.08);border:1px solid rgba(46,204,113,0.2);
                    border-radius:50px;padding:3px 10px;font-size:0.78rem;font-weight:600;
                    color:var(--accent);margin:2px;">${m.trim()}</span>`)
                    .join('');
            } else {
                medsEl.innerHTML = '<span style="font-size:0.82rem;color:#bbb;font-style:italic;">Aucun médicament déclaré</span>';
            }

            const docEl = document.getElementById('rdvModalDoc');
            if (docPath && docPath !== '') {
                const fileName = docPath.split('/').pop();
                const isPdf = fileName.toLowerCase().endsWith('.pdf');
                const icon = isPdf ? 'fa-file-pdf' : 'fa-file-image';
                const color = isPdf ? 'var(--danger)' : 'var(--secondary)';
                docEl.innerHTML = `
                <div style="display:flex;align-items:center;gap:10px;">
                    <i class="fa-solid ${icon}" style="font-size:1.4rem;color:${color};"></i>
                    <span style="font-size:0.85rem;font-weight:600;">${fileName}</span>
                    <a href="uploads/${docPath}" target="_blank"
                       style="margin-left:auto;padding:6px 14px;background:var(--secondary);
                       color:white;border-radius:50px;font-size:0.75rem;font-weight:700;
                       text-decoration:none;">
                        <i class="fa-solid fa-download"></i> Télécharger
                    </a>
                </div>`;
            } else {
                docEl.innerHTML = '<span style="font-size:0.82rem;color:#bbb;font-style:italic;">Aucun document joint</span>';
            }

            document.getElementById('modalRdvId').value = rdvId;
            document.getElementById('modalRdvId2').value = rdvId;
            document.getElementById('rdvModalOverlay').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeRdvModal(e) {
            if (e && e.target !== document.getElementById('rdvModalOverlay')) return;
            document.getElementById('rdvModalOverlay').style.display = 'none';
            document.body.style.overflow = '';
        }

        // ══ MODAL ANNULATION MÉDECIN ══
        function openAnnulModalMed(rdvId, date, heure, patient) {
            document.getElementById('annulMedRdvId').value = rdvId;
            document.getElementById('annulMedSubtitle').textContent = patient + ' — ' + date + ' à ' + heure;
            document.getElementById('annulMedOverlay').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeAnnulModalMed(e) {
            if (e && e.target !== document.getElementById('annulMedOverlay')) return;
            document.getElementById('annulMedOverlay').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.getElementById('rdvModalOverlay').style.display = 'none';
                document.getElementById('annulMedOverlay').style.display = 'none';
                document.body.style.overflow = '';
            }
        });

        // ── Recherche patients ──
        document.getElementById('patientSearch').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            document.querySelectorAll('.patient-card').forEach(card => {
                let name = card.querySelector('.patient-name').textContent.toLowerCase();
                let cin = card.querySelector('.patient-cin').textContent.toLowerCase();
                card.style.display = (name.includes(filter) || cin.includes(filter)) ? '' : 'none';
            });
        });

        // ── Modal modifier patient ──
        function openEditModal(patient) {
            document.getElementById('mod_id').value = patient.id;
            document.getElementById('mod_nom').value = patient.nom;
            document.getElementById('mod_prenom').value = patient.prenom;
            document.getElementById('mod_cin').value = patient.cin;
            document.getElementById('mod_email').value = patient.email;
            document.getElementById('mod_adresse').value = patient.adresse || '';
            document.getElementById('modalModifier').style.display = 'flex';
        }

        function fermerModale() {
            document.getElementById('modalModifier').style.display = 'none';
        }

        // ══ DÉTAIL RDV ══
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-open-rdv');
            if (!btn) return;

            const d = btn.dataset;

            document.getElementById('rdvModalSubtitle').textContent = d.date + ' à ' + d.heure;
            document.getElementById('rdvModalPatient').textContent = d.patient;
            document.getElementById('rdvModalDate').textContent = d.date + ' à ' + d.heure;
            document.getElementById('rdvModalMotif').textContent = d.motif;

            // Maladies
            const maladiesEl = document.getElementById('rdvModalMaladies');
            if (d.maladies && d.maladies.trim() !== '') {
                maladiesEl.innerHTML = d.maladies.split(',')
                    .map(m => `<span style="display:inline-flex;align-items:center;gap:5px;
                background:rgba(52,152,219,0.08);border:1px solid rgba(52,152,219,0.2);
                border-radius:50px;padding:3px 10px;font-size:0.78rem;font-weight:600;
                color:var(--secondary);margin:2px;">${m.trim()}</span>`).join('');
            } else {
                maladiesEl.innerHTML = '<span style="font-size:0.82rem;color:#bbb;font-style:italic;">Aucune maladie déclarée</span>';
            }

            // Médicaments
            const medsEl = document.getElementById('rdvModalMedicaments');
            if (d.medicaments && d.medicaments.trim() !== '') {
                medsEl.innerHTML = d.medicaments.split(',')
                    .map(m => `<span style="display:inline-flex;align-items:center;gap:5px;
                background:rgba(46,204,113,0.08);border:1px solid rgba(46,204,113,0.2);
                border-radius:50px;padding:3px 10px;font-size:0.78rem;font-weight:600;
                color:var(--accent);margin:2px;">${m.trim()}</span>`).join('');
            } else {
                medsEl.innerHTML = '<span style="font-size:0.82rem;color:#bbb;font-style:italic;">Aucun médicament déclaré</span>';
            }

            // Document
            const docEl = document.getElementById('rdvModalDoc');

            // Si d.document contient déjà 'uploads/...', on l'utilise tel quel
            if (d.document && d.document !== '') {
                const fileName = d.document.split('/').pop();
                const isPdf = fileName.toLowerCase().endsWith('.pdf');
                const icon = isPdf ? 'fa-file-pdf' : 'fa-file-image';
                const color = isPdf ? 'var(--danger)' : 'var(--secondary)';

                // CORRECTION : On retire "uploads/" du href car d.document le contient déjà
                docEl.innerHTML = `
                    <div style="display:flex;align-items:center;gap:10px;">
                    <i class="fa-solid ${icon}" style="font-size:1.4rem;color:${color};"></i>
                    <span style="font-size:0.85rem;font-weight:600;">${fileName}</span>
                    <a href="${d.document}" 
                        download="${fileName}" 
                        style="margin-left:auto;padding:6px 14px;background:var(--secondary);
                        color:white;border-radius:50px;font-size:0.75rem;font-weight:700;
                        text-decoration:none;">
                        <i class="fa-solid fa-download"></i> Télécharger
                    </a>
                </div>`;
            } else {
                docEl.innerHTML = '<span style="font-size:0.82rem;color:#bbb;font-style:italic;">Aucun document joint</span>';
            }

            document.getElementById('modalRdvId').value = d.id;
            document.getElementById('modalRdvId2').value = d.id;
            document.getElementById('rdvModalOverlay').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        // ══ ANNULATION ══
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-open-annul');
            if (!btn) return;

            const d = btn.dataset;
            document.getElementById('annulMedRdvId').value = d.id;
            document.getElementById('annulMedSubtitle').textContent = d.patient + ' — ' + d.date + ' à ' + d.heure;
            document.getElementById('annulMedOverlay').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });