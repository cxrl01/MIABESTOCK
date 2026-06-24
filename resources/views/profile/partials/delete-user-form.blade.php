<section>
    <header style="margin-bottom: 20px;">
        <h2 class="page-header-title" style="font-size: 18px; color: var(--red);">Supprimer le compte</h2>
        <p class="page-header-sub">
            Une fois votre compte supprimé, toutes ses données seront définitivement effacées.
            Téléchargez les informations que vous souhaitez conserver avant de continuer.
        </p>
    </header>

    <button
        type="button"
        class="btn-action"
        style="background: var(--red);"
        onclick="document.getElementById('confirm-delete-modal').style.display='flex'"
    >
        Supprimer le compte
    </button>

    <div
        id="confirm-delete-modal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1000;"
    >
        <div class="form-card" style="max-width: 480px; width: 90%;">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <h3 class="page-header-title" style="font-size: 17px; margin-bottom: 8px;">
                    Êtes-vous sûr de vouloir supprimer votre compte ?
                </h3>
                <p class="page-header-sub" style="margin-bottom: 20px;">
                    Cette action est irréversible. Entrez votre mot de passe pour confirmer.
                </p>

                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input id="password" name="password" type="password" class="form-input" placeholder="Mot de passe">
                    @error('password', 'userDeletion')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="document.getElementById('confirm-delete-modal').style.display='none'">
                        Annuler
                    </button>
                    <button type="submit" class="btn-action" style="background: var(--red);">
                        Supprimer le compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>