@extends('layouts.master')

@section('content')
    <main>
        {{-- ══════════════════════════════ HEADER ══════════════════════════════ --}}
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="bar-chart-2"></i></div>
                                Statistiques & Analyses
                            </h1>
                            <p class="text-white-50">Vue d'ensemble des performances — Ventes · Entrées · Commandes · Cuves
                            </p>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-dark btn-sm">
                                <i data-feather="home" class="me-1"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">

            {{-- ══════════════════════════ FILTRES ══════════════════════════════ --}}
            <div class="card mb-4">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('statistiques.index') }}" class="row g-2 align-items-end">
                        @if (auth()->user()->isAdmin())
                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1">Station</label>
                                <select name="station_id" class="form-select form-select-sm">
                                    <option value="">Toutes les stations</option>
                                    @foreach ($stations as $s)
                                        <option value="{{ $s->id }}"
                                            {{ $stationSelectId == $s->id ? 'selected' : '' }}>{{ $s->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1">Station</label>
                                <input type="hidden" name="station_id" value="{{ auth()->user()->station_id }}">
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ auth()->user()->station->nom ?? 'N/A' }}" disabled>
                            </div>
                        @endif
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Année</label>
                            <select name="annee" class="form-select form-select-sm">
                                @foreach ($annees as $a)
                                    <option value="{{ $a }}" {{ $annee == $a ? 'selected' : '' }}>
                                        {{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold mb-1">Mois (optionnel)</label>
                            <select name="mois" class="form-select form-select-sm">
                                <option value="">Toute l'année</option>
                                @foreach ($moisNoms as $num => $nom)
                                    <option value="{{ $num }}" {{ $mois == $num ? 'selected' : '' }}>
                                        {{ ucfirst($nom) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i data-feather="filter" class="me-1"></i> Filtrer
                            </button>
                            <a href="{{ route('statistiques.index') }}" class="btn btn-light btn-sm ms-1">
                                <i data-feather="x" class="me-1"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ══════════════════════════ KPI CARDS ════════════════════════════ --}}
            <div class="row g-3 mb-4">
                {{-- Chiffre d'affaires --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3 rounded-circle d-flex align-items-center justify-content-center"
                                style="width:52px;height:52px;background:rgba(13,110,253,.12)">
                                <i data-feather="trending-up" style="color:#0d6efd;width:22px;height:22px;"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-semibold text-uppercase mb-1">Chiffre d'affaires</div>
                                <div class="fs-5 fw-bold">{{ number_format($kpi['ventes_total'], 0, ',', ' ') }} <small
                                        class="fs-6 fw-normal text-muted">FCFA</small></div>
                                <div class="small text-muted">{{ number_format($kpi['ventes_count'], 0) }} transaction(s)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Volume vendu --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3 rounded-circle d-flex align-items-center justify-content-center"
                                style="width:52px;height:52px;background:rgba(25,135,84,.12)">
                                <i data-feather="droplet" style="color:#198754;width:22px;height:22px;"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-semibold text-uppercase mb-1">Volume Vendu</div>
                                <div class="fs-5 fw-bold">{{ number_format($kpi['ventes_quantite'], 0, ',', ' ') }} <small
                                        class="fs-6 fw-normal text-muted">L</small></div>
                                <div class="small text-muted">Volume total distribué</div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Entrées --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3 rounded-circle d-flex align-items-center justify-content-center"
                                style="width:52px;height:52px;background:rgba(13,202,240,.12)">
                                <i data-feather="arrow-down-circle" style="color:#0dcaf0;width:22px;height:22px;"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-semibold text-uppercase mb-1">Entrées (Réceptions)</div>
                                <div class="fs-5 fw-bold">{{ number_format($kpi['entrees_quantite'], 0, ',', ' ') }} <small
                                        class="fs-6 fw-normal text-muted">L</small></div>
                                <div class="small text-muted">{{ number_format($kpi['entrees_total'], 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Commandes --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3 rounded-circle d-flex align-items-center justify-content-center"
                                style="width:52px;height:52px;background:rgba(255,193,7,.12)">
                                <i data-feather="shopping-cart" style="color:#ffc107;width:22px;height:22px;"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-semibold text-uppercase mb-1">Commandes</div>
                                <div class="fs-5 fw-bold">{{ $kpi['commandes_count'] }} <small
                                        class="fs-6 fw-normal text-muted">total</small></div>
                                <div class="small text-warning fw-semibold">{{ $kpi['commandes_attente'] }} en attente
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════ GRAPHIQUE 1 — Ventes mensuelles ══════════════════ --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-8">
                    <div class="card h-100 border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h6 class="mb-0 fw-bold">Ventes Mensuelles {{ $annee }}</h6>
                                <small class="text-muted">Chiffre d'affaires (FCFA) · Quantité (L)</small>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary">Barres + Ligne</span>
                        </div>
                        <div class="card-body">
                            <canvas id="chartVentesMensuelles" height="100"></canvas>
                        </div>
                    </div>
                </div>

                {{-- GRAPHIQUE 3 — Répartition carburant (Donut) --}}
                <div class="col-xl-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h6 class="mb-0 fw-bold">Ventes par Carburant</h6>
                                <small class="text-muted">Répartition du CA</small>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success">Donut</span>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            @if ($ventesByCarburant->count())
                                <canvas id="chartCarburant" height="200"></canvas>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i data-feather="pie-chart" style="width:40px;height:40px;opacity:.3"></i>
                                    <p class="mt-2 small">Aucune donnée pour cette période</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════ GRAPHIQUE 2 — Entrées mensuelles ════════════════ --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h6 class="mb-0 fw-bold">Réceptions Mensuelles {{ $annee }}</h6>
                                <small class="text-muted">Volume reçu (Litres)</small>
                            </div>
                            <span class="badge bg-info bg-opacity-10 text-info">Aire</span>
                        </div>
                        <div class="card-body">
                            <canvas id="chartEntrees" height="140"></canvas>
                        </div>
                    </div>
                </div>

                {{-- GRAPHIQUE 4 — Commandes par statut --}}
                <div class="col-xl-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h6 class="mb-0 fw-bold">Commandes par Statut</h6>
                                <small class="text-muted">Nombre & Montant</small>
                            </div>
                            <span class="badge bg-warning bg-opacity-10 text-warning">Barres</span>
                        </div>
                        <div class="card-body">
                            @if ($commandesByStatut->count())
                                <canvas id="chartCommandes" height="140"></canvas>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i data-feather="shopping-cart" style="width:40px;height:40px;opacity:.3"></i>
                                    <p class="mt-2 small">Aucune commande pour cette période</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════ GRAPHIQUE 5 — Stock cuves ════════════════════════ --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-8">
                    <div class="card h-100 border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h6 class="mb-0 fw-bold">Stock Actuel des Cuves</h6>
                                <small class="text-muted">Stock actuel vs capacité maximale</small>
                            </div>
                            <span class="badge bg-danger bg-opacity-10 text-danger">Barres horizontales</span>
                        </div>
                        <div class="card-body">
                            @if ($cuves->count())
                                <canvas id="chartCuves" height="{{ max(80, $cuves->count() * 28) }}"></canvas>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i data-feather="database" style="width:40px;height:40px;opacity:.3"></i>
                                    <p class="mt-2 small">Aucune cuve trouvée</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- GRAPHIQUE 6 — Top jours --}}
                <div class="col-xl-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-bold">Top Jours de Ventes</h6>
                            <small class="text-muted">{{ ucfirst($moisNoms[$moisTop]) }} {{ $annee }}</small>
                        </div>
                        <div class="card-body">
                            @if ($topJours->count())
                                <canvas id="chartTopJours" height="220"></canvas>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i data-feather="calendar" style="width:40px;height:40px;opacity:.3"></i>
                                    <p class="mt-2 small">Aucune vente ce mois</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════ TABLEAU — Récapitulatif par carburant ═══════════ --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold">Récapitulatif par Carburant</h6>
                    <small class="text-muted">Ventes · Entrées · Stock actuel</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Carburant</th>
                                    <th class="text-end">CA Ventes (FCFA)</th>
                                    <th class="text-end">Vol. Vendu (L)</th>
                                    <th class="text-end">Vol. Reçu (L)</th>
                                    <th class="text-end">Stock Actuel (L)</th>
                                    <th class="text-center">Cuves</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recapCarburants as $c)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $c->nom }}</span>
                                            @if ($c->code)
                                                <small class="text-muted ms-1">({{ $c->code }})</small>
                                            @endif
                                        </td>
                                        <td class="text-end fw-semibold text-primary">
                                            {{ number_format($c->ventes_montant, 0, ',', ' ') }}</td>
                                        <td class="text-end">{{ number_format($c->ventes_quantite, 0, ',', ' ') }}</td>
                                        <td class="text-end text-info">
                                            {{ number_format($c->entrees_quantite, 0, ',', ' ') }}</td>
                                        <td class="text-end">
                                            <span
                                                class="{{ $c->stock_actuel > 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                                {{ number_format($c->stock_actuel, 0, ',', ' ') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $c->cuves_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Aucune donnée disponible.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>{{-- /container --}}
    </main>

    {{-- ════════════════════════ CHART.JS ════════════════════════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
        Chart.defaults.color = '#6b7280';

        const MONTHS = @json($labelsVentes);
        const palette = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'];

        // ── 1. Ventes Mensuelles (Bar + Line) ─────────────────────────────────
        new Chart(document.getElementById('chartVentesMensuelles'), {
            data: {
                labels: MONTHS,
                datasets: [{
                        type: 'bar',
                        label: 'CA (FCFA)',
                        data: @json($dataCA),
                        backgroundColor: 'rgba(59,130,246,.7)',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        borderRadius: 4,
                        yAxisID: 'yCA',
                    },
                    {
                        type: 'line',
                        label: 'Quantité (L)',
                        data: @json($dataQte),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 4,
                        yAxisID: 'yQte',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                const v = ctx.parsed.y;
                                return ctx.dataset.yAxisID === 'yCA' ?
                                    ` ${ctx.dataset.label}: ${v.toLocaleString('fr-FR')} FCFA` :
                                    ` ${ctx.dataset.label}: ${v.toLocaleString('fr-FR')} L`;
                            }
                        }
                    }
                },
                scales: {
                    yCA: {
                        type: 'linear',
                        position: 'left',
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            callback: v => (v / 1000).toFixed(0) + 'k'
                        }
                    },
                    yQte: {
                        type: 'linear',
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: v => v.toLocaleString('fr-FR') + 'L'
                        }
                    }
                }
            }
        });

        // ── 2. Réceptions Mensuelles (Area) ───────────────────────────────────
        new Chart(document.getElementById('chartEntrees'), {
            type: 'line',
            data: {
                labels: MONTHS,
                datasets: [{
                        label: 'Volume Reçu (L)',
                        data: @json($dataEntreesQte),
                        borderColor: '#0dcaf0',
                        backgroundColor: 'rgba(13,202,240,.15)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0dcaf0',
                        pointRadius: 4,
                        yAxisID: 'yQte',
                    },
                    {
                        label: 'Montant (FCFA)',
                        data: @json($dataEntreesMontant),
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99,102,241,.08)',
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointRadius: 4,
                        yAxisID: 'yMontant',
                        borderDash: [5, 3],
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    yQte: {
                        type: 'linear',
                        position: 'left',
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            callback: v => v.toLocaleString('fr-FR') + 'L'
                        }
                    },
                    yMontant: {
                        type: 'linear',
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: v => (v / 1000).toFixed(0) + 'k'
                        }
                    }
                }
            }
        });

        // ── 3. Carburant Donut ────────────────────────────────────────────────
        @if ($ventesByCarburant->count())
            new Chart(document.getElementById('chartCarburant'), {
                type: 'doughnut',
                data: {
                    labels: @json($ventesByCarburant->pluck('carburant')),
                    datasets: [{
                        data: @json($ventesByCarburant->pluck('total')->map(fn($v) => round($v, 0))),
                        backgroundColor: palette,
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 16,
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${ctx.parsed.toLocaleString('fr-FR')} FCFA`
                            }
                        }
                    }
                }
            });
        @endif

        // ── 4. Commandes par statut ───────────────────────────────────────────
        @if ($commandesByStatut->count())
            const statutLabels = {
                en_attente: 'En Attente',
                validee: 'Validée',
                livree: 'Livrée',
                annulee: 'Annulée'
            };
            const statutColors = {
                en_attente: '#f59e0b',
                validee: '#3b82f6',
                livree: '#10b981',
                annulee: '#ef4444'
            };
            const cmdData = @json($commandesByStatut);
            new Chart(document.getElementById('chartCommandes'), {
                type: 'bar',
                data: {
                    labels: cmdData.map(d => statutLabels[d.statut] ?? d.statut),
                    datasets: [{
                            label: 'Nombre',
                            data: cmdData.map(d => d.total),
                            backgroundColor: cmdData.map(d => statutColors[d.statut] ?? '#6b7280'),
                            borderRadius: 4,
                            yAxisID: 'yNb',
                        },
                        {
                            label: 'Montant (FCFA)',
                            type: 'line',
                            data: cmdData.map(d => d.montant),
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99,102,241,.1)',
                            pointBackgroundColor: '#6366f1',
                            pointRadius: 5,
                            tension: 0.3,
                            yAxisID: 'yMt',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        yNb: {
                            type: 'linear',
                            position: 'left',
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        yMt: {
                            type: 'linear',
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                callback: v => (v / 1000).toFixed(0) + 'k'
                            }
                        }
                    }
                }
            });
        @endif

        // ── 5. Stock Cuves (horizontal bar) ──────────────────────────────────
        @if ($cuves->count())
            const cuveData = @json($cuveChartData);

            new Chart(document.getElementById('chartCuves'), {
                type: 'bar',
                data: {
                    labels: cuveData.map(d => d.nom),
                    datasets: [{
                            label: 'Stock Actuel (L)',
                            data: cuveData.map(d => d.stock),
                            backgroundColor: cuveData.map(d =>
                                d.stock <= d.min ? 'rgba(239,68,68,.8)' :
                                d.pct < 50 ? 'rgba(245,158,11,.8)' :
                                'rgba(16,185,129,.8)'
                            ),
                            borderRadius: 3,
                        },
                        {
                            label: 'Capacité Max (L)',
                            data: cuveData.map(d => d.max),
                            backgroundColor: 'rgba(209,213,219,.4)',
                            borderRadius: 3,
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                afterBody: (items) => {
                                    const i = items[0].dataIndex;
                                    const d = cuveData[i];
                                    return [`Remplissage: ${d.pct}%`,
                                        `Stock min: ${d.min.toLocaleString('fr-FR')} L`
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: v => v.toLocaleString('fr-FR') + 'L'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        @endif

        // ── 6. Top jours ─────────────────────────────────────────────────────
        @if ($topJours->count())
            new Chart(document.getElementById('chartTopJours'), {
                type: 'bar',
                data: {
                    labels: @json($topJours->pluck('jour')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))),
                    datasets: [{
                        label: 'CA (FCFA)',
                        data: @json($topJours->pluck('ca')->map(fn($v) => round($v, 0))),
                        backgroundColor: palette,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y.toLocaleString('fr-FR')} FCFA`
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: v => (v / 1000).toFixed(0) + 'k'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endsection
