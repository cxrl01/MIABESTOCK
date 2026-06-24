<section>
    <header style="margin-bottom: 20px;">
        <h2 class="page-header-title" style="font-size: 18px;">Modifier le mot de passe</h2>
        <p class="page-header-sub">Utilisez un mot de passe long et aléatoire pour sécuriser votre compte.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password" class="form-label">Mot de passe actuel</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-input" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password" class="form-label">Nouveau mot de passe</label>
            <input id="update_password_password" name="password" type="password" class="form-input" autocomplete="new-password">
            @error('password', 'updatePassword')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-action">Enregistrer</button>

            @if (session('status') === 'password-updated')
                <p style="font-size: 13px; color: var(--green); font-weight: 600; align-self: center;">Enregistré.</p>
            @endif
        </div>
    </form>
</section>