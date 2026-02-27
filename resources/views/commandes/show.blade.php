@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="shopping-cart"></i></div>
                                Commande {{ $commande->numero_commande }}
                            </h1>
                        </div>
                        <div class="col-auto mt-4">
                            @if ($commande->statut == 'en_attente')
                                <a href="{{ route('commandes.edit', $commande) }}" class="btn btn-warning btn-sm">
                                    <i data-feather="edit" class="me-2"></i>Modifier
                                </a>
                            @endif
                            <a href="{{ route('commandes.index') }}" class="btn btn-light btn-sm">
                                <i data-feather="arrow-left" class="me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Détails de la Commande</div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>N° de Commande:</strong>
                                    <p class="h5">{{ $commande->numero_commande }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Statut:</strong>
                                    <p>
                                        @switch($commande->statut)
                                            @case('en_attente')
                                                <span class="badge bg-warning fs-6">En Attente</span>
                                            @break

                                            @case('validee')
                                                <span class="badge bg-info fs-6">Validée</span>
                                            @break

                                            @case('livree')
                                                <span class="badge bg-success fs-6">Livrée</span>
                                            @break

                                            @case('annulee')
                                                <span class="badge bg-danger fs-6">Annulée</span>
                                            @break
                                        @endswitch
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Station:</strong>
                                    <p>{{ $commande->station->nom }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Carburant:</strong>
                                    <p><span class="badge bg-info">{{ $commande->carburant->nom }}</span></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Quantité:</strong>
                                    <p class="h5 text-primary">{{ number_format($commande->quantite, 0) }} L</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Prix Unitaire:</strong>
                                    <p>{{ number_format($commande->prix_unitaire, 0) }} FCFA</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Montant Total:</strong>
                                    <p class="h5 text-success">{{ number_format($commande->montant_total, 0) }} FCFA</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Fournisseur:</strong>
                                <p>{{ $commande->fournisseur }}</p>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Date de Commande:</strong>
                                    <p>{{ $commande->date_commande->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Date de Livraison Prévue:</strong>
                                    <p>{{ $commande->date_livraison_prevue ? $commande->date_livraison_prevue->format('d/m/Y') : 'Non définie' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Passée par:</strong>
                                <p>{{ $commande->user->prenom }} {{ $commande->user->nom }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    @if ($commande->statut == 'en_attente' || $commande->statut == 'validee')
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark">Actions</div>
                            <div class="card-body">
                                <form action="{{ route('commandes.updateStatut', $commande) }}" method="POST">
                                    @csrf

                                    @if ($commande->statut == 'en_attente')
                                        <button type="submit" name="statut" value="validee"
                                            class="btn btn-info btn-sm w-100 mb-2">
                                            <i data-feather="check-circle"></i>&nbsp; Valider la Commande
                                        </button>
                                        <button type="submit"name="statut" value="annulee"
                                            class="btn btn-danger btn-sm w-100"
                                            onclick="return confirm('Êtes-vous sûr d\'annuler cette commande ?')">
                                            <i data-feather="x-circle"></i>&nbsp; Annuler la Commande
                                        </button>
                                    @endif

                                    @if ($commande->statut == 'validee' && !$commande->entree)
                                        <a href="{{ route('entrees.create') }}?commande_id={{ $commande->id }}"
                                            class="btn btn-success btn-sm w-100">
                                            <i data-feather="arrow-down-circle"></i>&nbsp; Enregistrer la Réception
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header bg-light text-dark">Informations</div>
                        <div class="card-body">
                            <small class="text-muted">
                                <strong>Créée le:</strong><br>
                                {{ $commande->created_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                    </div>

                    @if ($commande->entree)
                        <div class="card mt-4">
                            <div class="card-header bg-light text-dark">Entrée/Réception</div>
                            <div class="card-body">
                                <p><strong>N° Entrée:</strong> {{ $commande->entree->numero_entree }}</p>
                                <p><strong>Date de Réception:</strong>
                                    {{ $commande->entree->date_entree->format('d/m/Y H:i') }}</p>
                                <p><strong>Quantité Reçue:</strong> {{ number_format($commande->entree->quantite, 0) }} L
                                </p>
                                <p><strong>Cuve:</strong> {{ $commande->entree->cuve->nom }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
