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
                                Registre des Jaugeages
                            </h1>
                            <p><small>Historique de toutes les mesures physiques de carburant dans les cuves.</small></p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('jaugeages.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-2"></i>&nbsp; Nouveau Jaugeage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Type</th>
                                    <th>Cuve</th>
                                    <th>Carburant</th>
                                    <th class="text-end">Mesuré</th>
                                    <th class="text-end">Théorique</th>
                                    <th class="text-end">Écart</th>
                                    <th>Session</th>
                                    <th>Date</th>
                                    <th>Opérateur</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jaugeages as $jauge)
                                    <tr>
                                        <td><strong>{{ $jauge->numero_jaugeage }}</strong></td>
                                        <td>
                                            <span class="badge {{ $jauge->badgeClass() }}">
                                                {{ $jauge->libellType() }}
                                            </span>
                                        </td>
                                        <td>{{ $jauge->cuve->nom }}</td>
                                        <td>{{ $jauge->cuve->carburant->nom }}</td>
                                        <td class="text-end">{{ number_format($jauge->quantite_mesuree, 2) }}</td>
                                        <td class="text-end">{{ number_format($jauge->quantite_theorique, 2) }}</td>
                                        <td class="text-end">
                                            <span class="badge {{ $jauge->ecartBadgeClass() }}">
                                                {{ $jauge->ecart >= 0 ? '+' : '' }}{{ number_format($jauge->ecart, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($jauge->sessionVente)
                                                <a href="{{ route('session_ventes.show', $jauge->sessionVente) }}"
                                                    class="text-decoration-none">
                                                    {{ $jauge->sessionVente->numero_session }}
                                                </a>
                                            @elseif($jauge->entree)
                                                <a href="{{ route('entrees.show', $jauge->entree) }}"
                                                    class="text-decoration-none text-warning">
                                                    {{ $jauge->entree->numero_entree }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $jauge->date_jaugeage->format('d/m/Y H:i') }}</td>
                                        <td>{{ $jauge->user->prenom }} {{ $jauge->user->nom }}</td>
                                        <td>
                                            <a href="{{ route('jaugeages.show', $jauge) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">Aucun jaugeage enregistré</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $jaugeages->links() }}</div>
                </div>
            </div>
        </div>
    </main>
@endsection
