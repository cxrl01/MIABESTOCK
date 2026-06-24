<x-app-layout title="Dashboard">

    <!-- CARTES STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Ventes</div>
                    <div class="stat-sub">Aujourd'hui</div>
                </div>
                <div class="stat-icon-badge">
                    <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
            </div>
            <div class="stat-value">{{ $ventesToday ?? 0 }}</div>
            <div class="stat-trend">
                @if(($ventesToday ?? 0) > 0)
                    transaction{{ ($ventesToday ?? 0) > 1 ? 's' : '' }} aujourd'hui
                @else
                    Aucune vente enregistrée
                @endif
            </div>
        </div>

        <div class="stat-card stat-card-green">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">CA aujourd'hui</div>
                    <div class="stat-sub">Chiffre d'affaires</div>
                </div>
                <div class="stat-icon-badge stat-icon-green">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($caToday ?? 0, 0, ',', ' ') }} F</div>
            <div class="stat-trend">
                CA du mois : {{ number_format($caMois ?? 0, 0, ',', ' ') }} F
            </div>
        </div>

        <div class="stat-card stat-card-purple">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Clients</div>
                    <div class="stat-sub">Total enregistrés</div>
                </div>
                <div class="stat-icon-badge stat-icon-purple">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
            <div class="stat-value">{{ $totalClients ?? 0 }}</div>
            <div class="stat-trend">
                {{ ($totalClients ?? 0) > 0 ? 'Clients dans le répertoire' : 'Aucun client enregistré' }}
            </div>
        </div>

        <div class="stat-card" style="border-top:3px solid var(--red);">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Dépenses</div>
                    <div class="stat-sub">Ce mois</div>
                </div>
                <div class="stat-icon-badge" style="background:rgba(239,68,68,.12);color:var(--red);">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="stat-value" style="color:var(--red);">{{ number_format($depensesMois ?? 0, 0, ',', ' ') }} F</div>
            <div class="stat-trend">Charges du mois en cours</div>
        </div>
    </div>

    <!-- GRAPHIQUE + ALERTES -->
    <div class="content-row">
        <div class="chart-panel">
            <div class="panel-header">
                <div>
                    <h3>Dernières ventes</h3>
                    <p class="panel-sub">5 transactions les plus récentes</p>
                </div>
                <a href="{{ route('ventes.index') }}" style="font-size:13px;color:var(--accent);font-weight:600;text-decoration:none;">Voir tout →</a>
            </div>

            @if(isset($dernieresVentes) && $dernieresVentes->isNotEmpty())
                <div style="display:flex;flex-direction:column;gap:10px;margin-top:8px;">
                    @foreach($dernieresVentes as $vente)
                        @php $paye = $vente->paiements->sum('montant'); $reste = $vente->total_ttc - $paye; @endphp
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-radius:10px;background:var(--surface);border:1px solid var(--border-light);">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="avatar-circle" style="width:34px;height:34px;font-size:13px;">
                                    {{ strtoupper(substr($vente->client->nom_complet ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13px;color:var(--text);">{{ $vente->client->nom_complet ?? 'Client anonyme' }}</div>
                                    <div style="font-size:11px;color:var(--text-sec);">{{ $vente->numero }} • {{ $vente->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-weight:700;font-size:14px;color:var(--text);">{{ number_format($vente->total_ttc, 0, ',', ' ') }} F</div>
                                @if($vente->statut === 'soldee')
                                    <span class="badge-statut badge-statut-soldee">Soldée</span>
                                @elseif($vente->statut === 'annulee')
                                    <span class="badge-statut badge-statut-annulee">Annulée</span>
                                @else
                                    <span class="badge-statut badge-statut-partielle">{{ number_format($reste, 0, ',', ' ') }} F restant</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-chart">
                    <p>Les dernières ventes apparaîtront ici dès vos premières transactions.</p>
                </div>
            @endif
        </div>

        <div class="alerts-panel">
            <div class="panel-header">
                <div>
                    <h3>Alertes stock</h3>
                    <p class="panel-sub">Produits en stock critique</p>
                </div>
                <a href="{{ route('produits.index') }}" style="font-size:13px;color:var(--accent);font-weight:600;text-decoration:none;">Voir tout →</a>
            </div>

            @if(isset($alertesStock) && $alertesStock->isNotEmpty())
                <div class="alerts-list">
                    @foreach($alertesStock as $produit)
                        <div class="alert-item alert-item-warning">
                            <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            <div>
                                <div class="alert-item-title">Stock critique — {{ $produit->nom }}</div>
                                <div class="alert-item-sub">Reste {{ $produit->quantite_stock }} unité(s) · Seuil : {{ $produit->seuil_alerte }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-alerts">
                    <svg viewBox="0 0 24 24" style="width:40px;height:40px;color:var(--green);margin-bottom:8px;"><polyline points="20 6 9 17 4 12"/></svg>
                    <p style="color:var(--text-sec);font-size:13px;">Tous les stocks sont suffisants.</p>
                </div>
            @endif

            <!-- Liens rapides -->
            <div style="margin-top:20px;">
                <div class="panel-header" style="margin-bottom:10px;">
                    <h3 style="font-size:14px;">Accès rapide</h3>
                </div>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <a href="{{ route('ventes.create') }}" class="btn-action" style="justify-content:center;font-size:13px;">
                        <svg viewBox="0 0 24 24" style="width:14px;height:14px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Nouvelle vente
                    </a>
                    <a href="{{ route('livraisons.create') }}" class="btn btn-secondary" style="justify-content:center;font-size:13px;display:flex;align-items:center;gap:6px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        Nouvelle livraison
                    </a>
                    <a href="{{ route('depenses.create') }}" class="btn btn-secondary" style="justify-content:center;font-size:13px;display:flex;align-items:center;gap:6px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        Enregistrer une dépense
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>