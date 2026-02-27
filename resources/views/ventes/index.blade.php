@extends('layouts.master')

@section('title', 'Liste des Ventes')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="dollar-sign"></i></div>
                                Gestion des Ventes
                            </h1>
                            <p>
                                <small>Visualisez et gérez toutes les ventes de carburant enregistrées dans le
                                    système.</small>
                            </p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('ventes.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-2"></i>&nbsp; Nouvelle Vente
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <!-- Filtres -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('ventes.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date Début</label>
                            <input type="date" name="date_debut" class="form-control"
                                value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date Fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mode Paiement</label>
                            <select name="mode_paiement" class="form-select">
                                <option value="">Tous</option>
                                <option value="especes">Espèces</option>
                                <option value="carte">Carte</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="credit">Crédit</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-secondary btn-sm me-2">
                                <i class="fas fa-filter me-2"></i> Filtrer
                            </button>
                            <a href="{{ route('ventes.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-redo me-2"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>N° Vente</th>
                                    <th>Date</th>
                                    <th>Pistolet/Carburant</th>
                                    <th>Pompiste</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Montant</th>
                                    <th>Mode Paiement</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventes as $vente)
                                    <tr>
                                        <td>{{ $vente->numero_vente }}</td>
                                        <td>{{ $vente->date_vente->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <strong>{{ $vente->pistolet->nom }}</strong><br>
                                            <small class="text-muted">
                                                {{ $vente->pistolet->pompe->cuve->carburant->nom }}
                                            </small>
                                        </td>
                                        <td>{{ $vente->pompiste->prenom }} {{ $vente->pompiste->nom }}</td>
                                        <td>{{ number_format($vente->quantite, 2) }} L</td>
                                        <td>{{ number_format($vente->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                        <td><strong>{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</strong>
                                        </td>
                                        <td>
                                            @switch($vente->mode_paiement)
                                                @case('especes')
                                                    <span class="badge bg-success">Espèces</span>
                                                @break

                                                @case('carte')
                                                    <span class="badge bg-primary">Carte</span>
                                                @break

                                                @case('mobile_money')
                                                    <span class="badge bg-info">Mobile Money</span>
                                                @break

                                                @case('credit')
                                                    <span class="badge bg-warning">Crédit</span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('ventes.show', $vente) }}" class="btn btn-sm btn-info"
                                                    title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if (auth()->user()->isAdmin())
                                                    <form action="{{ route('ventes.destroy', $vente) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">Aucune vente enregistrée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th>{{ number_format($ventes->sum('quantite'), 2) }} L</th>
                                        <th></th>
                                        <th colspan="3">
                                            <strong>{{ number_format($ventes->sum('montant_total'), 0, ',', ' ') }}
                                                FCFA</strong></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $ventes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    @endsection
