<x-app-layout title="Nouvelle vente">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Nouvelle vente</h2>
            <p class="page-header-sub">Recherchez un produit par nom ou scannez son code.</p>
        </div>
        <a href="{{ route('ventes.index') }}" class="btn-secondary">← Retour</a>
    </div>

    @error('produits')
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
        </div>
    @enderror

    <div class="pos-layout">

        <!-- COLONNE GAUCHE : recherche + grille produits -->
        <div class="pos-products">
            <input type="text" id="searchInput" class="form-input" placeholder="Rechercher un produit (nom ou code)..." autofocus style="margin-bottom: 16px;">

            <div class="pos-grid" id="produitsGrid">
                @foreach($produits as $produit)
                    <div class="pos-card"
                         data-id="{{ $produit->id }}"
                         data-nom="{{ strtolower($produit->nom) }}"
                         data-code="{{ strtolower($produit->code) }}"
                         data-prix="{{ $produit->prix_vente }}"
                         data-stock="{{ $produit->quantite_stock }}">
                        <div class="pos-card-nom">{{ $produit->nom }}</div>
                        <div class="pos-card-code">{{ $produit->code }}</div>
                        <div class="pos-card-prix">{{ number_format($produit->prix_vente, 0, ',', ' ') }} F</div>
                        <div class="pos-card-stock">Stock : {{ $produit->quantite_stock }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- COLONNE DROITE : panier -->
        <div class="pos-cart">
            <form method="POST" action="{{ route('ventes.store') }}" id="venteForm">
                @csrf

                <div class="form-group">
                    <label for="client_id" class="form-label">Client</label>
                    <select id="client_id" name="client_id" class="form-input">
                        <option value="">— Client anonyme —</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->nom_complet }}</option>
                        @endforeach
                    </select>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border); margin: 16px 0;">

                <div id="panierContainer" class="pos-panier-vide">
                    <p>Aucun produit ajouté</p>
                </div>

                <div class="pos-total-row">
                    <span>Total</span>
                    <span id="totalAffiche">0 F</span>
                </div>

                <div class="form-group" style="margin-top: 16px;">
                    <label for="mode_paiement" class="form-label">Mode de paiement</label>
                    <select id="mode_paiement" name="mode_paiement" class="form-input">
                        <option value="especes">💵 Espèces</option>
                        <option value="mobile_money">📱 Mobile Money</option>
                        <option value="cheque">📄 Chèque</option>
                        <option value="virement">🏦 Virement</option>
                    </select>
                </div>

                <div class="form-group" style="margin-top: 16px;">
                    <label for="montant_paye" class="form-label">Montant payé</label>
                    <input type="number" id="montant_paye" name="montant_paye" class="form-input" min="0" step="1" value="0" required>
                    <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 6px;">
                        Reste à payer : <span id="resteAffiche">0 F</span>
                    </p>
                </div>

                <button type="submit" class="btn-action" style="width: 100%; justify-content: center; margin-top: 12px;" id="validerBtn" disabled>
                    Valider la vente
                </button>
            </form>
        </div>

    </div>

    <script>
        const panier = {}; // { produitId: { nom, prix, quantite, stock } }

        const searchInput = document.getElementById('searchInput');
        const produitsGrid = document.getElementById('produitsGrid');
        const panierContainer = document.getElementById('panierContainer');
        const totalAffiche = document.getElementById('totalAffiche');
        const resteAffiche = document.getElementById('resteAffiche');
        const montantPayeInput = document.getElementById('montant_paye');
        const validerBtn = document.getElementById('validerBtn');
        const venteForm = document.getElementById('venteForm');

        // Recherche en temps réel (nom ou code) — compatible scanner USB
        searchInput.addEventListener('input', () => {
            const terme = searchInput.value.toLowerCase().trim();
            document.querySelectorAll('.pos-card').forEach(card => {
                const nom = card.dataset.nom;
                const code = card.dataset.code;
                const visible = nom.includes(terme) || code.includes(terme);
                card.style.display = visible ? '' : 'none';
            });
        });

        // Scanner USB : tape le code puis "Entrée" -> ajoute directement si correspondance exacte
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const terme = searchInput.value.toLowerCase().trim();
                const carteExacte = document.querySelector(`.pos-card[data-code="${terme}"]`);
                if (carteExacte) {
                    ajouterAuPanier(carteExacte);
                    searchInput.value = '';
                    document.querySelectorAll('.pos-card').forEach(c => c.style.display = '');
                }
            }
        });

        // Clic sur une carte produit
        produitsGrid.addEventListener('click', (e) => {
            const card = e.target.closest('.pos-card');
            if (card) ajouterAuPanier(card);
        });

        function ajouterAuPanier(card) {
            const id = card.dataset.id;
            const nom = card.querySelector('.pos-card-nom').textContent;
            const prix = parseFloat(card.dataset.prix);
            const stock = parseInt(card.dataset.stock);

            if (!panier[id]) {
                panier[id] = { nom, prix, quantite: 0, stock };
            }

            if (panier[id].quantite >= stock) {
                alert('Stock insuffisant pour ce produit.');
                return;
            }

            panier[id].quantite++;
            render();
        }

        function changerQuantite(id, delta) {
            if (!panier[id]) return;
            panier[id].quantite += delta;

            if (panier[id].quantite > panier[id].stock) {
                panier[id].quantite = panier[id].stock;
                alert('Stock insuffisant pour ce produit.');
            }

            if (panier[id].quantite <= 0) {
                delete panier[id];
            }

            render();
        }

        function render() {
            const ids = Object.keys(panier);

            if (ids.length === 0) {
                panierContainer.innerHTML = '<p class="pos-panier-vide-text">Aucun produit ajouté</p>';
                panierContainer.className = 'pos-panier-vide';
                validerBtn.disabled = true;
            } else {
                panierContainer.className = '';
                let html = '';
                ids.forEach(id => {
                    const item = panier[id];
                    const sousTotal = item.prix * item.quantite;
                    html += `
                        <div class="pos-panier-item">
                            <div class="pos-panier-item-info">
                                <div class="pos-panier-item-nom">${item.nom}</div>
                                <div class="pos-panier-item-prix">${item.prix.toLocaleString('fr-FR')} F × ${item.quantite}</div>
                            </div>
                            <div class="pos-panier-item-actions">
                                <button type="button" onclick="changerQuantite('${id}', -1)" class="pos-qty-btn">−</button>
                                <span>${item.quantite}</span>
                                <button type="button" onclick="changerQuantite('${id}', 1)" class="pos-qty-btn">+</button>
                            </div>
                            <div class="pos-panier-item-total">${sousTotal.toLocaleString('fr-FR')} F</div>
                            <input type="hidden" name="produits[${id}][id]" value="${id}">
                            <input type="hidden" name="produits[${id}][quantite]" value="${item.quantite}">
                            <input type="hidden" name="produits[${id}][prix_unitaire]" value="${item.prix}">
                        </div>
                    `;
                });
                panierContainer.innerHTML = html;
                validerBtn.disabled = false;
            }

            const total = ids.reduce((sum, id) => sum + panier[id].prix * panier[id].quantite, 0);
            totalAffiche.textContent = total.toLocaleString('fr-FR') + ' F';
            montantPayeInput.value = total;
            calculerReste();
        }

        function calculerReste() {
            const total = Object.keys(panier).reduce((sum, id) => sum + panier[id].prix * panier[id].quantite, 0);
            const paye = parseFloat(montantPayeInput.value) || 0;
            const reste = Math.max(total - paye, 0);
            resteAffiche.textContent = reste.toLocaleString('fr-FR') + ' F';
        }

        montantPayeInput.addEventListener('input', calculerReste);

        venteForm.addEventListener('submit', (e) => {
            e.preventDefault();

            if (Object.keys(panier).length === 0) {
                alert('Ajoutez au moins un produit avant de valider.');
                return;
            }

            validerBtn.disabled = true;
            validerBtn.textContent = 'Validation en cours...';

            fetch(venteForm.action, {
                method: 'POST',
                body: new FormData(venteForm),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const printBtn = document.getElementById('modalPrintInvoice');
                    printBtn.href = data.pdf_url;
                    
                    window.open(data.pdf_url, '_blank');

                    document.getElementById('success-modal').style.display = 'flex';
                } else {
                    alert('Erreur lors de la validation de la vente.');
                    validerBtn.disabled = false;
                    validerBtn.textContent = 'Valider la vente';
                }
            })
            .catch(err => {
                console.error(err);
                alert('Une erreur est survenue lors de l\'enregistrement.');
                validerBtn.disabled = false;
                validerBtn.textContent = 'Valider la vente';
            });
        });

        document.getElementById('modalNewSale').addEventListener('click', () => {
            document.getElementById('success-modal').style.display = 'none';

            for (const key in panier) {
                delete panier[key];
            }

            render();
            venteForm.reset();
            
            validerBtn.disabled = true;
            validerBtn.textContent = 'Valider la vente';
            
            searchInput.value = '';
            searchInput.focus();
        });
    </script>

    <!-- Modal Succès Vente -->
    <div id="success-modal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div class="modal-content" style="background-color: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 32px; max-width: 480px; width: 90%; box-shadow: var(--shadow); text-align: center; position: relative; margin: auto;">
            <div style="width: 60px; height: 60px; background-color: rgba(46, 204, 113, 0.1); color: var(--green); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="width: 32px; height: 32px;"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 800; color: var(--text); margin-bottom: 8px;">Vente enregistrée !</h3>
            <p style="color: var(--text-sec); font-size: 14px; margin-bottom: 24px; line-height: 1.5;">La transaction a été validée et les stocks ont été mis à jour automatiquement.</p>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a id="modalPrintInvoice" href="#" target="_blank" class="btn-action" style="width: 100%; justify-content: center; font-weight: 700; height: 42px; display: inline-flex; align-items: center; gap: 6px;">
                    🖨️ Imprimer la Facture
                </a>
                <button type="button" id="modalNewSale" class="btn btn-secondary" style="width: 100%; justify-content: center; height: 42px; font-weight: 600; display: inline-flex; align-items: center; cursor: pointer;">
                    🆕 Nouvelle Vente
                </button>
                <a href="{{ route('ventes.index') }}" class="btn btn-secondary" style="width: 100%; justify-content: center; height: 42px; font-weight: 600; display: inline-flex; align-items: center; cursor: pointer; text-decoration: none;">
                    📋 Historique des Ventes
                </a>
            </div>
        </div>
    </div>

</x-app-layout>