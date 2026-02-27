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
                            Gestion des Stations
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('stations.create') }}" class="btn btn-light">
                            <i data-feather="plus" class="me-2"></i>Nouvelle Station
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Ville</th>
                            <th>Adresse</th>
                            <th>Téléphone</th>
                            <th>Responsable</th>
                            <th>Cuves</th>
                            <th>Utilisateurs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stations as $station)
                        <tr>
                            <td><strong>{{ $station->nom }}</strong></td>
                            <td>{{ $station->ville }}</td>
                            <td>{{ $station->adresse }}</td>
                            <td>{{ $station->telephone }}</td>
                            <td>{{ $station->responsable }}</td>
                            <td><span class="badge bg-primary">{{ $station->cuves_count }}</span></td>
                            <td><span class="badge bg-info">{{ $station->users_count }}</span></td>
                            <td>
                                <a href="{{ route('stations.show', $station) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i data-feather="eye"></i>
                                </a>
                                <a href="{{ route('stations.edit', $station) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i data-feather="edit"></i>
                                </a>
                                <form action="{{ route('stations.destroy', $station) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette station ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Aucune station enregistrée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $stations->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
