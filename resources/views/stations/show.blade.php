@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                            {{ $station->nom }}
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('stations.edit', $station) }}" class="btn btn-warning">
                            <i data-feather="edit" class="me-2"></i>Modifier
                        </a>
                        <a href="{{ route('stations.index') }}" class="btn btn-light">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Informations Générales</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Nom:</strong>
                            <p>{{ $station->nom }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Ville:</strong>
                            <p>{{ $station->ville ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Adresse:</strong>
                            <p>{{ $station->adresse }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Téléphone:</strong>
                            <p>{{ $station->telephone ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong>
                            <p>{{ $station->email ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Responsable:</strong>
                            <p>{{ $station->responsable ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">Cuves de la Station</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Carburant</th>
                                    <th>Capacité</th>
                                    <th>Stock Actuel</th>
                                    <th>Niveau</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($station->cuves as $cuve)
                                <tr>
                                    <td>{{ $cuve->nom }}</td>
                                    <td><span class="badge bg-info">{{ $cuve->carburant->nom }}</span></td>
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
                                    <td colspan="5" class="text-center text-muted">Aucune cuve</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Utilisateurs de la Station</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($station->users as $user)
                                <tr>
                                    <td>{{ $user->prenom }} {{ $user->nom }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
                                    <td>
                                        @if($user->statut == 'actif')
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Aucun utilisateur</td>
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
