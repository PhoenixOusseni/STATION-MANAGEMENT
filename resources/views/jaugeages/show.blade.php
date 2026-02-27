@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="droplet"></i></div>
                                Jaugeage {{ $jaugeage->numero_jaugeage }}
                            </h1>
                            <p>
                                <small>
                                    Enregistré le {{ $jaugeage->created_at->format('d/m/Y à H:i') }}
                                    par {{ $jaugeage->user->prenom }} {{ $jaugeage->user->nom }}
                                </small>
                            </p>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('jaugeages.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>&nbsp; Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header bg-light text-dark">Détails du Jaugeage</div>
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>N° Jaugeage</strong>
                                    <p class="h5">{{ $jaugeage->numero_jaugeage }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Type</strong>
                                    <p>
                                        <span class="badge {{ $jaugeage->badgeClass() }} fs-6">
                                            {{ $jaugeage->libellType() }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Cuve</strong>
                                    <p>{{ $jaugeage->cuve->nom }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Carburant</strong>
                                    <p><span class="badge bg-info fs-6">{{ $jaugeage->cuve->carburant->nom }}</span></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Quantité Mesurée</strong>
                                    <p class="h5">{{ number_format($jaugeage->quantite_mesuree, 2) }} L</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Stock Théorique</strong>
                                    <p>{{ number_format($jaugeage->quantite_theorique, 2) }} L</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Écart</strong>
                                    <p>
                                        <span class="badge {{ $jaugeage->ecartBadgeClass() }} fs-6">
                                            {{ $jaugeage->ecart >= 0 ? '+' : '' }}{{ number_format($jaugeage->ecart, 2) }} L
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Date du Jaugeage</strong>
                                <p>{{ $jaugeage->date_jaugeage->format('d/m/Y à H:i') }}</p>
                            </div>

                            @if($jaugeage->observation)
                                <div class="mb-3">
                                    <strong>Observation</strong>
                                    <p>{{ $jaugeage->observation }}</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <strong>Opérateur</strong>
                                <p>{{ $jaugeage->user->prenom }} {{ $jaugeage->user->nom }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">

                    {{-- Lien vers la session --}}
                    @if($jaugeage->sessionVente)
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark">Session Associée</div>
                            <div class="card-body">
                                <p class="mb-1"><strong>N° Session :</strong>
                                    {{ $jaugeage->sessionVente->numero_session }}
                                </p>
                                <p class="mb-2"><strong>Statut :</strong>
                                    <span class="badge {{ $jaugeage->sessionVente->isEnCours() ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $jaugeage->sessionVente->isEnCours() ? 'En cours' : 'Clôturée' }}
                                    </span>
                                </p>
                                <a href="{{ route('session_ventes.show', $jaugeage->sessionVente) }}"
                                    class="btn btn-info btn-sm">
                                    <i data-feather="eye" class="me-1"></i>Voir la Session
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Lien vers l'entrée --}}
                    @if($jaugeage->entree)
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark">Entrée (Dépotage) Associée</div>
                            <div class="card-body">
                                <p class="mb-1"><strong>N° Entrée :</strong>
                                    {{ $jaugeage->entree->numero_entree }}
                                </p>
                                <p class="mb-2"><strong>Quantité dépotée :</strong>
                                    {{ number_format($jaugeage->entree->quantite, 2) }} L
                                </p>
                                <a href="{{ route('entrees.show', $jaugeage->entree) }}"
                                    class="btn btn-info btn-sm">
                                    <i data-feather="eye" class="me-1"></i>Voir l'Entrée
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Analyse de l'écart --}}
                    <div class="card">
                        <div class="card-header bg-light text-dark">Analyse de l'Écart</div>
                        <div class="card-body">
                            @php $ecart = (float) $jaugeage->ecart; @endphp
                            @if($ecart == 0)
                                <div class="alert alert-secondary mb-0">
                                    <i data-feather="check-circle" class="me-2"></i>
                                    <strong>Aucun écart.</strong> La mesure physique correspond exactement au stock théorique.
                                </div>
                            @elseif($ecart > 0)
                                <div class="alert alert-success mb-0">
                                    <i data-feather="trending-up" class="me-2"></i>
                                    <strong>Surplus de {{ number_format($ecart, 2) }} L.</strong>
                                    La cuve contient plus de carburant que le stock théorique (apport non enregistré ou erreur de mesure antérieure).
                                </div>
                            @else
                                <div class="alert alert-danger mb-0">
                                    <i data-feather="trending-down" class="me-2"></i>
                                    <strong>Perte de {{ number_format(abs($ecart), 2) }} L.</strong>
                                    La cuve contient moins de carburant que le stock théorique (évaporation, fuite, vente non enregistrée ou erreur de mesure).
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
