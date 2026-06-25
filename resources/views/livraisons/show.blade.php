<x-app-layout title="Détail de la livraison">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Livraison {{ $livraison->numero }}</h2>
            <p class="page-header-sub">Reçue de {{ $livraison->fournisseur->nom ?? '—' }} le {{ $livraison->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <a href="{{ route('livraisons.index') }}" class="btn-secondary">← Retour à la liste</a>
    </div>

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
                @foreach($livraison->lignes as $ligne)
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

        <!-- Paiements -->
        <div class="form-card">
            <h3 class="page-header-title" style="font-size: 16px; margin-bottom: 16px;">Paiements au fournisseur</h3>

            @forelse($livraison->paiements as $paiement)
                <div class="paiement-row">
                    <div>
                        <div class="font-medium">{{ $paiement->numero_facture }}</div>
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
                <span>{{ number_format($livraison->total_ttc, 0, ',', ' ') }} F</span>
            </div>
            <div class="paiement-row" style="color: var(--green); font-weight: 700;">
                <span>Payé</span>
                <span>{{ number_format($livraison->total_ttc - $soldeRestant, 0, ',', ' ') }} F</span>
            </div>
            <div class="paiement-row" style="color: {{ $soldeRestant > 0 ? 'var(--red)' : 'var(--text-muted)' }}; font-weight: 700;">
                <span>Reste à payer</span>
                <span>{{ number_format($soldeRestant, 0, ',', ' ') }} F</span>
            </div>

            @if($soldeRestant > 0)
                <form method="POST" action="{{ route('livraisons.paiement.store', $livraison) }}" style="margin-top: 20px;">
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

            @if($livraison->statut === 'soldee')
                <span class="badge-statut badge-statut-soldee">Payée intégralement</span>
            @else
                <span class="badge-statut badge-statut-partielle">Paiement partiel</span>
            @endif

            <form method="POST" action="{{ route('livraisons.destroy', $livraison) }}" onsubmit="return confirm('Annuler cette livraison ? Le stock sera ajusté et la dette fournisseur annulée.');" style="margin-top: 20px;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action" style="width: 100%; justify-content: center; background: var(--red);">
                    Annuler cette livraison
                </button>
            </form>
        </div>

    </div>

</x-app-layout>