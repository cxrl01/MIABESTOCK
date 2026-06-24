<x-app-layout title="Nouvelle catégorie">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Nouvelle catégorie</h2>
            <p class="page-header-sub">Ajoutez une catégorie pour organiser vos produits.</p>
        </div>
        <a href="{{ route('produits.index') }}" class="btn btn-secondary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Retour
    </a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div class="form-group">
                <label for="nom" class="form-label">Nom de la catégorie</label>
                <input type="text" id="nom" name="nom" class="form-input" value="{{ old('nom') }}" required autofocus>
                @error('nom')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description (optionnel)</label>
                <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Créer la catégorie</button>
                <a href="{{ route('categories.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>