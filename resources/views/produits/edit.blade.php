<x-app-layout title="Modifier le produit">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Modifier le produit</h2>
            <p class="page-header-sub">Mettez à jour les informations de ce produit.</p>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('produits.update', $produit) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="categorie_id" class="form-label">Catégorie</label>
                <select id="categorie_id" name="categorie_id" class="form-input" required>
                    <option value="">— Sélectionner une catégorie —</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" @selected(old('categorie_id', $produit->categorie_id) == $categorie->id)>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
                @error('categorie_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="code" class="form-label">Code produit</label>
                <input type="text" id="code" name="code" class="form-input" value="{{ old('code', $produit->code) }}" required>
                @error('code')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="nom" class="form-label">Nom du produit</label>
                <input type="text" id="nom" name="nom" class="form-input" value="{{ old('nom', $produit->nom) }}" required autofocus>
                @error('nom')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="prix_achat" class="form-label">Prix d'achat (FCFA)</label>
                    <input type="number" step="0.01" min="0" id="prix_achat" name="prix_achat" class="form-input" value="{{ old('prix_achat', $produit->prix_achat) }}" required>
                    @error('prix_achat')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="prix_vente" class="form-label">Prix de vente (FCFA)</label>
                    <input type="number" step="0.01" min="0" id="prix_vente" name="prix_vente" class="form-input" value="{{ old('prix_vente', $produit->prix_vente) }}" required>
                    @error('prix_vente')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="quantite_stock" class="form-label">Quantité en stock</label>
                    <input type="number" min="0" id="quantite_stock" name="quantite_stock" class="form-input" value="{{ old('quantite_stock', $produit->quantite_stock) }}" required>
                    @error('quantite_stock')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="seuil_alerte" class="form-label">Seuil d'alerte</label>
                    <input type="number" min="0" id="seuil_alerte" name="seuil_alerte" class="form-input" value="{{ old('seuil_alerte', $produit->seuil_alerte) }}" required>
                    @error('seuil_alerte')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Enregistrer les modifications</button>
                <a href="{{ route('produits.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>