<x-app-layout title="Rapports & Statistiques">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Rapports & Statistiques</h2>
            <p class="page-header-sub">Analysez les performances de votre boutique.</p>
        </div>
    </div>

    <div class="form-card" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('rapports.index') }}" id="filtreForm">
            <div style="display: flex; gap: 12px; align-items: end; flex-wrap: wrap;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Période</label>
                    <select name="periode" class="form-input" onchange="this.form.submit()">
                        <option value="mois" @selected($periode === 'mois')>Par mois</option>
                        <option value="annee" @selected($periode === 'annee')>Par année</option>
                        <option value="personnalise" @selected($periode === 'personnalise')>Période personnalisée</option>
                    </select>
                </div>

                @if($periode === 'mois')
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Mois</label>
                        <select name="mois" class="form-input">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" @selected($mois == $m)>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Année</label>
                        <select name="annee" class="form-input">
                            @foreach($anneesDisponibles as $a)
                                <option value="{{ $a }}" @selected($annee == $a)>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($periode === 'annee')
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Année</label>
                        <select name="annee" class="form-input">
                            @foreach($anneesDisponibles as $a)
                                <option value="{{ $a }}" @selected($annee == $a)>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Du</label>
                        <input type="date" name="date_debut" class="form-input" value="{{ $dateDebut }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Au</label>
                        <input type="date" name="date_fin" class="form-input" value="{{ $dateFin }}">
                    </div>
                @endif

                <button type="submit" class="btn-action">Filtrer</button>
            </div>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <div class="form-card" style="padding: 16px;">
            <div style="font-size: 0.85rem; color: #666;">Chiffre d'affaires</div>
            <div style="font-size: 1.5rem; font-weight: bold; margin-top: 4px;">{{ number_format($chiffreAffaires, 0, ',', ' ') }} F</div>
            <div style="font-size: 0.8rem; color: #999; margin-top: 4px;">{{ $nombreVentes }} vente(s)</div>
        </div>
        <div class="form-card" style="padding: 16px;">
            <div style="font-size: 0.85rem; color: #666;">Marge brute</div>
            <div style="font-size: 1.5rem; font-weight: bold; margin-top: 4px; color: #2e7d32;">{{ number_format($marge, 0, ',', ' ') }} F</div>
            <div style="font-size: 0.8rem; color: #2e7d32; margin-top: 4px;">Taux : {{ number_format($margePourcent, 1) }}%</div>
        </div>
        <div class="form-card" style="padding: 16px;">
            <div style="font-size: 0.85rem; color: #666;">Panier moyen</div>
            <div style="font-size: 1.5rem; font-weight: bold; margin-top: 4px;">{{ number_format($panierMoyen, 0, ',', ' ') }} F</div>
        </div>
        <div class="form-card" style="padding: 16px;">
            <div style="font-size: 0.85rem; color: #666;">Dépenses</div>
            <div style="font-size: 1.5rem; font-weight: bold; margin-top: 4px; color: #c62828;">{{ number_format($totalDepenses, 0, ',', ' ') }} F</div>
        </div>
    </div>

    <div class="chart-panel" style="margin-bottom: 20px;">
        <div class="panel-header">
            <div>
                <h3>Évolution du chiffre d'affaires</h3>
                <p class="panel-sub">{{ $periode === 'mois' ? 'Jour par jour' : 'Mois par mois' }}</p>
            </div>
        </div>

        @if(collect($evolution)->sum('total') > 0)
            <div class="mini-chart" style="overflow-x: auto; display: flex; align-items: end; height: 200px; gap: 8px; padding-top: 20px;">
                @php $maxVal = max(collect($evolution)->pluck('total')->max(), 1); @endphp
                @foreach($evolution as $point)
                    <div class="mini-bar-col" style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%;">
                        <div class="mini-bar" style="height: {{ max(($point['total'] / $maxVal) * 100, 2) }}%; width: 100%; background-color: #3b82f6; border-radius: 4px 4px 0 0;" title="{{ number_format($point['total'], 0, ',', ' ') }} F"></div>
                        <div class="mini-bar-label" style="font-size: 10px; margin-top: 4px; color: #666;">{{ $point['label'] }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-chart">
                <p>Aucune vente sur cette période.</p>
            </div>
        @endif
    </div>

    <div class="content-row">
        <div class="chart-panel">
            <div class="panel-header">
                <div>
                    <h3>Top 5 produits</h3>
                    <p class="panel-sub">Les plus vendus sur la période</p>
                </div>
            </div>

            @if($topProduits->isEmpty())
                <div class="empty-chart"><p>Aucune vente sur cette période.</p></div>
            @else
                <table class="data-table">
                    <thead>
                        <tr><th>Produit</th><th>Quantité</th><th class="text-right">CA généré</th></tr>
                    </thead>
                    <tbody>
                        @foreach($topProduits as $p)
                            <tr>
                                <td class="font-medium">{{ $p->nom }}</td>
                                <td>{{ $p->total_quantite }}</td>
                                <td class="text-right">{{ number_format($p->total_ventes, 0, ',', ' ') }} F</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="alerts-panel">
            <div class="panel-header">
                <div>
                    <h3>Top 5 clients</h3>
                    <p class="panel-sub">Sur la période</p>
                </div>
            </div>

            @if($topClients->isEmpty())
                <div class="empty-alerts"><p>Aucun client sur cette période.</p></div>
            @else
                <div class="alerts-list">
                    @foreach($topClients as $c)
                        <div class="alert-item alert-item-info">
                            <div>
                                <div class="alert-item-title">{{ $c['client']->nom_complet ?? ($c['client']->nom ?? 'Client anonyme') }}</div>
                                <div class="alert-item-sub">{{ $c['nombre'] }} achat(s) — {{ number_format($c['total'], 0, ',', ' ') }} F</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-app-layout>