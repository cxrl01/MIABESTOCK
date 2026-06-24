<x-app-layout title="Détail de la vente">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Vente {{ $vente->numero }}</h2>
            <p class="page-header-sub">
                {{ $vente->client->nom_complet ?? 'Client anonyme' }} — {{ $vente->created_at->format('d/m/Y à H:i') }}
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('ventes.pdf', $vente) }}" target="_blank" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 15px; height: 15px;"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
                Imprimer Facture
            </a>
            <a href="{{ route('ventes.index') }}" class="btn-secondary">← Retour à la liste</a>
        </div>
    </div>

    @if(session('success'))
        {{-- déjà affiché par le layout, mais on garde la structure --}}
    @endif

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th class="text-right">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vente->lignes as $ligne)
                    <tr>
                        <td class="font-medium">{{ $ligne->produit->nom ?? 'Produit supprimé' }}</td>
                        <td>{{ $ligne->quantite }}</td>
                        <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                        <td class="text-right font-medium">{{ number_format($ligne->sous_total, 0, ',', ' ') }} F</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="vente-show-grid">

        <!-- Récapitulatif paiement -->
        <div class="form-card">
            <h3 class="page-header-title" style="font-size: 16px; margin-bottom: 16px;">Paiements</h3>

            @forelse($vente->paiements as $paiement)
                <div class="paiement-row" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="font-medium">{{ $paiement->numero_facture }}</span>
                            <a href="{{ route('paiements.recu', $paiement) }}" target="_blank" title="Imprimer le reçu" style="color: var(--green); display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
                            </a>
                        </div>
                        <div class="text-muted" style="font-size: 12px;">{{ $paiement->created_at->format('d/m/Y H:i') }} — {{ ucfirst($paiement->mode) }}</div>
                    </div>
                    <div style="color: var(--green); font-weight: 700;">{{ number_format($paiement->montant, 0, ',', ' ') }} F</div>
                </div>
            @empty
                <p class="text-muted" style="font-size: 13px;">Aucun paiement enregistré.</p>
            @endforelse

            <hr style="border: none; border-top: 1px solid var(--border); margin: 16px 0;">

            <div class="paiement-row" style="font-weight: 700;">
                <span>Total</span>
                <span>{{ number_format($vente->total_ttc, 0, ',', ' ') }} F</span>
            </div>
            <div class="paiement-row" style="color: var(--green); font-weight: 700;">
                <span>Payé</span>
                <span>{{ number_format($vente->total_ttc - $soldeRestant, 0, ',', ' ') }} F</span>
            </div>
            <div class="paiement-row" style="color: {{ $soldeRestant > 0 ? 'var(--red)' : 'var(--text-muted)' }}; font-weight: 700;">
                <span>Reste à payer</span>
                <span>{{ number_format($soldeRestant, 0, ',', ' ') }} F</span>
            </div>

            @if($soldeRestant > 0 && $vente->statut !== 'annulee')
                <form method="POST" action="{{ route('ventes.paiement.store', $vente) }}" style="margin-top: 20px;">
                    @csrf
                    <div class="form-group">
                        <label for="montant" class="form-label">Enregistrer un paiement</label>
                        <input type="number" id="montant" name="montant" class="form-input" min="1" max="{{ $soldeRestant }}" step="1" placeholder="Montant" required>
                        @error('montant')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn-action" style="width: 100%; justify-content: center;">
                        Enregistrer le paiement
                    </button>
                </form>
            @endif
        </div>

        <!-- Statut & actions -->
        <div class="form-card">
            <h3 class="page-header-title" style="font-size: 16px; margin-bottom: 16px;">Statut</h3>

            @if($vente->statut === 'soldee')
                <span class="badge-statut badge-statut-soldee">Payée intégralement</span>
            @elseif($vente->statut === 'annulee')
                <span class="badge-statut badge-statut-annulee">Annulée</span>
            @else
                <span class="badge-statut badge-statut-partielle">Paiement partiel</span>
            @endif

            @if($vente->statut !== 'annulee')
                <form method="POST" action="{{ route('ventes.cancel', $vente) }}" onsubmit="return confirm('Annuler cette vente ? Le stock sera restitué et la dette client annulée.');" style="margin-top: 20px;">
                    @csrf
                    <button type="submit" class="btn-action" style="width: 100%; justify-content: center; background: var(--red);">
                        Annuler la vente
                    </button>
                </form>
            @endif
        </div>

    </div>

</x-app-layout>