<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Station;
use App\Models\Carburant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Commande::with(['station', 'carburant', 'user']);

        if (!$user->isAdmin()) {
            $query->where('station_id', $user->station_id);
        }

        $commandes = $query->latest()->get();
        return view('commandes.index', compact('commandes'));
    }

    public function create()
    {
        $user = Auth::user();
        $stations = $user->isAdmin() ? Station::all() : Station::where('id', $user->station_id)->get();
        $carburants = Carburant::all();
        return view('commandes.create', compact('stations', 'carburants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'carburant_id' => 'required|exists:carburants,id',
            'quantite' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'fournisseur' => 'required|string|max:255',
            'date_commande' => 'required|date',
            'date_livraison_prevue' => 'nullable|date|after_or_equal:date_commande',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['numero_commande'] = 'CMD-' . date('Ymd') . '-' . str_pad(Commande::count() + 1, 5, '0', STR_PAD_LEFT);
        $validated['montant_total'] = $validated['quantite'] * $validated['prix_unitaire'];
        $validated['statut'] = 'en_attente';

        Commande::create($validated);

        return redirect()->route('commandes.index')
            ->with('success', 'Commande créée avec succès.');
    }

    public function show(Commande $commande)
    {
        $commande->load(['station', 'carburant', 'user', 'entree']);
        return view('commandes.show', compact('commande'));
    }

    public function edit(Commande $commande)
    {
        $user = Auth::user();
        $stations = $user->isAdmin() ? Station::all() : Station::where('id', $user->station_id)->get();
        $carburants = Carburant::all();
        return view('commandes.edit', compact('commande', 'stations', 'carburants'));
    }

    public function update(Request $request, Commande $commande)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'carburant_id' => 'required|exists:carburants,id',
            'quantite' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'fournisseur' => 'required|string|max:255',
            'statut' => 'required|in:en_attente,validee,livree,annulee',
            'date_commande' => 'required|date',
            'date_livraison_prevue' => 'nullable|date|after_or_equal:date_commande',
        ]);

        $validated['montant_total'] = $validated['quantite'] * $validated['prix_unitaire'];

        $commande->update($validated);

        return redirect()->route('commandes.index')
            ->with('success', 'Commande mise à jour avec succès.');
    }

    public function destroy(Commande $commande)
    {
        $commande->delete();

        return redirect()->route('commandes.index')
            ->with('success', 'Commande supprimée avec succès.');
    }

    public function updateStatut(Request $request, Commande $commande)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente,validee,livree,annulee',
        ]);

        $commande->update($validated);

        return redirect()->back()
            ->with('success', 'Statut de la commande mis à jour avec succès.');
    }
}
