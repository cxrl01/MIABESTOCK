@php
    $layout = auth()->user()->estSuperAdmin() ? 'admin-layout' : 'app-layout';
@endphp

<x-dynamic-component :component="$layout" title="Mon profil">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Mon profil</h2>
            <p class="page-header-sub">Gérez vos informations personnelles et votre sécurité.</p>
        </div>
    </div>

    <div class="form-card" style="margin-bottom: 24px;">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="form-card" style="margin-bottom: 24px;">
        @include('profile.partials.update-password-form')
    </div>

    <div class="form-card">
        @include('profile.partials.delete-user-form')
    </div>

</x-dynamic-component>