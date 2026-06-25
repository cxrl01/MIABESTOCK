<x-app-layout title="Ventes">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Historique des ventes</h2>
            <p class="page-header-sub">Toutes les transactions enregistrées.</p>
        </div>
        @if(!auth()->user()->estGestionnaire())
            <a href="{{ route('ventes.create') }}" class="btn-action">
                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouvelle vente
            </a>
        @endif
    </div>

    @php
        $ventesAujourdhui = $ventes->filter(fn($v) => $v->created_at->isToday() && $v->statut !== 'annulee');
        $caAujourdhui = $ventesAujourdhui->sum('total_ttc');
        $caCeMois = $ventes->filter(fn($v) => $v->created_at->isSameMonth(now()) && $v->statut !== 'annulee')->sum('total_ttc');
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Ventes aujourd'hui</div>
                    <div class="stat-sub">Transactions</div>
                </div>
            </div>
            <div class="stat-value">{{ $ventesAujourdhui->count() }}</div>
        </div>

        <div class="stat-card stat-card-green">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">CA aujourd'hui</div>
                    <div class="stat-sub">Chiffre d'affaires</div>
                </div>
            </div>
            <div class="stat-value">{{ number_format($caAujourdhui, 0, ',', ' ') }} F</div>
        </div>

        <div class="stat-card stat-card-purple">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">CA ce mois</div>
                    <div class="stat-sub">{{ now()->translatedFormat('F Y') }}</div>
                </div>
            </div>
            <div class="stat-value">{{ number_format($caCeMois, 0, ',', ' ') }} F</div>
        </div>
    </div>

    @if($ventes->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <h3>Aucune vente pour le moment</h3>
            <p>Enregistrez votre première vente depuis le point de vente.</p>
            @if(!auth()->user()->estGestionnaire())
                <a href="{{ route('ventes.create') }}" class="btn-action">Nouvelle vente</a>
            @endif
        </div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>N° Vente</th>
                        <th>Client</th>
                        <th>Montant total</th>
                        <th>Montant payé</th>
                        <th>Reste</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventes as $vente)
                        @php
                            $paye = $vente->paiements->sum('montant');
                            $reste = $vente->total_ttc - $paye;
                        @endphp
                        <tr>
                            <td class="font-medium">{{ $vente->numero }}</td>
                            <td class="text-muted">{{ $vente->client->nom_complet ?? 'Client anonyme' }}</td>
                            <td>{{ number_format($vente->total_ttc, 0, ',', ' ') }} F</td>
                            <td style="color: var(--green); font-weight: 600;">{{ number_format($paye, 0, ',', ' ') }} F</td>
                            <td style="color: {{ $reste > 0 ? 'var(--red)' : 'var(--text-muted)' }}; font-weight: 600;">
                                {{ number_format($reste, 0, ',', ' ') }} F
                            </td>
                            <td>
                                @if($vente->statut === 'soldee')
                                    <span class="badge-statut badge-statut-soldee">Payée</span>
                                @elseif($vente->statut === 'annulee')
                                    <span class="badge-statut badge-statut-annulee">Annulée</span>
                                @else
                                    <span class="badge-statut badge-statut-partielle">Partielle</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $vente->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-right">
                                <div class="row-actions">
                                    <a href="{{ route('ventes.show', $vente) }}" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                        Voir
                                    </a>
                                    @if($vente->statut !== 'annulee' && auth()->user()->estGerant())
                                        <form method="POST" action="{{ route('ventes.cancel', $vente) }}" onsubmit="return confirm('Annuler cette vente ? Le stock sera restitué.');" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; color: var(--red); font-size: 12px; font-weight: 600; cursor: pointer;">
                                                Annuler
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>