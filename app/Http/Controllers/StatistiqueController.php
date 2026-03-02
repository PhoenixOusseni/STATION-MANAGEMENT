<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Entree;
use App\Models\Commande;
use App\Models\Cuve;
use App\Models\Station;
use App\Models\Carburant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $annee = $request->input('annee', now()->year);
        $mois  = $request->input('mois');       // null = toute l'année
        $stationFiltreId = $request->input('station_id'); // admin seulement

        // ── Scope station ──────────────────────────────────────────────
        if ($user->isAdmin()) {
            $stations       = Station::all();
            $stationIds     = $stationFiltreId
                ? [(int)$stationFiltreId]
                : $stations->pluck('id')->toArray();
            $stationSelectId = $stationFiltreId;
        } else {
            $stations        = Station::where('id', $user->station_id)->get();
            $stationIds      = [$user->station_id];
            $stationSelectId = $user->station_id;
        }

        // ── Helpers de période ─────────────────────────────────────────
        $ventesQuery    = fn() => Vente::whereIn('station_id', $stationIds)->whereYear('date_vente', $annee);
        $entreesQuery   = fn() => Entree::whereHas('cuve', fn($q) => $q->whereIn('station_id', $stationIds))->whereYear('date_entree', $annee);
        $commandesQuery = fn() => Commande::whereIn('station_id', $stationIds)->whereYear('date_commande', $annee);

        if ($mois) {
            $ventesQuery    = fn() => Vente::whereIn('station_id', $stationIds)->whereYear('date_vente', $annee)->whereMonth('date_vente', $mois);
            $entreesQuery   = fn() => Entree::whereHas('cuve', fn($q) => $q->whereIn('station_id', $stationIds))->whereYear('date_entree', $annee)->whereMonth('date_entree', $mois);
            $commandesQuery = fn() => Commande::whereIn('station_id', $stationIds)->whereYear('date_commande', $annee)->whereMonth('date_commande', $mois);
        }

        // ═══════════════════════════════════════════════════════════════
        // CARTES KPI
        // ═══════════════════════════════════════════════════════════════
        $kpi = [
            'ventes_total'        => $ventesQuery()->sum('montant_total'),
            'ventes_quantite'     => $ventesQuery()->sum('quantite'),
            'ventes_count'        => $ventesQuery()->count(),
            'entrees_total'       => $entreesQuery()->sum('montant_total'),
            'entrees_quantite'    => $entreesQuery()->sum('quantite'),
            'commandes_count'     => $commandesQuery()->count(),
            'commandes_montant'   => $commandesQuery()->sum('montant_total'),
            'commandes_attente'   => Commande::whereIn('station_id', $stationIds)->where('statut', 'en_attente')->count(),
        ];

        // ═══════════════════════════════════════════════════════════════
        // GRAPHIQUE 1 — Ventes mensuelles (CA + Quantité) — 12 mois
        // ═══════════════════════════════════════════════════════════════
        $ventesParMois = Vente::whereIn('station_id', $stationIds)
            ->whereYear('date_vente', $annee)
            ->select(
                DB::raw('MONTH(date_vente) as mois'),
                DB::raw('SUM(montant_total) as ca'),
                DB::raw('SUM(quantite) as qte')
            )
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->keyBy('mois');

        $labelsVentes = [];
        $dataCA       = [];
        $dataQte      = [];
        for ($m = 1; $m <= 12; $m++) {
            $labelsVentes[] = Carbon::create($annee, $m, 1)->locale('fr')->isoFormat('MMMM');
            $dataCA[]       = round($ventesParMois[$m]->ca  ?? 0, 0);
            $dataQte[]      = round($ventesParMois[$m]->qte ?? 0, 2);
        }

        // ═══════════════════════════════════════════════════════════════
        // GRAPHIQUE 2 — Entrées mensuelles (Volume + Montant)
        // ═══════════════════════════════════════════════════════════════
        $entreesParMois = Entree::whereHas('cuve', fn($q) => $q->whereIn('station_id', $stationIds))
            ->whereYear('date_entree', $annee)
            ->select(
                DB::raw('MONTH(date_entree) as mois'),
                DB::raw('SUM(montant_total) as montant'),
                DB::raw('SUM(quantite) as qte')
            )
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->keyBy('mois');

        $dataEntreesMontant = [];
        $dataEntreesQte     = [];
        for ($m = 1; $m <= 12; $m++) {
            $dataEntreesMontant[] = round($entreesParMois[$m]->montant ?? 0, 0);
            $dataEntreesQte[]     = round($entreesParMois[$m]->qte ?? 0, 2);
        }

        // ═══════════════════════════════════════════════════════════════
        // GRAPHIQUE 3 — Ventes par carburant (donut)
        // ═══════════════════════════════════════════════════════════════
        $ventesByCarburant = Vente::whereIn('ventes.station_id', $stationIds)
            ->whereYear('ventes.date_vente', $annee)
            ->join('pistolets', 'ventes.pistolet_id', '=', 'pistolets.id')
            ->join('pompes',    'pistolets.pompe_id',  '=', 'pompes.id')
            ->join('cuves',     'pompes.cuve_id',      '=', 'cuves.id')
            ->join('carburants','cuves.carburant_id',  '=', 'carburants.id')
            ->select('carburants.nom as carburant', DB::raw('SUM(ventes.montant_total) as total'), DB::raw('SUM(ventes.quantite) as qte'))
            ->groupBy('carburants.nom')
            ->get();

        // ═══════════════════════════════════════════════════════════════
        // GRAPHIQUE 4 — Commandes par statut (bar horizontal)
        // ═══════════════════════════════════════════════════════════════
        $commandesByStatut = Commande::whereIn('station_id', $stationIds)
            ->whereYear('date_commande', $annee)
            ->select('statut', DB::raw('COUNT(*) as total'), DB::raw('SUM(montant_total) as montant'))
            ->groupBy('statut')
            ->get();

        // ═══════════════════════════════════════════════════════════════
        // GRAPHIQUE 5 — Stock actuel des cuves (par station filtrée)
        // ═══════════════════════════════════════════════════════════════
        $cuves = Cuve::whereIn('station_id', $stationIds)
            ->with(['carburant', 'station'])
            ->get();

        $cuveChartData = $cuves->map(function ($c) {
            $capaciteMax = (float) $c->capacite_max;
            return [
                'nom'   => $c->nom . ' (' . ($c->carburant->code ?? $c->carburant->nom) . ')',
                'stock' => round((float) $c->stock_actuel, 2),
                'max'   => round($capaciteMax, 2),
                'min'   => round((float) $c->stock_min, 2),
                'pct'   => $capaciteMax > 0 ? round($c->stock_actuel / $capaciteMax * 100, 1) : 0,
            ];
        })->values();

        // ═══════════════════════════════════════════════════════════════
        // GRAPHIQUE 6 — Top 5 jours de ventes (ce mois ou mois courant)
        // ═══════════════════════════════════════════════════════════════
        $moisTop = $mois ?? now()->month;
        $topJours = Vente::whereIn('station_id', $stationIds)
            ->whereYear('date_vente', $annee)
            ->whereMonth('date_vente', $moisTop)
            ->select(DB::raw('DATE(date_vente) as jour'), DB::raw('SUM(montant_total) as ca'), DB::raw('SUM(quantite) as qte'))
            ->groupBy('jour')
            ->orderByDesc('ca')
            ->limit(7)
            ->get();

        // ═══════════════════════════════════════════════════════════════
        // TABLEAU — Récapitulatif par carburant
        // ═══════════════════════════════════════════════════════════════
        $recapCarburants = Carburant::withCount([
                'cuves as cuves_count' => fn($q) => $q->whereIn('station_id', $stationIds)
            ])
            ->get()
            ->map(function ($carburant) use ($stationIds, $annee, $mois) {
                $cuveIds = Cuve::whereIn('station_id', $stationIds)->where('carburant_id', $carburant->id)->pluck('id');

                $ventesQ = Vente::whereIn('station_id', $stationIds)
                    ->join('pistolets','ventes.pistolet_id','=','pistolets.id')
                    ->join('pompes','pistolets.pompe_id','=','pompes.id')
                    ->whereIn('pompes.cuve_id', $cuveIds)
                    ->whereYear('ventes.date_vente', $annee);
                if ($mois) $ventesQ->whereMonth('ventes.date_vente', $mois);

                $entreesQ = Entree::whereIn('cuve_id', $cuveIds)->whereYear('date_entree', $annee);
                if ($mois) $entreesQ->whereMonth('date_entree', $mois);

                $carburant->ventes_montant  = $ventesQ->sum('ventes.montant_total');
                $carburant->ventes_quantite = $ventesQ->sum('ventes.quantite');
                $carburant->entrees_quantite = $entreesQ->sum('quantite');
                $carburant->stock_actuel = Cuve::whereIn('id', $cuveIds)->sum('stock_actuel');
                return $carburant;
            })
            ->filter(fn($c) => $c->cuves_count > 0 || $c->ventes_montant > 0);

        $annees = range(now()->year, max(now()->year - 4, 2020));
        $moisNoms = [];
        for ($m = 1; $m <= 12; $m++) {
            $moisNoms[$m] = Carbon::create(2000, $m, 1)->locale('fr')->isoFormat('MMMM');
        }

        return view('statistique', compact(
            'kpi', 'stations', 'stationSelectId',
            'annee', 'mois', 'annees', 'moisNoms',
            'labelsVentes', 'dataCA', 'dataQte',
            'dataEntreesMontant', 'dataEntreesQte',
            'ventesByCarburant',
            'commandesByStatut',
            'cuves', 'cuveChartData',
            'topJours', 'moisTop',
            'recapCarburants'
        ));
    }
}
