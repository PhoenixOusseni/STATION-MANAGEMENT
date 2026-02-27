@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="zap"></i></div>
                                {{ $pistolet->nom }} — N° {{ $pistolet->numero }}
                            </h1>
                            <p><small>
                                {{ $pistolet->pompe->nom }} &mdash;
                                {{ $pistolet->pompe->cuve->carburant->nom }} &mdash;
                                {{ $pistolet->pompe->cuve->station->nom ?? '' }}
                            </small></p>
                        </div>
                        <div class="col-auto mt-4 d-flex gap-2">
                            <a href="{{ route('pistolets.edit', $pistolet) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                            <a href="{{ route('pistolets.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Détails</div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Nom</dt>
                                <dd class="col-sm-7">{{ $pistolet->nom }}</dd>

                                <dt class="col-sm-5">Numéro</dt>
                                <dd class="col-sm-7">{{ $pistolet->numero }}</dd>

                                <dt class="col-sm-5">Pompe</dt>
                                <dd class="col-sm-7">
                                    <a href="{{ route('pompes.show', $pistolet->pompe) }}">{{ $pistolet->pompe->nom }}</a>
                                </dd>

                                <dt class="col-sm-5">Carburant</dt>
                                <dd class="col-sm-7">{{ $pistolet->pompe->cuve->carburant->nom }}</dd>

                                <dt class="col-sm-5">Station</dt>
                                <dd class="col-sm-7">{{ $pistolet->pompe->cuve->station->nom ?? '-' }}</dd>

                                <dt class="col-sm-5">Pompiste</dt>
                                <dd class="col-sm-7">
                                    @if($pistolet->pompiste)
                                        {{ $pistolet->pompiste->prenom }} {{ $pistolet->pompiste->nom }}
                                    @else
                                        <span class="text-muted">Non assigné</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-5">État</dt>
                                <dd class="col-sm-7">
                                    @switch($pistolet->etat)
                                        @case('actif')      <span class="badge bg-success">Actif</span>@break
                                        @case('inactif')    <span class="badge bg-secondary">Inactif</span>@break
                                        @case('maintenance')<span class="badge bg-warning">Maintenance</span>@break
                                    @endswitch
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light text-dark">
                            <i data-feather="dollar-sign" class="me-2"></i>10 Dernières Ventes
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-sm table-hover table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Vente</th>
                                        <th>Date</th>
                                        <th class="text-end">Quantité (L)</th>
                                        <th class="text-end">Montant (FCFA)</th>
                                        <th>Paiement</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pistolet->ventes as $vente)
                                        <tr>
                                            <td>{{ $vente->numero_vente }}</td>
                                            <td>{{ $vente->date_vente->format('d/m/Y H:i') }}</td>
                                            <td class="text-end">{{ number_format($vente->quantite, 2) }}</td>
                                            <td class="text-end">{{ number_format($vente->montant_total, 0, ',', ' ') }}</td>
                                            <td>
                                                <span class="badge
                                                    @switch($vente->mode_paiement)
                                                        @case('especes') bg-success @break
                                                        @case('carte') bg-primary @break
                                                        @case('mobile_money') bg-info @break
                                                        @case('credit') bg-warning @break
                                                    @endswitch">
                                                    {{ ucfirst(str_replace('_', ' ', $vente->mode_paiement)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('ventes.show', $vente) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-muted text-center">Aucune vente</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
