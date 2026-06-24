<x-app-layout title="Modifier le fournisseur">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Modifier le fournisseur</h2>
            <p class="page-header-sub">Mettez à jour les informations de ce fournisseur.</p>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('fournisseurs.update', $fournisseur) }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" id="nom" name="nom" class="form-input" value="{{ old('nom', $fournisseur->nom) }}" required autofocus>
                @error('nom')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" id="telephone" name="telephone" class="form-input" value="{{ old('telephone', $fournisseur->telephone) }}">
                @error('telephone')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $fournisseur->email) }}">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="form-input" value="{{ old('adresse', $fournisseur->adresse) }}">
                @error('adresse')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Enregistrer les modifications</button>
                <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>
