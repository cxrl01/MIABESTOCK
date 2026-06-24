<x-guest-layout>
    <p style="font-size:22px; font-weight:700; color:#111827; margin-bottom:6px; font-family:'Syne',sans-serif; text-align:center;">Créer mon compte</p>
    <p style="font-size:13px; color:#6b7280; text-align:center; margin-bottom:28px;">Votre boutique en quelques secondes</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nom complet')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="boutique_nom" :value="__('Nom de la boutique')" />
            <x-text-input id="boutique_nom" class="block mt-1 w-full" type="text" name="boutique_nom" :value="old('boutique_nom')" required autocomplete="organization" />
            <x-input-error :messages="$errors->get('boutique_nom')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button>
                Créer ma boutique
            </x-primary-button>
        </div>

        <p style="text-align:center; font-size:13px; color:#6b7280; margin-top:18px;">
            Déjà inscrit ?
            <a href="{{ route('login') }}" style="color:#1a56db; font-weight:600;">Se connecter</a>
        </p>
    </form>
</x-guest-layout>