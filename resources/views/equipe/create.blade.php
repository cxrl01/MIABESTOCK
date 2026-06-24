<x-app-layout title="Nouveau collaborateur">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Nouveau collaborateur</h2>
            <p class="page-header-sub">Ajoutez un membre à votre équipe et définissez son rôle.</p>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('equipe.store') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Rôle</label>
                <select id="role" name="role" class="form-input" required>
                    <option value="">-- Choisir un rôle --</option>
                    <option value="gestionnaire" {{ old('role') === 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                    <option value="commercial" {{ old('role') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                </select>
                @error('role')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-input" required>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmation du mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                @error('password_confirmation')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Créer le collaborateur</button>
                <a href="{{ route('equipe.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>
