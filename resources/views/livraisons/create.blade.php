<x-app-layout title="Nouvelle livraison">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Nouvelle livraison</h2>
            <p class="page-header-sub">Enregistrez les produits reçus d'un fournisseur.</p>
        </div>
    </div>

    @error('produits')
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
        </div>
    @enderror

    <div class="form-card" style="max-width: 800px;">
        <form method="POST" action="{{ route('livraisons.store') }}" id="livraisonForm">
            @csrf

            <div class="form-group">
                <label for="fournisseur_id" class="form-label">Fournisseur</label>
                <select id="fournisseur_id" name="fournisseur_id" class="form-input" required>
                    <option value="">— Sélectionner un fournisseur —</option>
                    @foreach($fournisseurs as $fournisseur)
                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                    @endforeach
                </select>
                @error('fournisseur_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <hr style="border: none; border-top: 1px solid var(--border); margin: 20px 0;">

            <label class="form-label">Produits reçus</label>

            <div id="lignesContainer"></div>

            <button type="button" id="ajouterLigneBtn" class="btn-secondary" style="margin-top: 10px;">
                + Ajouter un produit
            </button>

            <div style="text-align: right; margin-top: 20px; font-size: 16px; font-weight: 700; color: var(--text);">
                Total : <span id="totalGeneral">0</span> F
            </div>

            <hr style="border: none; border-top: 1px solid var(--border); margin: 20px 0;">

            <div class="form-group">
                <label for="montant_paye" class="form-label">Montant payé au fournisseur</label>
                <input type="number" id="montant_paye" name="montant_paye" class="form-input" min="0" step="1" value="0" required>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 6px;">
                    Reste à payer : <span id="resteAffiche">0 F</span>
                </p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Enregistrer la livraison</button>
                <a href="{{ route('livraisons.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        const produitsDisponibles = @json($produits->map(fn($p) => ['id' => $p->id, 'nom' => $p->nom, 'prix' => $p->prix_achat]));
    </script>

    <script>
        let ligneIndex = 0;
        const container = document.getElementById('lignesContainer');
        const totalGeneralEl = document.getElementById('totalGeneral');
        const montantPayeInput = document.getElementById('montant_paye');
        const resteAffiche = document.getElementById('resteAffiche');

        function ajouterLigne() {
            const index = ligneIndex++;

            const div = document.createElement('div');
            div.className = 'ligne-produit';
            div.style.cssText = 'display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 10px; align-items: end; margin-bottom: 12px;';
            div.dataset.index = index;

            let optionsHtml = '<option value="">— Produit —</option>';
            produitsDisponibles.forEach(p => {
                optionsHtml += `<option value="${p.id}" data-prix="${p.prix}">${p.nom}</option>`;
            });

            div.innerHTML = `
                <div class="form-group" style="margin-bottom:0;">
                    <select name="produits[${index}][id]" class="form-input produit-select" required>
                        ${optionsHtml}
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <input type="number" name="produits[${index}][quantite]" class="form-input quantite-input" placeholder="Qté" min="1" value="1" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <input type="number" name="produits[${index}][prix_unitaire]" class="form-input prix-input" placeholder="Prix unitaire" step="0.01" min="0" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <input type="text" class="form-input sous-total-display" placeholder="Sous-total" readonly value="0 F">
                </div>
                <button type="button" class="icon-btn-sm icon-btn-danger supprimer-ligne" title="Retirer">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            `;

            container.appendChild(div);

            const select = div.querySelector('.produit-select');
            const quantiteInput = div.querySelector('.quantite-input');
            const prixInput = div.querySelector('.prix-input');
            const sousTotalDisplay = div.querySelector('.sous-total-display');
            const supprimerBtn = div.querySelector('.supprimer-ligne');

            select.addEventListener('change', () => {
                const option = select.options[select.selectedIndex];
                const prix = option.dataset.prix || 0;
                prixInput.value = prix;
                calculerLigne();
            });

            quantiteInput.addEventListener('input', calculerLigne);
            prixInput.addEventListener('input', calculerLigne);

            function calculerLigne() {
                const qte = parseFloat(quantiteInput.value) || 0;
                const prix = parseFloat(prixInput.value) || 0;
                const sousTotal = qte * prix;
                sousTotalDisplay.value = sousTotal.toLocaleString('fr-FR') + ' F';
                calculerTotalGeneral();
            }

            supprimerBtn.addEventListener('click', () => {
                div.remove();
                calculerTotalGeneral();
            });
        }

        function calculerTotalGeneral() {
            let total = 0;
            document.querySelectorAll('.ligne-produit').forEach(div => {
                const qte = parseFloat(div.querySelector('.quantite-input').value) || 0;
                const prix = parseFloat(div.querySelector('.prix-input').value) || 0;
                total += qte * prix;
            });
            totalGeneralEl.textContent = total.toLocaleString('fr-FR');
            montantPayeInput.value = total;
            calculerReste();
        }

        function calculerReste() {
            const total = parseFloat(totalGeneralEl.textContent.replace(/\s/g, '').replace(',', '.')) || 0;
            const paye = parseFloat(montantPayeInput.value) || 0;
            const reste = Math.max(total - paye, 0);
            resteAffiche.textContent = reste.toLocaleString('fr-FR') + ' F';
        }

        montantPayeInput.addEventListener('input', calculerReste);

        document.getElementById('ajouterLigneBtn').addEventListener('click', ajouterLigne);

        ajouterLigne();
    </script>

</x-app-layout>