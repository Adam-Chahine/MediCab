        const titles = {
            'rdv': 'Prendre un rendez-vous',
            'mes-rdv': 'Mes Rendez-vous',
            'historique': 'Historique de mes demandes',
            'dossier': 'Mon Dossier Médical',
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

        // ✅ UN SEUL écouteur — gère dimanche + créneaux pris
        document.getElementById('dateRdv').addEventListener('change', function() {
            const date = this.value;
            const d = new Date(date);
            const warn = document.getElementById('dateWarning');
            const select = document.querySelector('select[name="heure_rdv"]');

            // 1️⃣ Bloquer dimanche
            if (d.getUTCDay() === 0) {
                warn.style.display = 'block';
                this.value = '';
                return;
            } else {
                warn.style.display = 'none';
            }

            // 2️⃣ Récupérer créneaux pris
            if (!date) return;

            fetch('get_creneaux.php?date=' + date)
                .then(res => res.json())
                .then(heuresPrises => {
                    Array.from(select.options).forEach(opt => {
                        if (opt.value === "") return;
                        if (heuresPrises.includes(opt.value)) {
                            opt.disabled = true;
                            opt.textContent = opt.value;
                            opt.style.color = '#aaa';
                        } else {
                            opt.disabled = false;
                            opt.textContent = opt.value;
                            opt.style.color = '';
                        }
                    });

                    if (heuresPrises.includes(select.value)) {
                        select.value = "";
                    }
                });
        });

        // Set date min = demain
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('dateRdv').min = tomorrow.toISOString().split('T')[0];

        // Afficher nom fichier
        function updateFileName(input) {
            const label = document.getElementById('fileName');
            const icon = document.getElementById('uploadIcon');
            if (input.files && input.files[0]) {
                label.textContent = '✓ ' + input.files[0].name;
                label.style.display = 'block';
                icon.style.color = 'var(--accent)';
            }
        }

        /* ══ RENDU TABLE ══ */
        function renderTable(data) {
            const tbody = document.getElementById('histBody');
            const empty = document.getElementById('emptyState');
            tbody.innerHTML = '';
            if (data.length === 0) {
                empty.style.display = 'block';
                return;
            }
            empty.style.display = 'none';
            data.forEach(rdv => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                <td>${rdv.date_rdv}</td>
                <td>${rdv.heure_rdv}</td>
                <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${rdv.description}</td>
                <td>${getBadgeHTML(rdv.statut)}</td>
                <td><button class="btn-detail" onclick="openModal(${rdv.id})">
                    <i class="fa-solid fa-eye"></i> Détail
                </button></td>
            `;
                tbody.appendChild(tr);
            });
        }

        /* ══ FILTRES ══ */
        function filterRdv(statut, btn) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filtered = statut === 'all' ? rdvData : rdvData.filter(r => r.statut === statut);
            renderTable(filtered);
        }

        /* ══ BADGE HTML ══ */
        function getBadgeHTML(statut) {
            if (statut === 'en_attente') return `<span class="badge badge-waiting"><i class="fa-solid fa-hourglass-half"></i> En attente</span>`;
            if (statut === 'accepte') return `<span class="badge badge-accepted"><i class="fa-solid fa-check"></i> Accepté</span>`;
            if (statut === 'refuse') return `<span class="badge badge-refused"><i class="fa-solid fa-xmark"></i> Refusé</span>`;
            if (statut === 'termine') return `<span class="badge badge-accepted"><i class="fa-solid fa-check"></i> Passé</span>`;
            if (statut === 'absent') return `<span class="badge badge-refused"><i class="fa-solid fa-person-walking-arrow-right"></i> Absent</span>`;
            if (statut === 'annule') return `<span class="badge badge-refused"><i class="fa-solid fa-ban"></i> Annulé</span>`;
            return '';
        }

        /* ══ MODAL ══ */
        function openModal(id) {
            const rdv = rdvData.find(r => r.id == id);
            if (!rdv) return;

            document.getElementById('modalSubtitle').textContent = rdv.date_rdv + ' à ' + rdv.heure_rdv;
            document.getElementById('mDate').textContent = rdv.date_rdv;
            document.getElementById('mHeure').textContent = rdv.heure_rdv;
            document.getElementById('mMotif').textContent = rdv.description;
            document.getElementById('mDesc').textContent = rdv.description;

            const modalBadge = document.getElementById('modalBadge');
            modalBadge.className = 'badge ' + getBadgeClass(rdv.statut);
            modalBadge.innerHTML = getBadgeContent(rdv.statut);

            const mMaladiesContainer = document.getElementById('mMaladies');
            let listeMaladies = [];
            if (typeof rdv.maladies === 'string' && rdv.maladies.trim() !== "") {
                listeMaladies = rdv.maladies.split(',');
            } else if (Array.isArray(rdv.maladies)) {
                listeMaladies = rdv.maladies;
            }
            mMaladiesContainer.innerHTML = listeMaladies.length > 0 ?
                listeMaladies.map(m => `<span class="tag blue">${m.trim()}</span>`).join('') :
                `<span style="font-size:.82rem;color:#bbb;font-style:italic;">Aucune maladie déclarée</span>`;

            const mMedsContainer = document.getElementById('mMeds');
            let listeMeds = [];
            if (typeof rdv.medicaments === 'string' && rdv.medicaments.trim() !== "") {
                listeMeds = rdv.medicaments.split(',');
            } else if (Array.isArray(rdv.medicaments)) {
                listeMeds = rdv.medicaments;
            }
            mMedsContainer.innerHTML = listeMeds.length > 0 ?
                listeMeds.map(m => `<span class="tag green">${m.trim()}</span>`).join('') :
                `<span style="font-size:.82rem;color:#bbb;font-style:italic;">Aucun médicament déclaré</span>`;

            const mFile = document.getElementById('mFile');
            if (rdv.document && rdv.document !== "") {
                const documentUrl = rdv.document.startsWith('uploads/') 
                        ? rdv.document 
                        : 'uploads/' + rdv.document;
                const fileName = rdv.document.split('/').pop();
                const isPdf = fileName.toLowerCase().endsWith('.pdf');
                const icon = isPdf ? 'fa-file-pdf' : 'fa-file-image';
                mFile.innerHTML = `
            <div class="file-block" style="display:flex; align-items:center; gap:10px; padding:10px; background:#f8f9fa; border-radius:8px; border:1px solid #eee;">
                <i class="fa-solid ${icon}" style="font-size:1.5rem; color:var(--primary);"></i>
                <div style="flex:1">
                    <div class="fname" style="font-weight:600; font-size:0.9rem;">${fileName}</div>
                </div>
                <a href="${documentUrl}" download class="btn-download" style="color:var(--secondary); font-size:1.2rem;">
                    <i class="fa-solid fa-download"></i>
                </a>
            </div>`;
            } else {
                mFile.innerHTML = `<p class="no-file"><i class="fa-regular fa-folder-open"></i> Aucun document joint</p>`;
            }

            document.getElementById('modalOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function getBadgeClass(statut) {
            if (statut === 'en_attente') return 'badge-waiting';
            if (statut === 'accepte') return 'badge-accepted';
            if (statut === 'refuse' || statut === 'annule' || statut === 'absent') return 'badge-refused';
            return '';
        }

        function getBadgeContent(statut) {
            if (statut === 'en_attente') return '<i class="fa-solid fa-hourglass-half"></i> En attente';
            if (statut === 'accepte') return '<i class="fa-solid fa-check"></i> Accepté';
            if (statut === 'refuse') return '<i class="fa-solid fa-xmark"></i> Refusé';
            if (statut === 'annule') return '<i class="fa-solid fa-ban"></i> Annulé';
            if (statut === 'absent') return '<i class="fa-solid fa-person-walking-arrow-right"></i> Absent';
            return '';
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        function closeModalOutside(e) {
            if (e.target === document.getElementById('modalOverlay')) closeModal();
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        // Init au chargement
        renderTable(rdvData);

        // ══ MODAL ANNULATION ══
        function openAnnulModal(rdvId, date, heure) {
            document.getElementById('annulRdvId').value = rdvId;
            document.getElementById('annulModalSubtitle').textContent = date + ' à ' + heure;
            document.getElementById('annulRaison').value = '';
            document.getElementById('annulModalOverlay').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeAnnulModal(e) {
            if (e && e.target !== document.getElementById('annulModalOverlay')) return;
            document.getElementById('annulModalOverlay').style.display = 'none';
            document.body.style.overflow = '';
        }