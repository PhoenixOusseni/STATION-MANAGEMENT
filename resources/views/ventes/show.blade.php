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
                                <small>Détails de la vente enregistrée le {{ $vente->created_at->format('d/m/Y à H:i') }}.</small>
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

                {{-- ─── Colonne principale ─── --}}
                <div class="col-lg-8">

                    {{-- Identification --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark fw-bold">
                            <i data-feather="info" class="me-2"></i>Identification
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">N° de Vente</p>
                                    <p class="fw-bold">{{ $vente->numero_vente }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Date de Vente</p>
                                    <p class="fw-bold">{{ $vente->date_vente->format('d/m/Y à H:i') }}</p>
                                </div>
                                @if ($vente->numero_ticket)
                                    <div class="col-md-4">
                                        <p class="text-muted mb-1 small">N° de Ticket</p>
                                        <p class="fw-bold">{{ $vente->numero_ticket }}</p>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Pistolet</p>
                                    <p class="fw-bold">{{ $vente->pistolet->numero }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Pompe</p>
                                    <p class="fw-bold">{{ $vente->pistolet->pompe->nom ?? 'Pompe '.$vente->pistolet->pompe->numero }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Cuve</p>
                                    <p class="fw-bold">{{ $vente->pistolet->pompe->cuve->nom }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Carburant</p>
                                    <p><span class="badge bg-info fs-6">{{ $vente->pistolet->pompe->cuve->carburant->nom }}</span></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Station</p>
                                    <p class="fw-bold">{{ $vente->station->nom ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Vendu par</p>
                                    <p class="fw-bold">
                                        {{ $vente->pompiste->prenom ?? '' }} {{ $vente->pompiste->nom ?? 'N/A' }}
                                        <br><small class="text-muted fw-normal">{{ ucfirst($vente->pompiste->role ?? '') }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Relevé Compteur --}}
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white fw-bold">
                            <i data-feather="activity" class="me-2"></i>Relevé Compteur & Quantités
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1 small">Index Initial</p>
                                    <p class="fw-bold">{{ $vente->index_depart !== null ? number_format($vente->index_depart, 2) : '—' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1 small">Index Final</p>
                                    <p class="fw-bold">{{ $vente->index_final !== null ? number_format($vente->index_final, 2) : '—' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Quantité Compteur (L)</p>
                                    <p class="fw-bold text-primary h5">{{ number_format($vente->quantite, 2) }} L</p>
                                    <small class="text-muted">= Index Initial − Index Final</small>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Retour Cuve (L)</p>
                                    <p class="fw-bold text-warning">{{ number_format($vente->retour_cuve, 2) }} L</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Quantité Vendue (L)</p>
                                    <p class="fw-bold text-success h5">{{ number_format($vente->quantite_vendue, 2) }} L</p>
                                    <small class="text-muted">= Quantité − Retour Cuve</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Prix & Montant --}}
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white fw-bold">
                            <i data-feather="dollar-sign" class="me-2"></i>Prix & Montant
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Prix Unitaire</p>
                                    <p class="fw-bold">{{ number_format($vente->prix_unitaire, 0, ',', ' ') }} FCFA</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Montant Théorique</p>
                                    <p class="fw-bold">{{ number_format($vente->quantite * $vente->prix_unitaire, 0, ',', ' ') }} FCFA</p>
                                    <small class="text-muted">Qté Compteur × Prix</small>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Montant Réel (Total)</p>
                                    <p class="fw-bold text-success h5">{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</p>
                                    <small class="text-muted">Qté Vendue × Prix</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mode de paiement --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark fw-bold">
                            <i data-feather="credit-card" class="me-2"></i>Mode de Paiement
                        </div>
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1 small">Mode de Paiement</p>
                                    @switch($vente->mode_paiement)
                                        @case('especes')
                                            <span class="badge bg-success fs-6">
                                                <i data-feather="dollar-sign" style="width:14px;height:14px;" class="me-1"></i>Espèces
                                            </span>
                                        @break
                                        @case('carte')
                                            <span class="badge bg-primary fs-6">
                                                <i data-feather="credit-card" style="width:14px;height:14px;" class="me-1"></i>Carte Bancaire
                                            </span>
                                        @break
                                        @case('mobile_money')
                                            <span class="badge bg-info fs-6">
                                                <i data-feather="smartphone" style="width:14px;height:14px;" class="me-1"></i>Mobile Money
                                            </span>
                                        @break
                                        @case('cheque')
                                            <span class="badge bg-secondary fs-6">
                                                <i data-feather="file-text" style="width:14px;height:14px;" class="me-1"></i>Chèque
                                            </span>
                                        @break
                                        @case('credit')
                                            <span class="badge bg-warning fs-6">
                                                <i data-feather="clock" style="width:14px;height:14px;" class="me-1"></i>À Crédit
                                            </span>
                                        @break
                                    @endswitch
                                </div>
                                @if ($vente->client)
                                    <div class="col-md-6">
                                        <p class="text-muted mb-1 small">Nom du Client</p>
                                        <p class="fw-bold">{{ $vente->client }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($vente->observation)
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark fw-bold">
                                <i data-feather="message-square" class="me-2"></i>Observation
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $vente->observation }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ─── Colonne latérale ─── --}}
                <div class="col-lg-4">

                    {{-- Impact stock --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark fw-bold">Impact sur Stock</div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-3">
                                <tr>
                                    <td class="text-muted">Stock avant vente</td>
                                    <td class="fw-bold text-end">
                                        {{ number_format($vente->pistolet->pompe->cuve->stock_actuel + $vente->quantite_vendue, 0, ',', ' ') }} L
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Qté compteur</td>
                                    <td class="fw-bold text-end text-primary">{{ number_format($vente->quantite, 2) }} L</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Retour cuve</td>
                                    <td class="fw-bold text-end text-warning">+ {{ number_format($vente->retour_cuve, 2) }} L</td>
                                </tr>
                                <tr class="table-danger">
                                    <td class="fw-bold">Qté vendue déduite</td>
                                    <td class="fw-bold text-end text-danger">− {{ number_format($vente->quantite_vendue, 2) }} L</td>
                                </tr>
                                <tr class="table-primary">
                                    <td class="fw-bold">Stock actuel</td>
                                    <td class="fw-bold text-end text-primary h6">
                                        {{ number_format($vente->pistolet->pompe->cuve->stock_actuel, 0, ',', ' ') }} L
                                    </td>
                                </tr>
                            </table>
                            @php
                                $percentage = $vente->pistolet->pompe->cuve->pourcentageRemplissage();
                                $colorClass = $percentage <= 20 ? 'bg-danger' : ($percentage <= 50 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $colorClass }}" style="width: {{ $percentage }}%">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($vente->mode_paiement === 'credit')
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-white fw-bold">Crédit à Recouvrer</div>
                            <div class="card-body">
                                <p class="h4 text-danger mb-1">{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</p>
                                <small class="text-muted">Client : {{ $vente->client }}</small>
                            </div>
                        </div>
                    @endif

                    {{-- Infos --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Informations</div>
                        <div class="card-body">
                            <small class="text-muted d-block mb-1">
                                <strong>Enregistrée le :</strong><br>
                                {{ $vente->created_at->format('d/m/Y à H:i') }}
                            </small>
                            @if ($vente->updated_at != $vente->created_at)
                                <small class="text-muted d-block">
                                    <strong>Modifiée le :</strong><br>
                                    {{ $vente->updated_at->format('d/m/Y à H:i') }}
                                </small>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card">
                        <div class="card-header bg-light text-dark fw-bold">Actions</div>
                        <div class="card-body d-grid gap-2">
                            <a href="{{ route('ventes.print', $vente) }}" target="_blank" class="btn btn-success btn-sm">
                                <i data-feather="printer" class="me-2"></i>Imprimer la Vente
                            </a>
                            <a href="{{ route('ventes.edit', $vente) }}" class="btn btn-1 btn-sm">
                                <i data-feather="edit" class="me-2"></i>Modifier la Vente
                            </a>
                            <form action="{{ route('ventes.destroy', $vente) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?');">
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
