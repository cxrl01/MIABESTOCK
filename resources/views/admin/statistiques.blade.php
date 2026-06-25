<x-admin-layout title="Statistiques globales">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Statistiques globales</h2>
            <p class="page-header-sub">Vue d'ensemble des performances de toutes les boutiques.</p>
        </div>
    </div>

    <!-- FILTRES -->
    <div class="form-card" style="margin-bottom: 20px;">
    <form method="GET" action="{{ route('admin.statistiques.index') }}" style="display: flex; gap: 12px; align-items: end; flex-wrap: wrap;">
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
                        <option value="{{ $m }}" @selected($mois == $m)>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
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
    </form>
    </div>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Chiffre d'affaires global</div>
                    <div class="stat-sub">Toutes boutiques</div>
                </div>
            </div>
            <div class="stat-value">{{ number_format($chiffreAffairesGlobal, 0, ',', ' ') }} F</div>
        </div>

        <div class="stat-card stat-card-green">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Ventes totales</div>
                    <div class="stat-sub">Sur la période</div>
                </div>
            </div>
            <div class="stat-value">{{ $nombreVentesTotal }}</div>
        </div>

        <div class="stat-card stat-card-purple">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Panier moyen</div>
                    <div class="stat-sub">Toutes boutiques confondues</div>
                </div>
            </div>
            <div class="stat-value">{{ number_format($panierMoyenGlobal, 0, ',', ' ') }} F</div>
        </div>
    </div>

    <!-- COURBE D'ÉVOLUTION -->
    <div class="chart-panel" style="margin-bottom: 20px;">
        <div class="panel-header">
            <div>
                <h3>Évolution du chiffre d'affaires global</h3>
                <p class="panel-sub">{{ $periode === 'mois' ? 'Jour par jour' : 'Mois par mois' }}</p>
            </div>
        </div>

        @if(collect($evolution)->sum('total') > 0)
            <div class="mini-chart" style="overflow-x: auto;">
                @php $maxVal = max(collect($evolution)->pluck('total')->max(), 1); @endphp
                @foreach($evolution as $point)
                    <div class="mini-bar-col">
                        <div class="mini-bar" style="height: {{ max(($point['total'] / $maxVal) * 100, 2) }}%;" title="{{ number_format($point['total'], 0, ',', ' ') }} F"></div>
                        <div class="mini-bar-label">{{ $point['label'] }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-chart">
                <p>Aucune vente sur cette période.</p>
            </div>
        @endif
    </div>

    <!-- CLASSEMENT DES BOUTIQUES -->
    <div class="table-card">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-light);">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text);">Classement des boutiques par chiffre d'affaires</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Boutique</th>
                    <th>Ventes</th>
                    <th class="text-right">Chiffre d'affaires</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classementBoutiques as $index => $ligne)
                    <tr>
                        <td class="font-medium">{{ $index + 1 }}</td>
                        <td>
                            <a href="{{ route('admin.boutiques.show', $ligne['boutique']) }}" style="color: var(--accent); font-weight: 600;">
                                {{ $ligne['boutique']->nom }}
                            </a>
                        </td>
                        <td>{{ $ligne['ventes'] }}</td>
                        <td class="text-right font-medium">{{ number_format($ligne['ca'], 0, ',', ' ') }} F</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted text-center">Aucune boutique enregistrée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin-layout>