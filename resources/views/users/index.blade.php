@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Gestion des Utilisateurs
                        </h1>
                    </div>
                    <div class="col-auto mt-4">
                        @if(auth()->user()->isAdmin() || auth()->user()->isGestionnaire())
                            <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">
                                <i data-feather="plus"></i> Nouvel Utilisateur
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card">
            <div class="card-header bg-light text-dark">
                Liste des Utilisateurs
                <div class="dropdown float-end">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i data-feather="filter"></i> Filtrer par rôle
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('users.index') }}">Tous</a></li>
                        <li><a class="dropdown-item" href="{{ route('users.index', ['role' => 'admin']) }}">Administrateurs</a></li>
                        <li><a class="dropdown-item" href="{{ route('users.index', ['role' => 'gestionnaire']) }}">Gestionnaires</a></li>
                        <li><a class="dropdown-item" href="{{ route('users.index', ['role' => 'pompiste']) }}">Pompistes</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-hover table-bordered table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom Complet</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Station</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <div class="avatar-initials bg-primary">
                                            {{ strtoupper(substr($user->prenom, 0, 1) . substr($user->nom, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $user->prenom }} {{ $user->nom }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger">Administrateur</span>
                                        @break
                                    @case('gestionnaire')
                                        <span class="badge bg-info">Gestionnaire</span>
                                        @break
                                    @case('pompiste')
                                        <span class="badge bg-success">Pompiste</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $user->station ? $user->station->nom : 'Non assigné' }}</td>
                            <td>
                                @if($user->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i data-feather="eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin() || (auth()->user()->isGestionnaire() && $user->role != 'admin'))
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @if($user->id != auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun utilisateur trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</main>

<style>
.avatar {
    width: 40px;
    height: 40px;
    display: inline-block;
}
.avatar-initials {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
    font-weight: bold;
}
</style>
@endsection
