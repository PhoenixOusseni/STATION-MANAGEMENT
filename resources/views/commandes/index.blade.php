@extends('layouts.master')

@section('content')
    <header class="page-header page-header-dark pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="shopping-cart"></i></div>
                            Gestion des Commandes
                        </h1>
                        <p>
                            <small class="text-white">Vous pouvez gérer les commandes ici. Tous les champs marqués
                                d'un astérisque (*) sont obligatoires.</small>
                        </p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('commandes.create') }}" class="btn btn-light btn-sm">
                            <i data-feather="plus" class="me-2"></i>Nouvelle Commande
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card-body">
            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body text-center">
                            <h4>{{ $commandes->where('statut', 'en_attente')->count() }}</h4>
                            <p class="mb-0 text-muted">En Attente</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <h4>{{ $commandes->where('statut', 'validee')->count() }}</h4>
                            <p class="mb-0 text-muted">Validées</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h4>{{ $commandes->where('statut', 'livree')->count() }}</h4>
                            <p class="mb-0 text-muted">Livrées</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <h4>{{ $commandes->where('statut', 'annulee')->count() }}</h4>
                            <p class="mb-0 text-muted">Annulées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-striped table-hover table-bordered mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>N°</th>
                                <th>Station</th>
                                <th>Carburant</th>
                                <th>Fournisseur</th>
                                <th>Quantité</th>
                                <th>Montant Total</th>
                                <th>Date</th>
                                <th>Livraison Prévue</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commandes as $commande)
                                <tr>
                                    <td>{{ $commande->numero_commande }}</td>
                                    <td>{{ $commande->station->nom }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $commande->carburant->nom }}</span>
                                    </td>
                                    <td>{{ $commande->fournisseur }}</td>
                                    <td>{{ number_format($commande->quantite, 2) }} L</td>
                                    <td><strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                    <td>{{ $commande->date_commande->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($commande->date_livraison_prevue)
                                            {{ $commande->date_livraison_prevue->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Non définie</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($commande->statut)
                                            @case('en_attente')
                                                <span class="badge bg-warning">En Attente</span>
                                            @break

                                            @case('validee')
                                                <span class="badge bg-info">Validée</span>
                                            @break

                                            @case('livree')
                                                <span class="badge bg-success">Livrée</span>
                                            @break

                                            @case('annulee')
                                                <span class="badge bg-danger">Annulée</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('commandes.show', $commande) }}" class="btn btn-sm btn-info"
                                                title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($commande->statut == 'en_attente')
                                                <a href="{{ route('commandes.edit', $commande) }}"
                                                    class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            @if (auth()->user()->isAdmin() || auth()->user()->isGestionnaire())
                                                <form action="{{ route('commandes.destroy', $commande) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">Aucune commande enregistrée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
