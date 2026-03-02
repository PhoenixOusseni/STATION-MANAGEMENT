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
                            {{ $carburant->nom }}
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('carburants.edit', $carburant) }}" class="btn btn-warning btn-sm">
                            <i data-feather="edit" class="me-2"></i>Modifier
                        </a>
                        <a href="{{ route('carburants.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Retour
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
                    <div class="card-header bg-light text-dark">Informations du Carburant</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Code:</strong>
                            <p><span class="badge bg-primary">{{ $carburant->code }}</span></p>
                        </div>
                        <div class="mb-3">
                            <strong>Nom:</strong>
                            <p>{{ $carburant->nom }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Prix Unitaire:</strong>
                            <p class="h4 text-success">{{ number_format($carburant->prix_unitaire, 0) }} FCFA</p>
                        </div>
                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p>{{ $carburant->description ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light text-dark">Cuves Utilisant ce Carburant</div>
                    <div class="card-body">
                        <table class="table table-sm table-hover table-bordered mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Station</th>
                                    <th>Nom de la Cuve</th>
                                    <th>Capacité</th>
                                    <th>Stock Actuel</th>
                                    <th>Niveau</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($carburant->cuves as $cuve)
                                <tr>
                                    <td>{{ $cuve->station->nom }}</td>
                                    <td>{{ $cuve->nom }}</td>
                                    <td>{{ number_format($cuve->capacite_max, 0) }} L</td>
                                    <td>{{ number_format($cuve->stock_actuel, 0) }} L</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            @php
                                                $percentage = $cuve->pourcentageRemplissage();
                                                $colorClass = $percentage <= 20 ? 'bg-danger' : ($percentage <= 50 ? 'bg-warning' : 'bg-success');
                                            @endphp
                                            <div class="progress-bar {{ $colorClass }}" style="width: {{ $percentage }}%">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucune cuve n'utilise ce carburant</td>
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
