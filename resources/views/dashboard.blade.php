@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-12">
                        <h2 class="mb-0 text-white">
                            <i class="fas fa-tachometer-alt"></i> Tableau de Bord
                        </h2>
                        <p class="text-white">Bienvenue, {{ $user->prenom }} {{ $user->nom }} - {{ ucfirst($user->role) }}
                        </p>
                        @if ($station)
                            <p class="text-white"><i class="fas fa-building"></i> {{ $station->nom }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        <!-- Statistiques Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white">Cuves Totales</h6>
                                <h2 class="mb-0 text-white">{{ $stats['total_cuves'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-oil-can fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white">Cuves en Alerte</h6>
                                <h2 class="mb-0 text-white">{{ $stats['cuves_alerte'] }}</h2>
                            </div>
                            <div>
                                <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white">Ventes du Jour</h6>
                                <h2 class="mb-0 text-white">{{ number_format($stats['ventes_jour'], 0, ',', ' ') }} FCFA</h2>
                            </div>
                            <div>
                                <i class="fas fa-cash-register fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white">Ventes du Mois</h6>
                                <h2 class="mb-0 text-white">{{ number_format($stats['ventes_mois'], 0, ',', ' ') }} FCFA</h2>
                            </div>
                            <div>
                                <i class="fas fa-chart-line fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cuves en Alerte -->
        @if ($cuvesAlerte->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light text-dark">
                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Cuves en Alerte - Stock Minimum
                                Atteint</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tableCuvesAlerte" class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Cuve</th>
                                            <th>Carburant</th>
                                            <th>Stock Actuel</th>
                                            <th>Stock Min</th>
                                            <th>Capacité Max</th>
                                            <th>Niveau</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cuvesAlerte as $cuve)
                                            <tr>
                                                <td>{{ $cuve->nom }}</td>
                                                <td>{{ $cuve->carburant->nom }}</td>
                                                <td>{{ number_format($cuve->stock_actuel, 2) }} L</td>
                                                <td>{{ number_format($cuve->stock_min, 2) }} L</td>
                                                <td>{{ number_format($cuve->capacite_max, 2) }} L</td>
                                                <td>
                                                    <div class="progress" style="height: 25px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: {{ $cuve->pourcentageRemplissage() }}%"
                                                            aria-valuenow="{{ $cuve->pourcentageRemplissage() }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            {{ number_format($cuve->pourcentageRemplissage(), 1) }}%
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Dernières Ventes et Entrées -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0"><i class="fas fa-cash-register"></i> Dernières Ventes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tableDernieresVentes" class="table table-hover table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N° Vente</th>
                                        <th>Date</th>
                                        <th>Pistolet</th>
                                        <th>Pompiste</th>
                                        <th>Quantité</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dernieresVentes as $vente)
                                        <tr>
                                            <td>{{ $vente->numero_vente }}</td>
                                            <td>{{ $vente->date_vente->format('d/m/Y H:i') }}</td>
                                            <td>{{ $vente->pistolet->nom }}</td>
                                            <td>{{ $vente->pompiste->prenom }} {{ $vente->pompiste->nom }}</td>
                                            <td>{{ number_format($vente->quantite, 2) }} L</td>
                                            <td>{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Aucune vente enregistrée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-light text-dark">
                        <h5 class="mb-0"><i class="fas fa-arrow-down"></i> Dernières Entrées</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @forelse($dernieresEntrees as $entree)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $entree->cuve->carburant->nom }}</h6>
                                            <small class="text-muted">{{ $entree->cuve->nom }}</small>
                                        </div>
                                        <div class="text-end">
                                            <strong>{{ number_format($entree->quantite, 0) }} L</strong><br>
                                            <small class="text-muted">{{ $entree->date_entree->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    Aucune entrée enregistrée
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if ($stats['commandes_attente'] > 0)
                    <div class="card mt-3">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0"><i class="fas fa-clock"></i> Commandes en Attente</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2>{{ $stats['commandes_attente'] }}</h2>
                            <p class="mb-0">commande(s) en attente de validation</p>
                            <a href="{{ route('commandes.index') }}" class="btn btn-warning mt-2">Voir les commandes</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ['tableCuvesAlerte', 'tableDernieresVentes'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) {
                    new simpleDatatables.DataTable(el, { searchable: true, fixedHeight: false });
                }
            });
        });
    </script>
@endsection
