<x-app-layout title="Nouveau client">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Nouveau client</h2>
            <p class="page-header-sub">Ajoutez un client à votre répertoire.</p>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('clients.store') }}">
            @csrf

            <div class="form-group">
                <label for="nom_complet" class="form-label">Nom complet</label>
                <input type="text" id="nom_complet" name="nom_complet" class="form-input" value="{{ old('nom_complet') }}" required autofocus>
                @error('nom_complet')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" id="telephone" name="telephone" class="form-input" value="{{ old('telephone') }}">
                @error('telephone')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="form-input" value="{{ old('adresse') }}">
                @error('adresse')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Créer le client</button>
                <a href="{{ route('clients.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>