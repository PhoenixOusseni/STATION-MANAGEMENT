@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="tool"></i></div>
                                {{ $pompe->nom }}
                            </h1>
                            <p><small>{{ $pompe->cuve->station->nom ?? '' }} &mdash; {{ $pompe->cuve->nom }} ({{ $pompe->cuve->carburant->nom }})</small></p>
                        </div>
                        <div class="col-auto mt-4 d-flex gap-2">
                            <a href="{{ route('pompes.edit', $pompe) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                            <a href="{{ route('pompes.index') }}" class="btn btn-light btn-sm">
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
                                <dd class="col-sm-7">{{ $pompe->nom }}</dd>

                                <dt class="col-sm-5">N° Série</dt>
                                <dd class="col-sm-7">{{ $pompe->numero_serie ?? '-' }}</dd>

                                <dt class="col-sm-5">Cuve</dt>
                                <dd class="col-sm-7">{{ $pompe->cuve->nom }}</dd>

                                <dt class="col-sm-5">Carburant</dt>
                                <dd class="col-sm-7">{{ $pompe->cuve->carburant->nom }}</dd>

                                <dt class="col-sm-5">État</dt>
                                <dd class="col-sm-7">
                                    @switch($pompe->etat)
                                        @case('actif')     <span class="badge bg-success">Actif</span>@break
                                        @case('inactif')   <span class="badge bg-secondary">Inactif</span>@break
                                        @case('maintenance')<span class="badge bg-warning">Maintenance</span>@break
                                    @endswitch
                                </dd>

                                <dt class="col-sm-5">Maintenance</dt>
                                <dd class="col-sm-7">{{ $pompe->date_maintenance ? $pompe->date_maintenance->format('d/m/Y') : '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
                            <span><i data-feather="zap" class="me-2"></i>Pistolets</span>
                            <a href="{{ route('pistolets.create', ['pompe_id' => $pompe->id]) }}" class="btn btn-1 btn-sm">
                                <i class="fas fa-plus me-1"></i>Ajouter un pistolet
                            </a>
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-sm table-hover table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Numéro</th>
                                        <th>Pompiste affiché</th>
                                        <th>État</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pompe->pistolets as $pistolet)
                                        <tr>
                                            <td>{{ $pistolet->nom }}</td>
                                            <td>{{ $pistolet->numero }}</td>
                                            <td>
                                                @if($pistolet->pompiste)
                                                    {{ $pistolet->pompiste->prenom }} {{ $pistolet->pompiste->nom }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($pistolet->etat)
                                                    @case('actif')     <span class="badge bg-success">Actif</span>@break
                                                    @case('inactif')   <span class="badge bg-secondary">Inactif</span>@break
                                                    @case('maintenance')<span class="badge bg-warning">Maintenance</span>@break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('pistolets.show', $pistolet) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('pistolets.edit', $pistolet) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-muted text-center">Aucun pistolet</td></tr>
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
