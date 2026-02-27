<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Cuve;
use App\Models\Vente;
use App\Models\Entree;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $station = $user->station;

        // Vérifier si l'utilisateur a une station assignée
        if (!$station) {
            return view('dashboard', [
                'stats' => [
                    'total_cuves' => 0,
                    'cuves_alerte' => 0,
                    'ventes_jour' => 0,
                    'ventes_mois' => 0,
                    'commandes_attente' => 0,
                ],
                'cuvesAlerte' => collect(),
                'dernieresVentes' => collect(),
                'dernieresEntrees' => collect(),
                'user' => $user,
                'station' => null
            ])->with('error', 'Aucune station n\'est assignée à votre compte. Veuillez contacter l\'administrateur.');
        }

        // Statistiques générales
        $stats = [
            'total_cuves' => $station->cuves()->count(),
            'cuves_alerte' => $station->cuves()->whereRaw('stock_actuel <= stock_min')->count(),
            'ventes_jour' => Vente::where('station_id', $station->id)->whereDate('date_vente', today())->sum('montant_total'),
            'ventes_mois' => Vente::where('station_id', $station->id)->whereMonth('date_vente', now()->month)->sum('montant_total'),
            'commandes_attente' => Commande::where('station_id', $station->id)->where('statut', 'en_attente')->count(),
        ];

        // Cuves en alerte
        $cuvesAlerte = Cuve::where('station_id', $station->id)->whereRaw('stock_actuel <= stock_min')->with('carburant')->get();

        // Dernières ventes
        $dernieresVentes = Vente::where('station_id', $station->id)
            ->with(['pistolet.pompe', 'pompiste'])
            ->latest('date_vente')
            ->take(10)
            ->get();

        // Dernières entrées
        $dernieresEntrees = Entree::whereHas('cuve', function ($query) use ($station) {
            $query->where('station_id', $station->id);
        })
            ->with(['cuve.carburant', 'user'])
            ->latest('date_entree')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'cuvesAlerte', 'dernieresVentes', 'dernieresEntrees', 'user', 'station'));
    }
}
