<x-guest-layout>
    <p style="font-size:22px; font-weight:700; color:#111827; margin-bottom:6px; font-family:'Syne',sans-serif; text-align:center;">Connexion</p>
    <p style="font-size:13px; color:#6b7280; text-align:center; margin-bottom:28px;">Accédez à votre espace MiabéStock</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button>
                Se connecter
            </x-primary-button>
        </div>

        <p style="text-align:center; font-size:13px; color:#6b7280; margin-top:18px;">
            Pas encore inscrit ?
            <a href="{{ route('register') }}" style="color:#1a56db; font-weight:600;">Créer un compte</a>
        </p>
    </form>
</x-guest-layout>