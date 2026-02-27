@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="arrow-down-circle"></i></div>
                                Entrée {{ $entree->numero_entree }}
                            </h1>
                            <p>
                                <small>Détails de l'entrée de carburant enregistrée le
                                    {{ $entree->created_at->format('d/m/Y à H:i') }}.</small>
                            </p>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('entrees.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>&nbsp; Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light text-dark">Détails de l'Entrée</div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>N° d'Entrée:</strong>
                                    <p class="h5">{{ $entree->numero_entree }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Date d'Entrée:</strong>
                                    <p>{{ $entree->date_entree->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Cuve de Destination:</strong>
                                    <p>{{ $entree->cuve->nom }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Carburant:</strong>
                                    <p><span class="badge bg-info fs-6">{{ $entree->cuve->carburant->nom }}</span></p>
                                </div>
                            </div>

                            @if ($entree->commande)
                                <div class="mb-3">
                                    <strong>Commande Associée:</strong>
                                    <p>
                                        <a href="{{ route('commandes.show', $entree->commande) }}">
                                            {{ $entree->commande->numero_commande }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Quantité:</strong>
                                    <p class="h5 text-success">{{ number_format($entree->quantite, 0) }} L</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Prix Unitaire:</strong>
                                    <p>{{ number_format($entree->prix_unitaire, 0) }} FCFA</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Montant Total:</strong>
                                    <p class="h5 text-primary">{{ number_format($entree->montant_total, 0) }} FCFA</p>
                                </div>
                            </div>

                            @if ($entree->numero_bon_livraison)
                                <div class="mb-3">
                                    <strong>N° Bon de Livraison:</strong>
                                    <p>{{ $entree->numero_bon_livraison }}</p>
                                </div>
                            @endif

                            @if ($entree->observation)
                                <div class="mb-3">
                                    <strong>Observation:</strong>
                                    <p>{{ $entree->observation }}</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <strong>Enregistré par:</strong>
                                <p>{{ $entree->user->prenom }} {{ $entree->user->nom }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Impact sur la Cuve</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Stock avant entrée:</strong>
                                <p>{{ number_format($entree->cuve->stock_actuel - $entree->quantite, 0) }} L</p>
                            </div>
                            <div class="mb-3">
                                <strong>Quantité ajoutée:</strong>
                                <p class="text-success">+ {{ number_format($entree->quantite, 0) }} L</p>
                            </div>
                            <div class="mb-3">
                                <strong>Stock actuel:</strong>
                                <p class="h5 text-primary">{{ number_format($entree->cuve->stock_actuel, 0) }} L</p>
                            </div>
                            <div class="progress" style="height: 25px;">
                                @php
                                    $percentage = $entree->cuve->pourcentageRemplissage();
                                    $colorClass =
                                        $percentage <= 20
                                            ? 'bg-danger'
                                            : ($percentage <= 50
                                                ? 'bg-warning'
                                                : 'bg-success');
                                @endphp
                                <div class="progress-bar {{ $colorClass }}" style="width: {{ $percentage }}%">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light text-dark">Informations</div>
                        <div class="card-body">
                            <small class="text-muted">
                                <strong>Enregistrée le:</strong><br>
                                {{ $entree->created_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-light text-dark">Actions</div>
                        <div class="card-body">
                            <a href="{{ route('entrees.print', $entree) }}" target="_blank" class="btn btn-success btn-sm mb-2 w-100">
                                <i data-feather="printer" class="me-2"></i>Imprimer l'Entrée
                            </a>
                            <a href="{{ route('entrees.edit', $entree) }}" class="btn btn-1 btn-sm mb-2 w-100">
                                <i data-feather="edit" class="me-2"></i>Modifier l'Entrée
                            </a>
                            <form action="{{ route('entrees.destroy', $entree) }}" method="POST"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i data-feather="trash-2" class="me-2"></i>Supprimer l'entrée
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
