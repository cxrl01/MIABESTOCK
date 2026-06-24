<x-app-layout title="Modifier la catégorie">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Modifier la catégorie</h2>
            <p class="page-header-sub">Mettez à jour les informations de cette catégorie.</p>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('categories.update', $categorie) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nom" class="form-label">Nom de la catégorie</label>
                <input type="text" id="nom" name="nom" class="form-input" value="{{ old('nom', $categorie->nom) }}" required autofocus>
                @error('nom')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description (optionnel)</label>
                <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description', $categorie->description) }}</textarea>
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Enregistrer les modifications</button>
                <a href="{{ route('categories.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>