<x-app-layout title="Administration">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Paramètres de la boutique</h2>
            <p class="page-header-sub">Gérez les informations générales de votre boutique.</p>
        </div>
    </div>

    <div class="form-card" style="max-width: 600px;">
        <form method="POST" action="{{ route('administration.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group" style="display: flex; align-items: center; gap: 16px;">
                <div class="sidebar-logo-badge" style="width: 64px; height: 64px; font-size: 24px;">
                    @if($boutique->logo)
                        <img src="{{ asset('storage/' . $boutique->logo) }}" alt="Logo boutique">
                    @else
                        {{ substr($boutique->nom, 0, 1) }}
                    @endif
                </div>
                <div style="flex: 1;">
                    <label for="logo" class="form-label">Logo de la boutique</label>
                    <input type="file" id="logo" name="logo" class="form-input" accept="image/*">
                    @error('logo')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="nom" class="form-label">Nom de la boutique</label>
                <input type="text" id="nom" name="nom" class="form-input" value="{{ old('nom', $boutique->nom) }}" required>
                @error('nom')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" id="telephone" name="telephone" class="form-input" value="{{ old('telephone', $boutique->telephone) }}">
                @error('telephone')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="form-input" value="{{ old('adresse', $boutique->adresse) }}">
                @error('adresse')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="devise" class="form-label">Devise</label>
                    <select id="devise" name="devise" class="form-input">
                        <option value="FCFA" @selected(old('devise', $boutique->devise) == 'FCFA')>FCFA</option>
                        <option value="EUR" @selected(old('devise', $boutique->devise) == 'EUR')>EUR</option>
                        <option value="USD" @selected(old('devise', $boutique->devise) == 'USD')>USD</option>
                    </select>
                    @error('devise')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tva" class="form-label">Taux de TVA (%)</label>
                    <input type="number" id="tva" name="tva" class="form-input" step="0.01" min="0" max="100" value="{{ old('tva', $boutique->tva) }}" required>
                    @error('tva')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="mentions_facture" class="form-label">Mentions légales (factures)</label>
                <textarea id="mentions_facture" name="mentions_facture" class="form-textarea" rows="3" placeholder="Ex : RCCM, NIF, conditions de retour...">{{ old('mentions_facture', $boutique->mentions_facture) }}</textarea>
                @error('mentions_facture')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-action">Enregistrer les modifications</button>
            </div>
        </form>
    </div>

</x-app-layout>