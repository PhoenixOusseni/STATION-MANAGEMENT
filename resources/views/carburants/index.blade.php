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
                            Gestion des Carburants
                        </h1>
                        <p class="text-white">Liste des carburants disponibles dans les différentes stations.</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('carburants.create') }}" class="btn btn-light btn-sm">
                            <i data-feather="plus" class="me-2"></i>Nouveau Carburant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card">
            <div class="card-header bg-light text-dark">Liste des Carburants</div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <table id="datatablesSimple" class="table table-hover table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Prix Unitaire</th>
                            <th>Description</th>
                            <th>Nb Cuves</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($carburants as $carburant)
                        <tr>
                            <td><strong>{{ $carburant->code }}</strong></td>
                            <td>{{ $carburant->nom }}</td>
                            <td><strong>{{ number_format($carburant->prix_unitaire, 0) }} FCFA</strong></td>
                            <td>{{ $carburant->description }}</td>
                            <td><span class="badge bg-primary">{{ $carburant->cuves_count }}</span></td>
                            <td>
                                <a href="{{ route('carburants.show', $carburant) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i data-feather="eye"></i>
                                </a>
                                <a href="{{ route('carburants.edit', $carburant) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i data-feather="edit"></i>
                                </a>
                                <form action="{{ route('carburants.destroy', $carburant) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce carburant ?');">
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
                            <td colspan="6" class="text-center text-muted">Aucun carburant enregistré</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $carburants->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
