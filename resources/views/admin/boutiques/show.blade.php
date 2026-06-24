<x-admin-layout title="Détail boutique">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">{{ $boutique->nom }}</h2>
            <p class="page-header-sub">Créée le {{ $boutique->created_at->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('admin.boutiques.index') }}" class="btn-secondary">← Retour à la liste</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Produits</div>
                </div>
            </div>
            <div class="stat-value">{{ $nombreProduits }}</div>
        </div>

        <div class="stat-card stat-card-green">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Clients</div>
                </div>
            </div>
            <div class="stat-value">{{ $nombreClients }}</div>
        </div>

        <div class="stat-card stat-card-purple">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Ventes</div>
                </div>
            </div>
            <div class="stat-value">{{ $nombreVentes }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Chiffre d'affaires</div>
                </div>
            </div>
            <div class="stat-value">{{ number_format($chiffreAffaires, 0, ',', ' ') }} F</div>
        </div>
    </div>

    <div class="table-card">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-light);">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text);">Équipe de la boutique</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($boutique->users as $user)
                    <tr>
                        <td class="font-medium">{{ $user->name }}</td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            <span class="badge-count">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            @if($user->est_actif)
                                <span class="badge-statut badge-statut-soldee">Actif</span>
                            @else
                                <span class="badge-statut badge-statut-annulee">Désactivé</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="form-actions" style="margin-top: 24px;">
        @if($boutique->statut === 'active')
            <form method="POST" action="{{ route('admin.boutiques.suspend', $boutique) }}" onsubmit="return confirm('Suspendre cette boutique ?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-secondary">Suspendre cette boutique</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.boutiques.reactivate', $boutique) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-action">Réactiver cette boutique</button>
            </form>
        @endif

        <form method="POST" action="{{ route('admin.boutiques.destroy', $boutique) }}" onsubmit="return confirm('Supprimer définitivement cette boutique et toutes ses données ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action" style="background: var(--red);">Supprimer définitivement</button>
        </form>
    </div>

</x-admin-layout>