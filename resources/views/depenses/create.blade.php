<x-app-layout title="Nouvelle dépense">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Nouvelle dépense</h2>
            <p class="page-header-sub">Enregistrez une charge ou une sortie de trésorerie.</p>
        </div>
        <a href="{{ route('depenses.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;margin-right:6px;"><polyline points="15 18 9 12 15 6"/></svg>
            Retour
        </a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('depenses.store') }}">
            @csrf

            <div class="form-group">
                <label for="libelle" class="form-label">Libellé *</label>
                <input type="text" id="libelle" name="libelle" class="form-input" value="{{ old('libelle') }}" placeholder="Ex: Loyer du mois de juin" required autofocus>
                @error('libelle') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="categorie" class="form-label">Catégorie</label>
                <select id="categorie" name="categorie" class="form-input">
                    <option value="">— Sans catégorie —</option>
                    @foreach(['Loyer','Salaires','Électricité','Transport','Fournitures','Autres'] as $cat)
                        <option value="{{ $cat }}" @selected(old('categorie') == $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('categorie') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="montant" class="form-label">Montant (FCFA) *</label>
                <input type="number" id="montant" name="montant" class="form-input" min="0" step="1" value="{{ old('montant') }}" placeholder="0" required>
                @error('montant') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="date" class="form-label">Date *</label>
                <input type="date" id="date" name="date" class="form-input" value="{{ old('date', now()->toDateString()) }}" required>
                @error('date') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Enregistrer</button>
                <a href="{{ route('depenses.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>
