<x-admin-layout title="Boutiques">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Supervision des boutiques</h2>
            <p class="page-header-sub">Vue d'ensemble de toutes les boutiques MiabéStock.</p>
        </div>
    </div>

    <!-- STATS GLOBALES -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Boutiques</div>
                    <div class="stat-sub">Total enregistrées</div>
                </div>
            </div>
            <div class="stat-value">{{ $totalBoutiques }}</div>
            <div class="stat-trend">{{ $totalBoutiquesActives }} active(s)</div>
        </div>

        <div class="stat-card stat-card-green">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Utilisateurs</div>
                    <div class="stat-sub">Tous rôles confondus</div>
                </div>
            </div>
            <div class="stat-value">{{ $totalUtilisateurs }}</div>
            <div class="stat-trend">Gérants, Gestionnaires, Commerciaux</div>
        </div>

        <div class="stat-card stat-card-purple">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Chiffre d'affaires</div>
                    <div class="stat-sub">Toutes boutiques</div>
                </div>
            </div>
            <div class="stat-value">{{ number_format($totalVentes, 0, ',', ' ') }} F</div>
            <div class="stat-trend">Cumul de toutes les ventes</div>
        </div>
    </div>

    @if($boutiques->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/></svg>
            <h3>Aucune boutique enregistrée</h3>
            <p>Les boutiques apparaîtront ici dès qu'un Gérant s'inscrira sur la plateforme.</p>
        </div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Boutique</th>
                        <th>Utilisateurs</th>
                        <th>Produits</th>
                        <th>Clients</th>
                        <th>Statut</th>
                        <th>Créée le</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($boutiques as $boutique)
                        <tr>
                            <td class="font-medium">
                                <a href="{{ route('admin.boutiques.show', $boutique) }}" style="color: var(--accent);">
                                    {{ $boutique->nom }}
                                </a>
                            </td>
                            <td>{{ $boutique->users_count }}</td>
                            <td>{{ $boutique->produits_count }}</td>
                            <td>{{ $boutique->clients_count }}</td>
                            <td>
                                @if($boutique->statut === 'active')
                                    <span class="badge-statut badge-statut-soldee">Active</span>
                                @elseif($boutique->statut === 'suspendue')
                                    <span class="badge-statut badge-statut-partielle">Suspendue</span>
                                @else
                                    <span class="badge-statut badge-statut-annulee">Supprimée</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $boutique->created_at->format('d/m/Y') }}</td>
                            <td class="text-right">
                                <div class="row-actions">
                                    @if($boutique->statut === 'active')
                                        <form method="POST" action="{{ route('admin.boutiques.suspend', $boutique) }}" onsubmit="return confirm('Suspendre cette boutique ? Ses utilisateurs ne pourront plus se connecter.');" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">Suspendre</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.boutiques.reactivate', $boutique) }}" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-action" style="padding: 6px 12px; font-size: 12px;">Réactiver</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.boutiques.destroy', $boutique) }}" onsubmit="return confirm('Supprimer définitivement cette boutique et toutes ses données ?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn-sm icon-btn-danger" title="Supprimer">
                                            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>