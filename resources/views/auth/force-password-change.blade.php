<x-guest-layout>
    <p style="font-size:22px; font-weight:700; color:#111827; margin-bottom:6px; font-family:'Syne',sans-serif; text-align:center;">Bienvenue !</p>
    <p style="font-size:13px; color:#6b7280; text-align:center; margin-bottom:28px;">
        Pour des raisons de sécurité, vous devez changer votre mot de passe avant de continuer.
    </p>

    <form method="POST" action="{{ route('password.force.update') }}">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="password" :value="__('Nouveau mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button>
                Changer mon mot de passe
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>