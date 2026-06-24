<x-app-layout title="Modifier le collaborateur">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Modifier le collaborateur</h2>
            <p class="page-header-sub">Mettez à jour les informations et le rôle de ce membre.</p>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('equipe.update', $membre) }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $membre->name) }}" required autofocus>
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $membre->email) }}" required>
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Rôle</label>
                <select id="role" name="role" class="form-input" required>
                    <option value="">-- Choisir un rôle --</option>
                    <option value="gestionnaire" {{ old('role', $membre->role) === 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                    <option value="commercial" {{ old('role', $membre->role) === 'commercial' ? 'selected' : '' }}>Commercial</option>
                </select>
                @error('role')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Nouveau mot de passe <span class="text-muted" style="font-weight: 400;">(laisser vide pour ne pas modifier)</span></label>
                <input type="password" id="password" name="password" class="form-input">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmation du mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
                @error('password_confirmation')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Enregistrer les modifications</button>
                <a href="{{ route('equipe.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

</x-app-layout>
