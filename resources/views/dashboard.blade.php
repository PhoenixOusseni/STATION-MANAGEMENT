@extends('layouts.master')

@section('title', 'Dashboard')

@section('style')
<style>
    /* ── Fond général clair ── */
    #layoutSidenav_content {
        background: #f1f5f9 !important;
    }

    .dash-wrap {
        padding: 1.5rem;
        min-height: 100vh;
    }

    /* ── Topbar ── */
    .dash-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .dash-topbar .page-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #c41e3a;
        margin-bottom: .2rem;
    }
    .dash-topbar h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .dash-topbar .station-chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #c41e3a;
        font-size: .75rem;
        font-weight: 600;
        padding: .3rem .8rem;
        border-radius: 20px;
        margin-top: .35rem;
    }
    .dash-topbar .btn-new {
        background: linear-gradient(135deg, #c41e3a, #e05a72);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: .55rem 1.3rem;
        font-weight: 600;
        font-size: .85rem;
        box-shadow: 0 4px 15px rgba(196,30,58,.3);
        text-decoration: none;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }
    .dash-topbar .btn-new:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(196,30,58,.45);
        color: #fff;
    }

    /* ── KPI Cards ── */
    .kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.3rem 1.4rem;
        position: relative;
        overflow: hidden;
        transition: transform .22s, box-shadow .22s;
        box-shadow: 0 1px 6px rgba(0,0,0,.06);
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,.1);
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 14px 14px 0 0;
    }
    .kpi-card.kpi-red::before   { background: linear-gradient(90deg,#c41e3a,#e05a72); }
    .kpi-card.kpi-blue::before  { background: linear-gradient(90deg,#1d4ed8,#3b82f6); }
    .kpi-card.kpi-amber::before { background: linear-gradient(90deg,#b45309,#f59e0b); }
    .kpi-card.kpi-teal::before  { background: linear-gradient(90deg,#0d9488,#2dd4bf); }

    .kpi-card .kpi-icon {
        width: 44px; height: 44px;
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        margin-bottom: .9rem;
    }
    .kpi-red  .kpi-icon { background: #fef2f2; color: #c41e3a; }
    .kpi-blue .kpi-icon { background: #eff6ff; color: #1d4ed8; }
    .kpi-amber .kpi-icon { background: #fffbeb; color: #b45309; }
    .kpi-teal .kpi-icon { background: #f0fdfa; color: #0d9488; }

    .kpi-card .kpi-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #94a3b8;
        margin-bottom: .3rem;
    }
    .kpi-card .kpi-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }
    .kpi-card .kpi-value.small-val { font-size: 1.2rem; }
    .kpi-card .kpi-sub {
        font-size: .72rem;
        color: #94a3b8;
        margin-top: .35rem;
    }

    /* ── Light Card (charts & tables) ── */
    .light-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.06);
    }
    .light-card .lc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.1rem 1.4rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .light-card .lc-header .lc-title {
        display: flex;
        align-items: center;
        gap: .6rem;
        font-size: .9rem;
        font-weight: 700;
        color: #0f172a;
    }
    .light-card .lc-header .lc-title .lc-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #c41e3a;
        flex-shrink: 0;
    }
    .light-card .lc-header .lc-link {
        font-size: .75rem;
        color: #c41e3a;
        text-decoration: none;
        font-weight: 600;
        transition: color .15s;
    }
    .light-card .lc-header .lc-link:hover { color: #9b1628; }
    .light-card .lc-body { padding: 1.2rem 1.4rem; }
    .light-card .lc-body.p-0 { padding: 0; }

    .chart-wrap { position: relative; }

    /* ── Light Table ── */
    .light-table { width: 100%; font-size: .84rem; }
    .light-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        padding: .75rem 1.2rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .light-table tbody td {
        padding: .75rem 1.2rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .light-table tbody tr:last-child td { border-bottom: none; }
    .light-table tbody tr { transition: background .15s; }
    .light-table tbody tr:hover td { background: #fef2f2; }

    .vbadge {
        background: #fef2f2;
        color: #c41e3a;
        border: 1px solid #fecaca;
        border-radius: 6px;
        font-size: .72rem;
        font-weight: 700;
        padding: .25rem .6rem;
        white-space: nowrap;
    }
    .avatar-sm {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: #fef2f2;
        color: #c41e3a;
        font-size: .68rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .amount-cell { color: #16a34a; font-weight: 700; font-size: .85rem; }

    /* ── Entrées list ── */
    .entry-row {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: .75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .entry-row:last-child { border-bottom: none; }
    .entry-row .e-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: #fef2f2;
        color: #c41e3a;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
        flex-shrink: 0;
    }
    .entry-row .e-name { font-size: .85rem; font-weight: 600; color: #0f172a; }
    .entry-row .e-tank { font-size: .73rem; color: #94a3b8; }
    .entry-row .e-qty  { font-size: .85rem; font-weight: 700; color: #16a34a; }
    .entry-row .e-date { font-size: .7rem; color: #94a3b8; }

    /* ── Alert band ── */
    .alert-band {
        background: #fff;
        border: 1px solid #fecaca;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(220,53,69,.08);
    }
    .alert-band .ab-header {
        background: #fef2f2;
        padding: .85rem 1.4rem;
        display: flex;
        align-items: center;
        gap: .6rem;
        color: #c41e3a;
        font-weight: 700;
        font-size: .88rem;
        border-bottom: 1px solid #fecaca;
    }
    .alert-band .light-table thead th { background: #fff9f9; }

    /* ── Commandes card ── */
    .cmd-card {
        background: linear-gradient(135deg, #b45309, #d97706);
        border-radius: 14px;
        padding: 1.3rem;
        text-align: center;
        border: none;
        box-shadow: 0 4px 15px rgba(180,83,9,.25);
    }
    .cmd-card .cmd-count { font-size: 3rem; font-weight: 900; color: #fff; line-height: 1; }
    .cmd-card .cmd-label { color: rgba(255,255,255,.8); font-size: .82rem; margin: .35rem 0 .85rem; }
    .cmd-card .btn-cmd {
        background: rgba(255,255,255,.2);
        color: #fff;
        border: 1px solid rgba(255,255,255,.35);
        border-radius: 8px;
        padding: .45rem 1.1rem;
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        display: inline-block;
    }
    .cmd-card .btn-cmd:hover { background: rgba(255,255,255,.35); color: #fff; }

    /* ── Actions rapides ── */
    .qa-item {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .65rem .8rem;
        border-radius: 9px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        text-decoration: none;
        color: #334155;
        font-size: .83rem;
        font-weight: 500;
        transition: all .18s;
        margin-bottom: .4rem;
    }
    .qa-item:last-child { margin-bottom: 0; }
    .qa-item:hover {
        background: #fef2f2;
        border-color: #fecaca;
        color: #c41e3a;
        transform: translateX(3px);
    }
    .qa-item .qa-i {
        width: 32px; height: 32px;
        border-radius: 7px;
        background: #fef2f2;
        color: #c41e3a;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem;
        flex-shrink: 0;
    }

    /* ── Progress cuve ── */
    .cuve-prog { height: 8px; border-radius: 50px; background: #fee2e2; }
    .cuve-prog .bar { height: 100%; border-radius: 50px; background: #dc2626; }

    @media (max-width: 767px) {
        .dash-topbar h1 { font-size: 1.2rem; }
        .kpi-card .kpi-value { font-size: 1.3rem; }
    }
</style>
@endsection

@section('content')
<div class="dash-wrap">

    {{-- ── Topbar ── --}}
    <div class="dash-topbar">
        <div>
            <div class="page-label"><i class="fas fa-tachometer-alt me-1"></i>Tableau de bord</div>
            <h1>Bonjour, {{ $user->prenom }} {{ $user->nom }}</h1>
            @if($station)
                <div class="station-chip">
                    <i class="fas fa-gas-pump" style="font-size:.65rem;"></i>
                    {{ $station->nom }}
                </div>
            @endif
        </div>
        <a href="{{ route('ventes.create') }}" class="btn-new">
            <i class="fas fa-plus"></i> Nouvelle Vente
        </a>
    </div>

    {{-- ── KPI Row ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="kpi-card kpi-red">
                <div class="kpi-icon"><i class="fas fa-oil-can"></i></div>
                <div class="kpi-label">Cuves Totales</div>
                <div class="kpi-value">{{ $stats['total_cuves'] }}</div>
                <div class="kpi-sub">Cuves actives</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card kpi-amber">
                <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="kpi-label">Cuves en Alerte</div>
                <div class="kpi-value">{{ $stats['cuves_alerte'] }}</div>
                <div class="kpi-sub">Stock minimum atteint</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card kpi-blue">
                <div class="kpi-icon"><i class="fas fa-cash-register"></i></div>
                <div class="kpi-label">Ventes du Jour</div>
                <div class="kpi-value small-val">{{ number_format($stats['ventes_jour'], 0, ',', ' ') }}</div>
                <div class="kpi-sub">FCFA aujourd'hui</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card kpi-teal">
                <div class="kpi-icon"><i class="fas fa-chart-line"></i></div>
                <div class="kpi-label">Ventes du Mois</div>
                <div class="kpi-value small-val">{{ number_format($stats['ventes_mois'], 0, ',', ' ') }}</div>
                <div class="kpi-sub">FCFA ce mois</div>
            </div>
        </div>
    </div>

    {{-- ── Charts Row ── --}}
    <div class="row g-3 mb-4">

        {{-- Bar chart ventes --}}
        <div class="col-lg-8">
            <div class="light-card h-100">
                <div class="lc-header">
                    <div class="lc-title"><span class="lc-dot"></span>Évolution des Dernières Ventes</div>
                    <a href="{{ route('ventes.index') }}" class="lc-link">Voir tout <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="lc-body">
                    <div class="chart-wrap" style="height:260px;">
                        <canvas id="chartVentes"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Doughnut cuves --}}
        <div class="col-lg-4">
            <div class="light-card h-100">
                <div class="lc-header">
                    <div class="lc-title"><span class="lc-dot"></span>État des Cuves</div>
                </div>
                <div class="lc-body">
                    <div class="chart-wrap" style="height:200px;">
                        <canvas id="chartCuves"></canvas>
                    </div>
                    <div class="d-flex justify-content-center gap-4 mt-3">
                        <div class="text-center">
                            <div style="font-size:1.5rem;font-weight:800;color:#16a34a;">{{ $stats['total_cuves'] - $stats['cuves_alerte'] }}</div>
                            <div style="font-size:.7rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Normales</div>
                        </div>
                        <div style="width:1px;background:#e2e8f0;"></div>
                        <div class="text-center">
                            <div style="font-size:1.5rem;font-weight:800;color:#dc2626;">{{ $stats['cuves_alerte'] }}</div>
                            <div style="font-size:.7rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">En Alerte</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Cuves en alerte ── --}}
    @if($cuvesAlerte->count() > 0)
    <div class="mb-4">
        <div class="alert-band">
            <div class="ab-header">
                <i class="fas fa-exclamation-triangle"></i>
                {{ $cuvesAlerte->count() }} Cuve(s) en Alerte — Stock Minimum Atteint
            </div>

            <div style="padding:1.2rem 1.4rem 0;">
                <div class="chart-wrap" style="height:140px;">
                    <canvas id="chartAlerteCuves"></canvas>
                </div>
            </div>

            <div class="table-responsive" style="margin-top:.5rem;">
                <table class="light-table">
                    <thead>
                        <tr>
                            <th>Cuve</th>
                            <th>Carburant</th>
                            <th>Stock Actuel</th>
                            <th>Stock Min</th>
                            <th>Niveau</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuvesAlerte as $cuve)
                        <tr>
                            <td><strong>{{ $cuve->nom }}</strong></td>
                            <td><span style="color:#dc2626;font-size:.78rem;font-weight:600;">{{ $cuve->carburant->nom }}</span></td>
                            <td>{{ number_format($cuve->stock_actuel, 2) }} L</td>
                            <td>{{ number_format($cuve->stock_min, 2) }} L</td>
                            <td style="min-width:150px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="cuve-prog flex-grow-1">
                                        <div class="bar" style="width:{{ $cuve->pourcentageRemplissage() }}%;"></div>
                                    </div>
                                    <span style="font-size:.75rem;font-weight:700;color:#dc2626;white-space:nowrap;">{{ number_format($cuve->pourcentageRemplissage(),1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Tableau ventes + sidebar ── --}}
    <div class="row g-3">

        {{-- Dernières ventes --}}
        <div class="col-lg-8">
            <div class="light-card">
                <div class="lc-header">
                    <div class="lc-title"><span class="lc-dot"></span>Dernières Ventes</div>
                    <a href="{{ route('ventes.index') }}" class="lc-link">Tout voir</a>
                </div>
                <div class="lc-body p-0">
                    <div class="table-responsive">
                        <table class="light-table">
                            <thead>
                                <tr>
                                    <th>N° Vente</th>
                                    <th>Date</th>
                                    <th>Pistolet</th>
                                    <th>Pompiste</th>
                                    <th>Qté (L)</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dernieresVentes as $vente)
                                <tr>
                                    <td><span class="vbadge">{{ $vente->numero_vente }}</span></td>
                                    <td style="color:#94a3b8;font-size:.78rem;">{{ $vente->date_vente->format('d/m/Y H:i') }}</td>
                                    <td>{{ $vente->pistolet->nom }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar-sm">{{ strtoupper(substr($vente->pompiste->prenom,0,1).substr($vente->pompiste->nom,0,1)) }}</span>
                                            <span>{{ $vente->pompiste->prenom }} {{ $vente->pompiste->nom }}</span>
                                        </div>
                                    </td>
                                    <td style="color:#64748b;">{{ number_format($vente->quantite, 2) }}</td>
                                    <td class="amount-cell">{{ number_format($vente->montant_total, 0, ',', ' ') }} F</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5" style="color:#94a3b8;">
                                        <i class="fas fa-inbox fa-2x d-block mb-2" style="opacity:.3;"></i>
                                        Aucune vente enregistrée
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4 d-flex flex-column gap-3">

            {{-- Dernières Entrées --}}
            <div class="light-card">
                <div class="lc-header">
                    <div class="lc-title"><span class="lc-dot"></span>Dernières Entrées</div>
                </div>
                <div class="lc-body">
                    @forelse($dernieresEntrees as $entree)
                    <div class="entry-row">
                        <div class="e-icon"><i class="fas fa-tint"></i></div>
                        <div class="flex-grow-1">
                            <div class="e-name">{{ $entree->cuve->carburant->nom }}</div>
                            <div class="e-tank">{{ $entree->cuve->nom }}</div>
                        </div>
                        <div class="text-end">
                            <div class="e-qty">+{{ number_format($entree->quantite, 0) }} L</div>
                            <div class="e-date">{{ $entree->date_entree->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3" style="color:#94a3b8;">
                        <i class="fas fa-inbox fa-2x d-block mb-2" style="opacity:.3;"></i>
                        Aucune entrée
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Commandes en attente --}}
            @if($stats['commandes_attente'] > 0)
            <div class="cmd-card">
                <div class="cmd-count">{{ $stats['commandes_attente'] }}</div>
                <div class="cmd-label">commande(s) en attente<br>de validation</div>
                <a href="{{ route('commandes.index') }}" class="btn-cmd">
                    <i class="fas fa-eye me-1"></i>Voir les commandes
                </a>
            </div>
            @endif

            {{-- Actions rapides --}}
            <div class="light-card">
                <div class="lc-header">
                    <div class="lc-title"><span class="lc-dot"></span>Actions Rapides</div>
                </div>
                <div class="lc-body">
                    <a href="{{ route('ventes.create') }}" class="qa-item">
                        <span class="qa-i"><i class="fas fa-plus"></i></span> Nouvelle Vente
                    </a>
                    <a href="{{ route('session_ventes.index') }}" class="qa-item">
                        <span class="qa-i"><i class="fas fa-layer-group"></i></span> Sessions de Vente
                    </a>
                    <a href="{{ route('cuves.index') }}" class="qa-item">
                        <span class="qa-i"><i class="fas fa-oil-can"></i></span> Gérer les Cuves
                    </a>
                    <a href="{{ route('commandes.index') }}" class="qa-item">
                        <span class="qa-i"><i class="fas fa-shopping-cart"></i></span> Commandes
                    </a>
                    <a href="{{ route('entrees.index') }}" class="qa-item">
                        <span class="qa-i"><i class="fas fa-truck-loading"></i></span> Entrées de Stock
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = '#f1f5f9';
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";

    /* ── 1. Bar chart — Dernières Ventes ── */
    const ventesLabels = [
        @foreach(array_reverse($dernieresVentes->toArray()) as $v)
            "{{ \Carbon\Carbon::parse($v['date_vente'])->format('d/m H:i') }}",
        @endforeach
    ];
    const ventesValues = [
        @foreach(array_reverse($dernieresVentes->toArray()) as $v)
            {{ $v['montant_total'] }},
        @endforeach
    ];

    const ctxVentes = document.getElementById('chartVentes');
    if (ctxVentes && ventesLabels.length) {
        new Chart(ctxVentes, {
            type: 'bar',
            data: {
                labels: ventesLabels,
                datasets: [{
                    label: 'Montant (FCFA)',
                    data: ventesValues,
                    backgroundColor: 'rgba(196,30,58,0.12)',
                    borderColor: '#c41e3a',
                    borderWidth: 2,
                    borderRadius: 7,
                    hoverBackgroundColor: 'rgba(196,30,58,0.22)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        borderColor: '#334155',
                        borderWidth: 1,
                        titleColor: '#f1f5f9',
                        bodyColor: '#94a3b8',
                        callbacks: {
                            label: ctx => ' ' + ctx.parsed.y.toLocaleString('fr-FR') + ' FCFA'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, maxRotation: 45, color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { size: 10 }, color: '#94a3b8',
                            callback: v => (v >= 1000 ? (v/1000).toFixed(0)+'k' : v) + ' F'
                        }
                    }
                }
            }
        });
    } else if (ctxVentes) {
        ctxVentes.parentElement.innerHTML = '<p class="text-center py-5 text-muted">Aucune donnée disponible</p>';
    }

    /* ── 2. Doughnut — État des Cuves ── */
    const ctxCuves = document.getElementById('chartCuves');
    const total    = {{ $stats['total_cuves'] }};
    const enAlerte = {{ $stats['cuves_alerte'] }};
    if (ctxCuves && total > 0) {
        new Chart(ctxCuves, {
            type: 'doughnut',
            data: {
                labels: ['Normales', 'En Alerte'],
                datasets: [{
                    data: [total - enAlerte, enAlerte],
                    backgroundColor: ['rgba(22,163,74,0.15)', 'rgba(220,38,38,0.15)'],
                    borderColor:     ['#16a34a', '#dc2626'],
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 11 }, padding: 14, usePointStyle: true, color: '#64748b' }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        borderColor: '#334155',
                        borderWidth: 1,
                        titleColor: '#f1f5f9',
                        bodyColor: '#94a3b8',
                    }
                }
            }
        });
    }

    /* ── 3. Horizontal Bar — Niveaux cuves en alerte ── */
    @if($cuvesAlerte->count() > 0)
    const ctxAlerte = document.getElementById('chartAlerteCuves');
    if (ctxAlerte) {
        new Chart(ctxAlerte, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($cuvesAlerte as $c) "{{ $c->nom }}", @endforeach
                ],
                datasets: [{
                    label: 'Niveau (%)',
                    data: [
                        @foreach($cuvesAlerte as $c) {{ round($c->pourcentageRemplissage(), 1) }}, @endforeach
                    ],
                    backgroundColor: 'rgba(220,38,38,0.12)',
                    borderColor: '#dc2626',
                    borderWidth: 2,
                    borderRadius: 5,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        borderColor: '#334155',
                        borderWidth: 1,
                        titleColor: '#f1f5f9',
                        bodyColor: '#94a3b8',
                        callbacks: { label: ctx => ' ' + ctx.parsed.x + '%' }
                    }
                },
                scales: {
                    x: {
                        max: 100,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 10 }, color: '#94a3b8', callback: v => v + '%' }
                    },
                    y: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#64748b' } }
                }
            }
        });
    }
    @endif
});
</script>
@endsection
