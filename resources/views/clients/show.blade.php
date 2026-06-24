<x-app-layout title="Détail du client">

    <div class="page-header">
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="{{ route('clients.index') }}" class="btn btn-secondary" style="padding: 8px 12px; display: inline-flex; align-items: center; justify-content: center; height: 38px; width: 38px; border-radius: 50%;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px;"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <div>
                <h2 class="page-header-title">{{ $client->nom_complet }}</h2>
                <p class="page-header-sub">Fiche client et historique complet des transactions</p>
            </div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('clients.edit', $client) }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Modifier le profil
            </a>
        </div>
    </div>

    <!-- Info & Stats grid -->
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; margin-bottom: 24px; align-items: start;">
        
        <!-- Profile Card -->
        <div class="form-card" style="margin-top: 0; padding: 24px; display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
                <div class="avatar-circle" style="width: 60px; height: 60px; font-size: 24px; border-radius: 14px; background: var(--accent-light); color: var(--accent);">
                    {{ substr($client->nom_complet, 0, 1) }}
                </div>
                <div>
                    <h3 style="font-size: 17px; font-weight: 700; color: var(--text); margin-bottom: 4px;">{{ $client->nom_complet }}</h3>
                    <span class="badge-status {{ $client->solde_dette > 0 ? 'warning' : 'active' }}">
                        {{ $client->solde_dette > 0 ? 'Dette Impayée' : 'À jour' }}
                    </span>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13.5px;">
                <div>
                    <span style="color: var(--text-sec); display: block; font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Téléphone</span>
                    <span style="font-weight: 500; color: var(--text);">{{ $client->telephone ?? '—' }}</span>
                </div>
                <div>
                    <span style="color: var(--text-sec); display: block; font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Email</span>
                    <span style="font-weight: 500; color: var(--text);">{{ $client->email ?? '—' }}</span>
                </div>
                <div>
                    <span style="color: var(--text-sec); display: block; font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Adresse</span>
                    <span style="font-weight: 500; color: var(--text);">{{ $client->adresse ?? '—' }}</span>
                </div>
                <div>
                    <span style="color: var(--text-sec); display: block; font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Date d'inscription</span>
                    <span style="font-weight: 500; color: var(--text);">{{ $client->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 0;">
                
                <div class="stat-card-horizontal">
                    <div class="stat-icon-container accent">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
                    <div class="stat-details">
                        <span class="stat-number">{{ number_format($totalAchete, 0, ',', ' ') }} F</span>
                        <span class="stat-label">Total acheté</span>
                    </div>
                </div>

                <div class="stat-card-horizontal">
                    <div class="stat-icon-container green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"/><line x1="12" y1="18" x2="12" y2="18"/><path d="M17 9h.01M17 13h.01"/></svg>
                    </div>
                    <div class="stat-details">
                        <span class="stat-number" style="color: var(--green);">{{ number_format($totalPaye, 0, ',', ' ') }} F</span>
                        <span class="stat-label">Total réglé</span>
                    </div>
                </div>

                <div class="stat-card-horizontal">
                    <div class="stat-icon-container red">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div class="stat-details">
                        <span class="stat-number" style="color: var(--red);">{{ number_format($client->solde_dette, 0, ',', ' ') }} F</span>
                        <span class="stat-label">Reste à payer</span>
                    </div>
                </div>

            </div>

            <!-- Tab Buttons -->
            <div style="display: flex; gap: 4px; border-bottom: 2px solid var(--border-light); padding-bottom: 0; margin-top: 10px;">
                <button type="button" id="tab-ventes-btn" class="tab-btn active" style="padding: 10px 20px; font-size: 14px; font-weight: 700; background: none; border: none; border-bottom: 3px solid var(--accent); color: var(--accent); cursor: pointer; transition: all 0.2s;">
                    Achats & Ventes ({{ $ventes->count() }})
                </button>
                <button type="button" id="tab-paiements-btn" class="tab-btn" style="padding: 10px 20px; font-size: 14px; font-weight: 600; background: none; border: none; border-bottom: 3px solid transparent; color: var(--text-sec); cursor: pointer; transition: all 0.2s;">
                    Historique des Paiements ({{ $paiements->count() }})
                </button>
            </div>
        </div>

    </div>

    <!-- Ventes / Purchases Section -->
    <div id="section-ventes" class="tab-section">
        @if($ventes->isEmpty())
            <div class="empty-state" style="padding: 40px; background: var(--card); border: 1px solid var(--border); border-radius: var(--radius);">
                <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <h3>Aucun achat enregistré</h3>
                <p>Ce client n'a effectué aucune transaction pour le moment.</p>
                <a href="{{ route('ventes.create', ['client_id' => $client->id]) }}" class="btn-action" style="margin-top: 12px;">Enregistrer une vente</a>
            </div>
        @else
            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>N° Vente</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Payé</th>
                            <th>Reste</th>
                            <th>Statut</th>
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
                                <td>
                                    <a href="{{ route('ventes.show', $vente) }}" class="font-medium" style="color: var(--accent); text-decoration: none; font-weight: 700;">
                                        {{ $vente->numero }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $vente->created_at->format('d/m/Y H:i') }}</td>
                                <td class="font-medium">{{ number_format($vente->total_ttc, 0, ',', ' ') }} F</td>
                                <td style="color: var(--green); font-weight: 600;">{{ number_format($paye, 0, ',', ' ') }} F</td>
                                <td>
                                    @if($reste > 0)
                                        <span style="color: var(--red); font-weight: 600;">{{ number_format($reste, 0, ',', ' ') }} F</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vente->statut === 'soldee')
                                        <span class="badge-status active">Soldée</span>
                                    @elseif($vente->statut === 'annulee')
                                        <span class="badge-status inactive">Annulée</span>
                                    @else
                                        <span class="badge-status warning">Partielle</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                        <a href="{{ route('ventes.show', $vente) }}" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px; height: 30px; display: inline-flex; align-items: center; gap: 4px;">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 12px; height: 12px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            Consulter
                                        </a>
                                        <a href="{{ route('ventes.pdf', $vente) }}" target="_blank" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px; height: 30px; display: inline-flex; align-items: center; gap: 4px;">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 12px; height: 12px;"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
                                            Facture
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Payments Section -->
    <div id="section-paiements" class="tab-section" style="display: none;">
        @if($paiements->isEmpty())
            <div class="empty-state" style="padding: 40px; background: var(--card); border: 1px solid var(--border); border-radius: var(--radius);">
                <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"/><line x1="12" y1="18" x2="12" y2="18"/><path d="M17 9h.01M17 13h.01"/></svg>
                <h3>Aucun paiement enregistré</h3>
                <p>Aucune écriture de règlement n'a été saisie pour ce client.</p>
            </div>
        @else
            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>N° Reçu / Facture</th>
                            <th>Date</th>
                            <th>Mode de paiement</th>
                            <th>Montant réglé</th>
                            <th>Vente concernée</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paiements as $paiement)
                            <tr>
                                <td class="font-medium">{{ $paiement->numero_facture }}</td>
                                <td class="text-muted">{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge-status active" style="background: var(--bg); color: var(--text-sec); border: 1px solid var(--border);">
                                        @if($paiement->mode === 'especes')
                                            💵 Espèces
                                        @elseif($paiement->mode === 'mobile_money')
                                            📱 Mobile Money
                                        @elseif($paiement->mode === 'cheque')
                                            📄 Chèque
                                        @elseif($paiement->mode === 'virement')
                                            🏦 Virement
                                        @else
                                            {{ ucfirst($paiement->mode) }}
                                        @endif
                                    </span>
                                </td>
                                <td style="color: var(--green); font-weight: 700; font-size: 14.5px;">
                                    {{ number_format($paiement->montant, 0, ',', ' ') }} F
                                </td>
                                <td>
                                    @if($paiement->commande)
                                        <a href="{{ route('ventes.show', $paiement->commande) }}" style="color: var(--accent); text-decoration: none; font-weight: 600;">
                                            {{ $paiement->commande->numero }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('paiements.recu', $paiement) }}" target="_blank" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px; height: 30px; display: inline-flex; align-items: center; gap: 4px; color: var(--green); border-color: rgba(46, 204, 113, 0.2); background: rgba(46, 204, 113, 0.04);">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 12px; height: 12px;"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
                                        Générer reçu
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- JS for tabs switcher -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabVentes = document.getElementById('tab-ventes-btn');
            const tabPaiements = document.getElementById('tab-paiements-btn');
            const sectionVentes = document.getElementById('section-ventes');
            const sectionPaiements = document.getElementById('section-paiements');

            tabVentes.addEventListener('click', function() {
                tabVentes.classList.add('active');
                tabVentes.style.borderBottomColor = 'var(--accent)';
                tabVentes.style.color = 'var(--accent)';

                tabPaiements.classList.remove('active');
                tabPaiements.style.borderBottomColor = 'transparent';
                tabPaiements.style.color = 'var(--text-sec)';

                sectionVentes.style.display = 'block';
                sectionPaiements.style.display = 'none';
            });

            tabPaiements.addEventListener('click', function() {
                tabPaiements.classList.add('active');
                tabPaiements.style.borderBottomColor = 'var(--accent)';
                tabPaiements.style.color = 'var(--accent)';

                tabVentes.classList.remove('active');
                tabVentes.style.borderBottomColor = 'transparent';
                tabVentes.style.color = 'var(--text-sec)';

                sectionVentes.style.display = 'none';
                sectionPaiements.style.display = 'block';
            });
        });
    </script>

</x-app-layout>
