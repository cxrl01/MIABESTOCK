<x-app-layout title="Catégories">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Catégories de produits</h2>
            <p class="page-header-sub">Organisez vos produits par catégorie.</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Retour
            </a>
            <a href="{{ route('categories.create') }}" class="btn-action">
                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouvelle catégorie
            </a>
        </div>
    </div>

    @if($categories->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 3H8a2 2 0 0 0-2 2v2h12V5a2 2 0 0 0-2-2z"/></svg>
            <h3>Aucune catégorie pour le moment</h3>
            <p>Créez votre première catégorie pour commencer à organiser vos produits.</p>
            <a href="{{ route('categories.create') }}" class="btn-action">Créer une catégorie</a>
        </div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Produits</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $categorie)
                        <tr>
                            <td class="font-medium">{{ $categorie->nom }}</td>
                            <td class="text-muted">{{ $categorie->description ?? '—' }}</td>
                            <td>
                                <span class="badge-count">{{ $categorie->produits_count }}</span>
                            </td>
                            <td class="text-right">
                                <div class="row-actions">
                                    <a href="{{ route('categories.edit', $categorie) }}" class="icon-btn-sm" title="Modifier">
                                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('categories.destroy', $categorie) }}" onsubmit="return confirm('Supprimer cette catégorie ?');">
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

</x-app-layout>