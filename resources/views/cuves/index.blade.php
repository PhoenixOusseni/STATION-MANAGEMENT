@extends('layouts.master')

@section('title', 'Liste des Cuves')

@section('content')

    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fas fa-oil-can"></i></div>
                            Gestion des Cuves
                        </h1>
                        <p>Liste des cuves disponibles dans les différentes stations.</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('cuves.create') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-plus me-1"></i>&nbsp; Nouvelle Cuve
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10 mb-4">
        <div class="card">
            <div class="card-header bg-light text-dark">Liste des Cuves</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nom</th>
                                <th>Station</th>
                                <th>Carburant</th>
                                <th>Capacité Max</th>
                                <th>Stock Actuel</th>
                                <th>Stock Min</th>
                                <th>Niveau</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cuves as $cuve)
                                <tr>
                                    <td>{{ $cuve->nom }}</td>
                                    <td>{{ $cuve->station->nom }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $cuve->carburant->nom }}</span>
                                    </td>
                                    <td>{{ number_format($cuve->capacite_max, 2) }} L</td>
                                    <td>{{ number_format($cuve->stock_actuel, 2) }} L</td>
                                    <td>{{ number_format($cuve->stock_min, 2) }} L</td>
                                    <td>
                                        <div class="progress" style="height: 25px;">
                                            @php
                                                $percentage = $cuve->pourcentageRemplissage();
                                                $colorClass =
                                                    $percentage <= 20
                                                        ? 'bg-danger'
                                                        : ($percentage <= 50
                                                            ? 'bg-warning'
                                                            : 'bg-success');
                                            @endphp
                                            <div class="progress-bar {{ $colorClass }}" role="progressbar"
                                                style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($cuve->isStockAlerte())
                                            <span class="badge bg-danger"><i class="fas fa-exclamation-triangle"></i>
                                                Alerte</span>
                                        @else
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Normal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('cuves.show', $cuve) }}" class="btn btn-sm btn-info"
                                                title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('cuves.edit', $cuve) }}" class="btn btn-sm btn-warning"
                                                title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('cuves.destroy', $cuve) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette cuve ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Aucune cuve enregistrée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
