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
                                Gestion des Pistolets
                            </h1>
                            <p><small>Liste de tous les pistolets enregistrés dans le système.</small></p>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('pistolets.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouveau Pistolet
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
                                    <th>Numéro</th>
                                    <th>Pompe</th>
                                    <th>Carburant</th>
                                    <th>Station</th>
                                    <th>Pompiste</th>
                                    <th>État</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pistolets as $pistolet)
                                    <tr>
                                        <td><strong>{{ $pistolet->nom }}</strong></td>
                                        <td>{{ $pistolet->numero }}</td>
                                        <td>{{ $pistolet->pompe->nom }}</td>
                                        <td>{{ $pistolet->pompe->cuve->carburant->nom }}</td>
                                        <td>{{ $pistolet->pompe->cuve->station->nom ?? '-' }}</td>
                                        <td>
                                            @if($pistolet->pompiste)
                                                {{ $pistolet->pompiste->prenom }} {{ $pistolet->pompiste->nom }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($pistolet->etat)
                                                @case('actif')      <span class="badge bg-success">Actif</span>@break
                                                @case('inactif')    <span class="badge bg-secondary">Inactif</span>@break
                                                @case('maintenance')<span class="badge bg-warning">Maintenance</span>@break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('pistolets.show', $pistolet) }}" class="btn btn-sm btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pistolets.edit', $pistolet) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pistolets.destroy', $pistolet) }}" method="POST"
                                                    onsubmit="return confirm('Supprimer ce pistolet ?')">
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
                                        <td colspan="8" class="text-center text-muted">Aucun pistolet enregistré</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $pistolets->links() }}</div>
                </div>
            </div>
        </div>
    </main>
@endsection
