@extends('layouts.master')

@section('title', 'Liste des Entrées')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="arrow-down-circle"></i></div>
                                Gestion des Entrées de Carburant
                            </h1>
                            <p>
                                <small>Liste des entrées de carburant enregistrées dans le système.</small>
                            </p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('entrees.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-2"></i>&nbsp; Nouvelle Entrée
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>N° Entrée</th>
                                    <th>Date</th>
                                    <th>Cuve</th>
                                    <th>Carburant</th>
                                    <th>Commande</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Montant Total</th>
                                    <th>Enregistré par</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entrees as $entree)
                                    <tr>
                                        <td>{{ $entree->numero_entree }}</td>
                                        <td>{{ $entree->date_entree->format('d/m/Y H:i') }}</td>
                                        <td>{{ $entree->cuve->nom }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $entree->cuve->carburant->nom }}</span>
                                        </td>
                                        <td>
                                            @if ($entree->commande)
                                                <a href="{{ route('commandes.show', $entree->commande) }}">
                                                    {{ $entree->commande->numero_commande }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($entree->quantite, 2) }} L</td>
                                        <td>{{ number_format($entree->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                        <td><strong>{{ number_format($entree->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                                        <td>{{ $entree->user->prenom }} {{ $entree->user->nom }}</td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('entrees.show', $entree) }}" class="btn btn-sm btn-warning"
                                                    title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if (auth()->user()->isAdmin())
                                                    <form action="{{ route('entrees.destroy', $entree) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Attention! La suppression réajustera le stock de la cuve. Continuer ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">Aucune entrée enregistrée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <th colspan="5" class="text-end">Total:</th>
                                    <th>{{ number_format($entrees->sum('quantite'), 2) }} L</th>
                                    <th></th>
                                    <th colspan="3">
                                        <strong>{{ number_format($entrees->sum('montant_total'), 0, ',', ' ') }}
                                            FCFA</strong>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $entrees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
