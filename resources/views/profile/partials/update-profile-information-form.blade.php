<section>
    <header style="margin-bottom: 20px;">
        <h2 class="page-header-title" style="font-size: 18px;">Informations du profil</h2>
        <p class="page-header-sub">Mettez à jour votre nom et votre adresse email.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">Nom complet</label>
            <input id="name" name="name" type="text" class="form-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p style="font-size: 13px; color: var(--text-sec); margin-top: 8px;">
                    Votre adresse email n'est pas vérifiée.
                    <button form="send-verification" style="color: var(--accent); font-weight: 600; text-decoration: underline; background: none; border: none; cursor: pointer;">
                        Cliquez ici pour renvoyer l'email de vérification.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p style="font-size: 13px; font-weight: 600; color: var(--green); margin-top: 8px;">
                        Un nouveau lien de vérification a été envoyé à votre adresse email.
                    </p>
                @endif
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-action">Enregistrer</button>

            @if (session('status') === 'profile-updated')
                <p style="font-size: 13px; color: var(--green); font-weight: 600; align-self: center;">Enregistré.</p>
            @endif
        </div>
    </form>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>
    @endif
</section>