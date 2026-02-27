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
                                Gestion des Pompes
                            </h1>
                            <p><small>Liste de toutes les pompes enregistrées dans le système.</small></p>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('pompes.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Pompe
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-striped table-hover table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nom</th>
                                    <th>N° Série</th>
                                    <th>Cuve / Carburant</th>
                                    <th>Station</th>
                                    <th>Pistolets</th>
                                    <th>État</th>
                                    <th>Maintenance</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pompes as $pompe)
                                    <tr>
                                        <td><strong>{{ $pompe->nom }}</strong></td>
                                        <td>{{ $pompe->numero_serie ?? '-' }}</td>
                                        <td>
                                            {{ $pompe->cuve->nom }}
                                            <small class="text-muted d-block">{{ $pompe->cuve->carburant->nom }}</small>
                                        </td>
                                        <td>{{ $pompe->cuve->station->nom ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $pompe->pistolets->count() }}</span>
                                        </td>
                                        <td>
                                            @switch($pompe->etat)
                                                @case('actif')
                                                    <span class="badge bg-success">Actif</span>@break
                                                @case('inactif')
                                                    <span class="badge bg-secondary">Inactif</span>@break
                                                @case('maintenance')
                                                    <span class="badge bg-warning">Maintenance</span>@break
                                            @endswitch
                                        </td>
                                        <td>{{ $pompe->date_maintenance ? $pompe->date_maintenance->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('pompes.show', $pompe) }}" class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pompes.edit', $pompe) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pompes.destroy', $pompe) }}" method="POST"
                                                    onsubmit="return confirm('Supprimer cette pompe ?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Aucune pompe enregistrée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $pompes->links() }}</div>
                </div>
            </div>
        </div>
    </main>
@endsection
