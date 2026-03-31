@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="book-open"></i></div>
                                Livre Journal
                            </h1>
                            <p><small>État journalier des stocks par cuve — jaugeages, ventes, entrées et écarts.</small>
                            </p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('livre_journal.print', ['date' => $date, 'station_id' => $stationId]) }}"
                               target="_blank" class="btn btn-light btn-sm">
                                <i class="fas fa-print me-2"></i> Imprimer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">

            {{-- Filtre --}}
            <div class="card mb-4 no-print">
                <div class="card-body">
                    <form method="GET" action="{{ route('livre_journal.index') }}" class="row g-3 align-items-end">
                        @if ($stations)
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Station</label>
                                <select name="station_id" class="form-select form-select-sm">
                                    <option value="">Toutes les stations</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ $stationId == $station->id ? 'selected' : '' }}>
                                            {{ $station->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Date début</label>
                            <input type="date" name="date" class="form-control form-control-sm"
                                value="{{ $date }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-sync-alt me-1"></i> Rafraîchir
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tableau principal --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="fw-bold">
                        <i data-feather="calendar" class="me-1" style="width:16px;height:16px;"></i>
                        Journée du {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('D MMMM YYYY') }}
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0 text-center align-middle" id="livreJournalTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-start">Cuve</th>
                                    <th>Vente du Jour</th>
                                    <th>Jauge Début</th>
                                    <th>Qté Reçue</th>
                                    <th>Bordereau</th>
                                    <th>Stock Total</th>
                                    <th>Stock Théorique</th>
                                    <th>Jauge Fin</th>
                                    <th>Écart de Stock</th>
                                    <th>Cumul Écart</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lignes as $ligne)
                                    <tr>
                                        <td class="text-start fw-semibold">{{ $ligne['cuve'] }}</td>
                                        <td>{{ number_format($ligne['vente_jour'], 1) }}</td>
                                        <td>{{ number_format($ligne['jauge_debut'], 0) }}</td>
                                        <td>{{ number_format($ligne['qte_recu'], 1) }}</td>
                                        <td class="text-muted small">{{ $ligne['bordereau'] ?: '-' }}</td>
                                        <td>{{ number_format($ligne['stock_total'], 1) }}</td>
                                        <td>{{ number_format($ligne['stock_theorique'], 0) }}</td>
                                        <td>{{ number_format($ligne['jauge_fin'], 0) }}</td>
                                        <td>
                                            <span
                                                class="fw-semibold {{ $ligne['ecart_stock'] < 0 ? 'text-danger' : ($ligne['ecart_stock'] > 0 ? 'text-success' : 'text-secondary') }}">
                                                {{ number_format($ligne['ecart_stock'], 0) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($ligne['cumul_ecart'], 1) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            Aucune donnée pour cette date.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if (count($lignes) > 0)
                                <tfoot class="table-secondary fw-bold">
                                    <tr>
                                        <td class="text-start">TOTAL</td>
                                        <td>{{ number_format($totals['vente_jour'], 1) }}</td>
                                        <td>{{ number_format($totals['jauge_debut'], 1) }}</td>
                                        <td>{{ number_format($totals['qte_recu'], 1) }}</td>
                                        <td></td>
                                        <td>{{ number_format($totals['stock_total'], 1) }}</td>
                                        <td>{{ number_format($totals['stock_theorique'], 1) }}</td>
                                        <td>{{ number_format($totals['jauge_fin'], 1) }}</td>
                                        <td>
                                            <span
                                                class="{{ $totals['ecart_stock'] < 0 ? 'text-danger' : ($totals['ecart_stock'] > 0 ? 'text-success' : '') }}">
                                                {{ number_format($totals['ecart_stock'], 1) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($totals['cumul_ecart'], 1) }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{-- Récapitulatif Écarts --}}
            @if (count($lignes) > 0)
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="small fw-semibold text-muted text-uppercase mb-1">Somme écart de stock
                                        </div>
                                        <div
                                            class="h4 fw-bold {{ $totals['ecart_stock'] < 0 ? 'text-danger' : ($totals['ecart_stock'] > 0 ? 'text-success' : 'text-secondary') }}">
                                            {{ number_format($totals['ecart_stock'], 1) }}
                                        </div>
                                    </div>
                                    <div
                                        class="rounded-circle p-3 {{ $totals['ecart_stock'] < 0 ? 'bg-danger' : ($totals['ecart_stock'] > 0 ? 'bg-success' : 'bg-secondary') }} bg-opacity-10">
                                        <i data-feather="trending-{{ $totals['ecart_stock'] >= 0 ? 'up' : 'down' }}"
                                            class="{{ $totals['ecart_stock'] < 0 ? 'text-danger' : 'text-success' }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="small fw-semibold text-muted text-uppercase mb-1">Somme cumul des écarts
                                        </div>
                                        <div
                                            class="h4 fw-bold {{ $totals['cumul_ecart'] < 0 ? 'text-danger' : ($totals['cumul_ecart'] > 0 ? 'text-success' : 'text-secondary') }}">
                                            {{ number_format($totals['cumul_ecart'], 1) }}
                                        </div>
                                    </div>
                                    <div class="rounded-circle p-3 bg-info bg-opacity-10">
                                        <i data-feather="layers" class="text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </main>

    {{-- Styles impression --}}
    @push('styles')
        <style>
            @media print {
                .no-print {
                    display: none !important;
                }

                #layoutSidenav_nav,
                .topnav,
                .page-header {
                    display: none !important;
                }

                #layoutSidenav_content {
                    margin: 0 !important;
                    padding: 0 !important;
                }

                .container-xl {
                    max-width: 100% !important;
                    padding: 0 !important;
                }

                .card {
                    border: 1px solid #ccc !important;
                    box-shadow: none !important;
                }

                .mt-n10 {
                    margin-top: 0 !important;
                }

                h4.fw-bold {
                    font-size: 1rem;
                }
            }
        </style>
    @endpush
@endsection
