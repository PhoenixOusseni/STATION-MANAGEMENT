<?php

namespace App\Http\Controllers;

use App\Models\Cuve;
use App\Models\Vente;
use App\Models\Entree;
use App\Models\Jaugeage;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LivreJournalController extends Controller
{
    public function index(Request $request)
    {
        $user       = Auth::user();
        $date       = $request->input('date', now()->toDateString());
        $dateCarbon = Carbon::parse($date);

        // Scope station
        if ($user->isAdmin()) {
            $stationId = $request->input('station_id');
            $stations  = Station::all();
            $cuves     = Cuve::with('carburant')
                ->when($stationId, fn($q) => $q->where('station_id', $stationId))
                ->orderBy('nom')
                ->get();
        } else {
            $stationId = $user->station_id;
            $stations  = null;
            $cuves     = Cuve::with('carburant')
                ->where('station_id', $stationId)
                ->orderBy('nom')
                ->get();
        }

        $lignes = [];

        foreach ($cuves as $cuve) {
            // Vente du Jour : sum quantite_vendue via pistolet → pompe → cuve
            $venteJour = Vente::join('pistolets', 'ventes.pistolet_id', '=', 'pistolets.id')
                ->join('pompes', 'pistolets.pompe_id', '=', 'pompes.id')
                ->where('pompes.cuve_id', $cuve->id)
                ->whereDate('ventes.date_vente', $dateCarbon)
                ->sum('ventes.quantite_vendue');

            // Jauge début : jaugeage type=debut_session du jour pour cette cuve
            $jaugeDebut    = Jaugeage::where('cuve_id', $cuve->id)
                ->where('type', 'debut_session')
                ->whereDate('date_jaugeage', $dateCarbon)
                ->orderBy('date_jaugeage')
                ->first();
            $jaugeDebutVal = $jaugeDebut ? (float) $jaugeDebut->quantite_mesuree : 0;

            // Jauge fin : jaugeage type=fin_session du jour pour cette cuve
            $jaugeFin    = Jaugeage::where('cuve_id', $cuve->id)
                ->where('type', 'fin_session')
                ->whereDate('date_jaugeage', $dateCarbon)
                ->orderByDesc('date_jaugeage')
                ->first();
            $jaugeFinVal = $jaugeFin ? (float) $jaugeFin->quantite_mesuree : 0;

            // Quantité reçue + bordereaux (entrées du jour)
            $entrees   = Entree::where('cuve_id', $cuve->id)
                ->whereDate('date_entree', $dateCarbon)
                ->get();
            $qteRecu   = (float) $entrees->sum('quantite');
            $bordereau = $entrees->pluck('numero_bon_livraison')->filter()->implode(', ');

            // Calculs
            $stockTotal      = $jaugeDebutVal + $qteRecu;
            $stockTheorique  = $stockTotal - (float) $venteJour;
            $ecartStock      = $jaugeFinVal - $stockTheorique;

            // Cumul écart : somme des écarts des jours PRÉCÉDENTS (fin_session)
            $cumulEcart = (float) Jaugeage::where('cuve_id', $cuve->id)
                ->where('type', 'fin_session')
                ->whereDate('date_jaugeage', '<', $dateCarbon)
                ->sum('ecart');

            $lignes[] = [
                'cuve'            => $cuve->nom,
                'vente_jour'      => (float) $venteJour,
                'jauge_debut'     => $jaugeDebutVal,
                'qte_recu'        => $qteRecu,
                'bordereau'       => $bordereau,
                'stock_total'     => $stockTotal,
                'stock_theorique' => $stockTheorique,
                'jauge_fin'       => $jaugeFinVal,
                'ecart_stock'     => $ecartStock,
                'cumul_ecart'     => $cumulEcart,
            ];
        }

        $totals = [
            'vente_jour'      => collect($lignes)->sum('vente_jour'),
            'jauge_debut'     => collect($lignes)->sum('jauge_debut'),
            'qte_recu'        => collect($lignes)->sum('qte_recu'),
            'stock_total'     => collect($lignes)->sum('stock_total'),
            'stock_theorique' => collect($lignes)->sum('stock_theorique'),
            'jauge_fin'       => collect($lignes)->sum('jauge_fin'),
            'ecart_stock'     => collect($lignes)->sum('ecart_stock'),
            'cumul_ecart'     => collect($lignes)->sum('cumul_ecart'),
        ];

        return view('livre_journal.index', compact(
            'lignes', 'totals', 'date',
            'stations', 'stationId'
        ));
    }

    public function print(Request $request)
    {
        $user       = Auth::user();
        $date       = $request->input('date', now()->toDateString());
        $dateCarbon = Carbon::parse($date);

        if ($user->isAdmin()) {
            $stationId = $request->input('station_id');
            $stations  = Station::all();
            $station   = $stationId ? Station::find($stationId) : null;
            $cuves     = Cuve::with('carburant')
                ->when($stationId, fn($q) => $q->where('station_id', $stationId))
                ->orderBy('nom')
                ->get();
        } else {
            $stationId = $user->station_id;
            $stations  = null;
            $station   = Station::find($stationId);
            $cuves     = Cuve::with('carburant')
                ->where('station_id', $stationId)
                ->orderBy('nom')
                ->get();
        }

        $lignes = [];

        foreach ($cuves as $cuve) {
            $venteJour = Vente::join('pistolets', 'ventes.pistolet_id', '=', 'pistolets.id')
                ->join('pompes', 'pistolets.pompe_id', '=', 'pompes.id')
                ->where('pompes.cuve_id', $cuve->id)
                ->whereDate('ventes.date_vente', $dateCarbon)
                ->sum('ventes.quantite_vendue');

            $jaugeDebut    = Jaugeage::where('cuve_id', $cuve->id)
                ->where('type', 'debut_session')
                ->whereDate('date_jaugeage', $dateCarbon)
                ->orderBy('date_jaugeage')
                ->first();
            $jaugeDebutVal = $jaugeDebut ? (float) $jaugeDebut->quantite_mesuree : 0;

            $jaugeFin    = Jaugeage::where('cuve_id', $cuve->id)
                ->where('type', 'fin_session')
                ->whereDate('date_jaugeage', $dateCarbon)
                ->orderByDesc('date_jaugeage')
                ->first();
            $jaugeFinVal = $jaugeFin ? (float) $jaugeFin->quantite_mesuree : 0;

            $entrees   = Entree::where('cuve_id', $cuve->id)
                ->whereDate('date_entree', $dateCarbon)
                ->get();
            $qteRecu   = (float) $entrees->sum('quantite');
            $bordereau = $entrees->pluck('numero_bon_livraison')->filter()->implode(', ');

            $stockTotal     = $jaugeDebutVal + $qteRecu;
            $stockTheorique = $stockTotal - (float) $venteJour;
            $ecartStock     = $jaugeFinVal - $stockTheorique;

            $cumulEcart = (float) Jaugeage::where('cuve_id', $cuve->id)
                ->where('type', 'fin_session')
                ->whereDate('date_jaugeage', '<', $dateCarbon)
                ->sum('ecart');

            $lignes[] = [
                'cuve'            => $cuve->nom,
                'vente_jour'      => (float) $venteJour,
                'jauge_debut'     => $jaugeDebutVal,
                'qte_recu'        => $qteRecu,
                'bordereau'       => $bordereau,
                'stock_total'     => $stockTotal,
                'stock_theorique' => $stockTheorique,
                'jauge_fin'       => $jaugeFinVal,
                'ecart_stock'     => $ecartStock,
                'cumul_ecart'     => $cumulEcart,
            ];
        }

        $totals = [
            'vente_jour'      => collect($lignes)->sum('vente_jour'),
            'jauge_debut'     => collect($lignes)->sum('jauge_debut'),
            'qte_recu'        => collect($lignes)->sum('qte_recu'),
            'stock_total'     => collect($lignes)->sum('stock_total'),
            'stock_theorique' => collect($lignes)->sum('stock_theorique'),
            'jauge_fin'       => collect($lignes)->sum('jauge_fin'),
            'ecart_stock'     => collect($lignes)->sum('ecart_stock'),
            'cumul_ecart'     => collect($lignes)->sum('cumul_ecart'),
        ];

        return view('livre_journal.print', compact(
            'lignes', 'totals', 'date', 'station'
        ));
    }
}
