<x-app-layout title="Modifier le client">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Modifier le client</h2>
            <p class="page-header-sub">Mettez à jour les informations de ce client.</p>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('clients.update', $client) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nom_complet" class="form-label">Nom complet</label>
                <input type="text" id="nom_complet" name="nom_complet" class="form-input" value="{{ old('nom_complet', $client->nom_complet) }}" required autofocus>
                @error('nom_complet')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" id="telephone" name="telephone" class="form-input" value="{{ old('telephone', $client->telephone) }}">
                @error('telephone')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $client->email) }}">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="form-input" value="{{ old('adresse', $client->adresse) }}">
                @error('adresse')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Enregistrer les modifications</button>
                <a href="{{ route('clients.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>