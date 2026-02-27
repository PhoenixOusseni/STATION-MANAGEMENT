@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="dollar-sign" class="text-white"></i></div>
                                Vente {{ $vente->numero_vente }}
                            </h1>
                            <p class="text-white">
                                <small>Détails de la vente de carburant enregistrée le
                                    {{ $vente->created_at->format('d/m/Y à H:i') }}.</small>
                            </p>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('ventes.index') }}" class="btn btn-light btn-sm">
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
                        <div class="card-header bg-light text-dark">Détails de la Vente</div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>N° de Vente:</strong>
                                    <p class="h5">{{ $vente->numero_vente }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Date de Vente:</strong>
                                    <p>{{ $vente->date_vente->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>

                            @if ($vente->numero_ticket)
                                <div class="mb-3">
                                    <strong>N° de Ticket:</strong>
                                    <p>{{ $vente->numero_ticket }}</p>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Pistolet:</strong>
                                    <p>{{ $vente->pistolet->numero }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Pompe:</strong>
                                    <p>{{ $vente->pistolet->pompe->numero }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Carburant:</strong>
                                    <p><span
                                            class="badge bg-info fs-6">{{ $vente->pistolet->pompe->cuve->carburant->nom }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Cuve:</strong>
                                    <p>{{ $vente->pistolet->pompe->cuve->nom }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Quantité:</strong>
                                    <p class="h5 text-primary">{{ number_format($vente->quantite, 2) }} L</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Prix Unitaire:</strong>
                                    <p>{{ number_format($vente->prix_unitaire, 0) }} FCFA</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Montant Total:</strong>
                                    <p class="h5 text-success">{{ number_format($vente->montant_total, 0) }} FCFA</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Mode de Paiement:</strong>
                                    <p>
                                        @switch($vente->mode_paiement)
                                            @case('especes')
                                                <span class="badge bg-success"><i data-feather="dollar-sign"></i> Espèces</span>
                                            @break

                                            @case('carte')
                                                <span class="badge bg-primary"><i data-feather="credit-card"></i> Carte
                                                    Bancaire</span>
                                            @break

                                            @case('mobile_money')
                                                <span class="badge bg-info"><i data-feather="smartphone"></i> Mobile Money</span>
                                            @break

                                            @case('credit')
                                                <span class="badge bg-warning"><i data-feather="clock"></i> À Crédit</span>
                                            @break
                                        @endswitch
                                    </p>
                                </div>
                                @if ($vente->client)
                                    <div class="col-md-6">
                                        <strong>Nom du Client:</strong>
                                        <p>{{ $vente->client }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>Vendu par:</strong>
                                <p>{{ $vente->user->prenom ?? '' }} {{ $vente->user->nom ?? '' }}
                                    <small class="text-muted">{{ ucfirst($vente->user->role ?? 'N/A') }}</small>
                                </p>
                            </div>
                            <p>
                                <small class="text-muted">{{ ucfirst($vente->user->role ?? 'N/A') }}</small>
                            </p>
                            <div class="mb-3">
                                <strong>Station:</strong>
                                <p>{{ $vente->user->station->nom ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Impact sur Stock</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Stock avant vente:</strong>
                                <p>{{ number_format($vente->pistolet->pompe->cuve->stock_actuel + $vente->quantite, 0) }} L
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>Quantité vendue:</strong>
                                <p class="text-danger">- {{ number_format($vente->quantite, 2) }} L</p>
                            </div>
                            <div class="mb-3">
                                <strong>Stock actuel:</strong>
                                <p class="h5 text-primary">
                                    {{ number_format($vente->pistolet->pompe->cuve->stock_actuel, 0) }}
                                    L</p>
                            </div>
                            <div class="progress" style="height: 25px;">
                                @php
                                    $percentage = $vente->pistolet->pompe->cuve->pourcentageRemplissage();
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
                    @if ($vente->mode_paiement == 'credit')
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-white">Crédit à Recouvrer</div>
                            <div class="card-body">
                                <p class="h4 text-danger">{{ number_format($vente->montant_total, 0) }} FCFA</p>
                                <small class="text-muted">Client: {{ $vente->client }}</small>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header bg-light text-dark">Informations</div>
                        <div class="card-body">
                            <small class="text-muted">
                                <strong>Enregistrée le:</strong><br>
                                {{ $vente->created_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-light text-dark">Action</div>
                        <div class="card-body">
                            <a href="{{ route('ventes.print', $vente) }}" target="_blank" class="btn btn-success btn-sm w-100 mb-2">
                                <i data-feather="printer" class="me-2"></i>Imprimer la Vente
                            </a>
                            <a href="{{ route('ventes.edit', $vente) }}" class="btn btn-1 btn-sm w-100 mb-2">
                                <i data-feather="edit" class="me-2"></i>Modifier la Vente
                            </a>
                            <form action="{{ route('ventes.destroy', $vente) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?');">
                                    <i data-feather="trash-2" class="me-2"></i>Supprimer la Vente
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
