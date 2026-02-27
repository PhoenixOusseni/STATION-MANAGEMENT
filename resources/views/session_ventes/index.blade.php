@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="play-circle"></i></div>
                                Sessions de Vente
                            </h1>
                            <p><small>Gestion des sessions de vente (ouverture, jaugeages, index pompes, clôture).</small></p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('session_ventes.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-2"></i>&nbsp; Ouvrir une Session
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>N° Session</th>
                                    <th>Station</th>
                                    <th>Ouvert par</th>
                                    <th>Date Début</th>
                                    <th>Date Fin</th>
                                    <th>Ventes</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                    <tr>
                                        <td><strong>{{ $session->numero_session }}</strong></td>
                                        <td>{{ $session->station->nom ?? '-' }}</td>
                                        <td>{{ $session->user->prenom }} {{ $session->user->nom }}</td>
                                        <td>{{ $session->date_debut->format('d/m/Y H:i') }}</td>
                                        <td>{{ $session->date_fin ? $session->date_fin->format('d/m/Y H:i') : '-' }}</td>
                                        <td>{{ $session->ventes_count }}</td>
                                        <td>
                                            @if($session->isEnCours())
                                                <span class="badge bg-success">En cours</span>
                                            @else
                                                <span class="badge bg-secondary">Clôturée</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('session_ventes.show', $session) }}"
                                                    class="btn btn-sm btn-info" title="Détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($session->isEnCours())
                                                    <a href="{{ route('session_ventes.cloture', $session) }}"
                                                        class="btn btn-sm btn-warning" title="Clôturer">
                                                        <i class="fas fa-stop-circle"></i>
                                                    </a>
                                                @endif
                                                @if(auth()->user()->isAdmin() && $session->ventes_count == 0)
                                                    <form action="{{ route('session_ventes.destroy', $session) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Supprimer cette session ?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Aucune session enregistrée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $sessions->links() }}</div>
                </div>
            </div>
        </div>
    </main>
@endsection
