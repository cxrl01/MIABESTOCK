<x-app-layout title="Dettes clients">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Dettes clients</h2>
            <p class="page-header-sub">Clients ayant un solde restant à payer.</p>
        </div>
        <a href="{{ route('clients.index') }}" class="btn-secondary">← Retour aux clients</a>
    </div>

    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card" style="border-top-color: var(--red);">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Total des dettes</div>
                    <div class="stat-sub">Toutes boutiques confondues</div>
                </div>
            </div>
            <div class="stat-value" style="color: var(--red);">{{ number_format($totalDettes, 0, ',', ' ') }} F</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Clients endettés</div>
                    <div class="stat-sub">Nombre de clients</div>
                </div>
            </div>
            <div class="stat-value">{{ $clients->count() }}</div>
        </div>
    </div>

    @if($clients->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <h3>Aucune dette en cours</h3>
            <p>Tous vos clients sont à jour dans leurs paiements.</p>
        </div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Dette</th>
                        <th>Depuis</th>
                        <th class="text-right">Relancer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>
                                <a href="{{ route('clients.show', $client) }}" class="font-medium" style="color: var(--accent); text-decoration: none;">
                                    {{ $client->nom_complet }}
                                </a>
                            </td>
                            <td class="text-muted">{{ $client->telephone ?? '—' }}</td>
                            <td class="text-muted">{{ $client->email ?? '—' }}</td>
                            <td>
                                <span class="badge-statut badge-statut-annulee">{{ number_format($client->solde_dette, 0, ',', ' ') }} F</span>
                            </td>
                            <td class="text-muted">
                                {{ $client->depuis ? $client->depuis->format('d/m/Y') : '—' }}
                                @if($client->depuis)
                                    <div style="font-size: 11px;">({{ $client->depuis->diffForHumans() }})</div>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="row-actions" style="justify-content: flex-end;">
                                    @if($client->telephone)
                                        <!-- <a href="tel:{{ $client->telephone }}" class="icon-btn-sm" title="Appeler">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        </a> -->
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->telephone) }}?text={{ urlencode('Bonjour ' . $client->nom_complet . ', vous avez un solde de ' . number_format($client->solde_dette, 0, ',', ' ') . ' F à régler chez ' . (auth()->user()->boutique->nom ?? 'notre boutique') . '. Merci de bien vouloir régulariser. Cordialement.') }}" target="_blank" class="icon-btn-sm" title="WhatsApp" style="color: #25D366;">
                                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.6 6.32A8.86 8.86 0 0 0 12.05 4a8.94 8.94 0 0 0-7.74 13.4L3 21l3.73-1.27a8.93 8.93 0 0 0 4.27 1.1h.01a8.94 8.94 0 0 0 8.93-8.94 8.84 8.84 0 0 0-2.34-5.57zM12.05 19.1a7.4 7.4 0 0 1-3.78-1.04l-.27-.16-2.8.96.93-2.73-.18-.28a7.4 7.4 0 0 1-1.13-3.94 7.43 7.43 0 1 1 14.86 0 7.44 7.44 0 0 1-7.43 7.43zm4.07-5.56c-.22-.11-1.3-.64-1.5-.71-.2-.07-.35-.11-.5.11-.15.22-.57.71-.7.86-.13.15-.26.16-.48.05-.22-.11-1-.37-1.92-1.18a7.2 7.2 0 0 1-1.33-1.65c-.14-.24-.01-.37.1-.48.11-.11.25-.29.37-.43.12-.14.16-.24.24-.4.08-.16.04-.3-.03-.41-.07-.11-.62-1.49-.85-2.04-.22-.53-.45-.46-.62-.47-.16-.01-.34-.01-.52-.01-.18 0-.47.07-.72.34-.25.27-.95.93-.95 2.27 0 1.34.97 2.63 1.11 2.81.14.18 1.86 2.84 4.51 3.87 2.66 1.03 2.66.69 3.14.65.48-.04 1.55-.63 1.77-1.24.22-.61.22-1.13.16-1.24-.07-.11-.22-.16-.44-.27z"/></svg>
                                        </a>
                                    @endif
                                    @if($client->email)
                                        <a href="mailto:{{ $client->email }}?subject={{ urlencode('Rappel de paiement') }}&body={{ urlencode('Bonjour ' . $client->nom_complet . ',' . "\n\n" . 'Vous avez un solde de ' . number_format($client->solde_dette, 0, ',', ' ') . ' F à régler chez ' . (auth()->user()->boutique->nom ?? 'notre boutique') . '. Merci de bien vouloir régulariser dans les meilleurs délais.' . "\n\n" . 'Cordialement.') }}" class="icon-btn-sm" title="Email">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                        </a>
                                    @endif
                                    @if(!$client->telephone && !$client->email)
                                        <span class="text-muted" style="font-size: 12px;">Aucun contact</span>
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