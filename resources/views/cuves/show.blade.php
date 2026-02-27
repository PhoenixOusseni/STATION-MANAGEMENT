@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="database"></i></div>
                                {{ $cuve->nom }}
                            </h1>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('cuves.edit', $cuve) }}" class="btn btn-warning btn-sm">
                                <i data-feather="edit" class="me-2"></i>Modifier
                            </a>
                            <a href="{{ route('cuves.index') }}" class="btn btn-light btn-sm">
                                <i data-feather="arrow-left" class="me-2"></i>Retour
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
                        <div class="card-header bg-light text-dark">Informations Générales</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Station:</strong>
                                <p>{{ $cuve->station->nom }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Carburant:</strong>
                                <p><span class="badge bg-info">{{ $cuve->carburant->nom }}</span></p>
                            </div>
                            <div class="mb-3">
                                <strong>Numéro de Série:</strong>
                                <p>{{ $cuve->numero_serie ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Niveau du Stock</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Capacité Maximale:</strong>
                                <p class="h5">{{ number_format($cuve->capacite_max, 0) }} L</p>
                            </div>
                            <div class="mb-3">
                                <strong>Stock Actuel:</strong>
                                <p class="h5 text-primary">{{ number_format($cuve->stock_actuel, 0) }} L</p>
                            </div>
                            <div class="mb-3">
                                <strong>Stock Minimum:</strong>
                                <p>{{ number_format($cuve->stock_min, 0) }} L</p>
                            </div>
                            <div class="mb-3">
                                <strong>Niveau de Remplissage:</strong>
                                <div class="progress" style="height: 30px;">
                                    @php
                                        $percentage = $cuve->pourcentageRemplissage();
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
                            @if ($cuve->isStockAlerte())
                                <div class="alert alert-danger">
                                    <i data-feather="alert-triangle"></i> Stock en alerte!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Pompes Reliées</div>
                        <div class="card-body">
                            <table class="table table-sm table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nom</th>
                                        <th>N° Série</th>
                                        <th>État</th>
                                        <th>Maintenance</th>
                                        <th>Pistolets</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cuve->pompes as $pompe)
                                        <tr>
                                            <td>{{ $pompe->nom }}</td>
                                            <td>{{ $pompe->numero_serie ?? '-' }}</td>
                                            <td>
                                                @if ($pompe->etat == 'actif')
                                                    <span class="badge bg-success">Actif</span>
                                                @elseif($pompe->etat == 'maintenance')
                                                    <span class="badge bg-warning">Maintenance</span>
                                                @else
                                                    <span class="badge bg-danger">Inactif</span>
                                                @endif
                                            </td>
                                            <td>{{ $pompe->date_maintenance ? $pompe->date_maintenance->format('d/m/Y') : '-' }}
                                            </td>
                                            <td><span class="badge bg-primary">{{ $pompe->pistolets->count() }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Aucune pompe reliée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light text-dark">Dernières Entrées</div>
                        <div class="card-body">
                            <table class="table table-sm table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>N° Entrée</th>
                                        <th>Quantité</th>
                                        <th>Montant</th>
                                        <th>Enregistré par</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cuve->entrees()->latest()->take(10)->get() as $entree)
                                        <tr>
                                            <td>{{ $entree->date_entree->format('d/m/Y') }}</td>
                                            <td>{{ $entree->numero_entree }}</td>
                                            <td>{{ number_format($entree->quantite, 0) }} L</td>
                                            <td>{{ number_format($entree->montant_total, 0) }} FCFA</td>
                                            <td>{{ $entree->user->prenom }} {{ $entree->user->nom }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Aucune entrée</td>
                                        </tr>
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
