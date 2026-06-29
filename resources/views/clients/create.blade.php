<x-app-layout title="Nouveau collaborateur">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Nouveau collaborateur</h2>
            <p class="page-header-sub">Ajoutez un membre à votre équipe et définissez son rôle.</p>
        </div>
    </div>

    <div class="form-card">
        <div class="alert" style="background: var(--accent-light); color: var(--accent); margin-bottom: 16px;">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            Un mot de passe temporaire sera généré automatiquement et envoyé par email au collaborateur.
        </div>

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

            <div class="form-actions">
                <button type="submit" class="btn-action">Créer le collaborateur</button>
                <a href="{{ route('equipe.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>