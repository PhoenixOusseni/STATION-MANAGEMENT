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
                                Session {{ $sessionVente->numero_session }}
                            </h1>
                            <p>
                                <small>
                                    Ouverte le {{ $sessionVente->date_debut->format('d/m/Y à H:i') }}
                                    par {{ $sessionVente->user->prenom }} {{ $sessionVente->user->nom }}
                                </small>
                            </p>
                        </div>
                        <div class="col-auto mt-4 d-flex gap-2 flex-wrap">
                            @if ($sessionVente->isEnCours())
                                <a href="{{ route('session_ventes.cloture', $sessionVente) }}"
                                    class="btn btn-warning btn-sm">
                                    <i data-feather="stop-circle" class="me-1"></i>Clôturer la Session
                                </a>
                            @endif
                            <a href="{{ route('session_ventes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                {{-- ─── Colonne principale ─── --}}
                <div class="col-lg-8">

                    {{-- Jaugeages --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark fw-bold">
                            <i data-feather="droplet" class="me-2"></i>Jaugeages
                        </div>
                        <div class="card-body p-3">
                            <table id="tableJaugeages" class="table table-sm table-hover table-bordered table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Cuve</th>
                                        <th>Carburant</th>
                                        <th class="text-end">Mesuré</th>
                                        <th class="text-end">Théorique</th>
                                        <th class="text-end">Écart</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sessionVente->jaugeages as $jauge)
                                        <tr>
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
                                            <td>{{ $jauge->date_jaugeage->format('d/m H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-muted text-center">Aucun jaugeage</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Index Pompes --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark fw-bold">
                            <i data-feather="activity" class="me-2"></i>Index des Pompes
                        </div>
                        <div class="card-body p-3">
                            <table id="tableIndexPompes" class="table table-sm table-hover table-bordered table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pompe</th>
                                        <th>Carburant</th>
                                        <th class="text-end">Index Départ</th>
                                        <th class="text-end">Index Final</th>
                                        <th class="text-end">Qté Compteur</th>
                                        <th>Relevé départ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sessionVente->indexPompes as $ip)
                                        <tr>
                                            <td>{{ $ip->pompe->nom }}</td>
                                            <td>{{ $ip->pompe->cuve->carburant->nom }}</td>
                                            <td class="text-end">{{ number_format($ip->index_depart, 2) }}</td>
                                            <td class="text-end">
                                                {{ $ip->index_final !== null ? number_format($ip->index_final, 2) : '-' }}
                                            </td>
                                            <td class="text-end">
                                                @if ($ip->quantite_vendue_compteur !== null)
                                                    <strong>{{ number_format($ip->quantite_vendue_compteur, 2) }}</strong>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $ip->date_releve_depart->format('d/m H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-muted text-center">Aucun index enregistré</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Ventes de la session --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark fw-bold">
                            <i data-feather="dollar-sign" class="me-2"></i>Ventes de la Session
                            <span class="badge bg-primary ms-2">{{ $sessionVente->ventes->count() }}</span>
                        </div>
                        <div class="card-body p-3">
                            <table id="tableVentes" class="table table-sm table-hover table-bordered table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Vente</th>
                                        <th>Date</th>
                                        <th>Carburant</th>
                                        <th class="text-end">Quantité (L)</th>
                                        <th class="text-end">Montant (FCFA)</th>
                                        <th>Paiement</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sessionVente->ventes as $vente)
                                        <tr>
                                            <td>{{ $vente->numero_vente }}</td>
                                            <td>{{ $vente->date_vente->format('d/m H:i') }}</td>
                                            <td>{{ $vente->pistolet->pompe->cuve->carburant->nom }}</td>
                                            <td class="text-end">{{ number_format($vente->quantite_vendue, 2) }}</td>
                                            <td class="text-end">{{ number_format($vente->montant_total, 0, ',', ' ') }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge
                                                    @switch($vente->mode_paiement)
                                                        @case('especes') bg-success @break
                                                        @case('carte') bg-primary @break
                                                        @case('mobile_money') bg-info @break
                                                        @case('credit') bg-warning @break
                                                    @endswitch">
                                                    {{ ucfirst(str_replace('_', ' ', $vente->mode_paiement)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('ventes.show', $vente) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-muted text-center">Aucune vente dans cette
                                                session</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($sessionVente->ventes->count() > 0)
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td colspan="3" class="text-end">Totaux :</td>
                                            <td class="text-end">
                                                {{ number_format($sessionVente->quantiteTotaleVendue(), 2) }} L</td>
                                            <td class="text-end">
                                                {{ number_format($sessionVente->montantTotal(), 0, ',', ' ') }} FCFA</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ─── Colonne latérale ─── --}}
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Récapitulatif</div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-6">N° Session</dt>
                                <dd class="col-sm-6">{{ $sessionVente->numero_session }}</dd>

                                <dt class="col-sm-6">Station</dt>
                                <dd class="col-sm-6">{{ $sessionVente->station->nom ?? '-' }}</dd>

                                <dt class="col-sm-6">Statut</dt>
                                <dd class="col-sm-6">
                                    @if ($sessionVente->isEnCours())
                                        <span class="badge bg-success">En cours</span>
                                    @else
                                        <span class="badge bg-secondary">Clôturée</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-6">Nb ventes</dt>
                                <dd class="col-sm-6">{{ $sessionVente->ventes->count() }}</dd>

                                <dt class="col-sm-6">Total ventes</dt>
                                <dd class="col-sm-6">
                                    <strong>{{ number_format($sessionVente->montantTotal(), 0, ',', ' ') }} FCFA</strong>
                                </dd>

                                <dt class="col-sm-6">Qté vendue</dt>
                                <dd class="col-sm-6"><strong>{{ number_format($sessionVente->quantiteTotaleVendue(), 2) }}
                                        L</strong></dd>
                            </dl>
                        </div>
                    </div>

                    @if ($sessionVente->observation)
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark">Observation</div>
                            <div class="card-body text-muted">{{ $sessionVente->observation }}</div>
                        </div>
                    @endif

                    @if ($sessionVente->isEnCours())
                        <div class="card">
                            <div class="card-header bg-light text-dark">Actions</div>
                            <div class="card-body">
                                <a href="{{ route('session_ventes.cloture', $sessionVente) }}"
                                    class="btn btn-warning btn-sm w-100">
                                    <i data-feather="stop-circle" class="me-2"></i>Clôturer la Session
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ['tableJaugeages', 'tableIndexPompes', 'tableVentes'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) {
                    new simpleDatatables.DataTable(el, { searchable: true, fixedHeight: false });
                }
            });
        });
    </script>
@endsection
