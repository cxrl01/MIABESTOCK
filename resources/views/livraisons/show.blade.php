<x-app-layout title="Détail de la livraison">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Livraison {{ $livraison->numero }}</h2>
            <p class="page-header-sub">Reçue de {{ $livraison->fournisseur->nom ?? '—' }} le {{ $livraison->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <a href="{{ route('livraisons.index') }}" class="btn-secondary">
            ← Retour à la liste
        </a>
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

    <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
        <div class="stat-card" style="min-width: 240px;">
            <div class="stat-label">Total de la livraison</div>
            <div class="stat-value">{{ number_format($livraison->total_ttc, 0, ',', ' ') }} F</div>
        </div>
    </div>

    <div class="form-actions" style="margin-top: 24px;">
        <form method="POST" action="{{ route('livraisons.destroy', $livraison) }}" onsubmit="return confirm('Annuler cette livraison ? Le stock sera ajusté en conséquence.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action" style="background: var(--red);">
                Annuler cette livraison
            </button>
        </form>
    </div>

</x-app-layout>