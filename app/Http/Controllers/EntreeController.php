<?php

namespace App\Http\Controllers;

use App\Models\Entree;
use App\Models\Cuve;
use App\Models\Commande;
use App\Models\Jaugeage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntreeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Entree::with(['cuve.carburant', 'commande', 'user']);

        if (!$user->isAdmin()) {
            $query->whereHas('cuve', function($q) use ($user) {
                $q->where('station_id', $user->station_id);
            });
        }

        $entrees = $query->latest('date_entree')->paginate(15);
        return view('entrees.index', compact('entrees'));
    }

    public function create()
    {
        $user = Auth::user();
        $cuves = $user->isAdmin()
            ? Cuve::with('carburant')->get()
            : Cuve::where('station_id', $user->station_id)->with('carburant')->get();

        $commandes = $user->isAdmin()
            ? Commande::where('statut', 'validee')->whereDoesntHave('entree')->get()
            : Commande::where('station_id', $user->station_id)
                ->where('statut', 'validee')
                ->whereDoesntHave('entree')
                ->get();

        return view('entrees.create', compact('cuves', 'commandes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cuve_id'                => 'required|exists:cuves,id',
            'commande_id'            => 'nullable|exists:commandes,id',
            'quantite'               => 'required|numeric|min:0',
            'prix_unitaire'          => 'required|numeric|min:0',
            'date_entree'            => 'required|date',
            'numero_bon_livraison'   => 'nullable|string|max:255',
            'observation'            => 'nullable|string',
            // Jaugeage avant dépotage (optionnel mais recommandé)
            'quantite_jaugee_avant'  => 'nullable|numeric|min:0',
            'observation_jaugeage'   => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $cuve = Cuve::findOrFail($validated['cuve_id']);

            // 1. Créer le jaugeage avant dépotage si une mesure a été fournie
            $jaugeageId = null;
            if (!empty($validated['quantite_jaugee_avant'])) {
                $ecart = (float)$validated['quantite_jaugee_avant'] - (float)$cuve->stock_actuel;

                $jaugeage = Jaugeage::create([
                    'numero_jaugeage'    => 'JAU-' . date('Ymd') . '-' . str_pad(Jaugeage::count() + 1, 5, '0', STR_PAD_LEFT),
                    'cuve_id'            => $validated['cuve_id'],
                    'user_id'            => $user->id,
                    'session_vente_id'   => null,
                    'entree_id'          => null, // sera mis à jour après création de l'entrée
                    'type'               => 'avant_depotage',
                    'quantite_mesuree'   => $validated['quantite_jaugee_avant'],
                    'quantite_theorique' => $cuve->stock_actuel,
                    'ecart'              => $ecart,
                    'date_jaugeage'      => $validated['date_entree'],
                    'observation'        => $validated['observation_jaugeage'] ?? null,
                ]);
                $jaugeageId = $jaugeage->id;
            }

            // 2. Créer l'entrée
            $entree = Entree::create([
                'cuve_id'               => $validated['cuve_id'],
                'commande_id'           => $validated['commande_id'] ?? null,
                'jaugeage_id'           => $jaugeageId,
                'user_id'               => $user->id,
                'numero_entree'         => 'ENT-' . date('Ymd') . '-' . str_pad(Entree::count() + 1, 5, '0', STR_PAD_LEFT),
                'quantite_jaugee_avant' => $validated['quantite_jaugee_avant'] ?? null,
                'observation_jaugeage'  => $validated['observation_jaugeage'] ?? null,
                'quantite'              => $validated['quantite'],
                'prix_unitaire'         => $validated['prix_unitaire'],
                'montant_total'         => $validated['quantite'] * $validated['prix_unitaire'],
                'date_entree'           => $validated['date_entree'],
                'numero_bon_livraison'  => $validated['numero_bon_livraison'] ?? null,
                'observation'           => $validated['observation'] ?? null,
            ]);

            // 3. Lier le jaugeage à l'entrée
            if ($jaugeageId) {
                Jaugeage::where('id', $jaugeageId)->update(['entree_id' => $entree->id]);
            }

            // 4. Mettre à jour le stock de la cuve
            $cuve->increment('stock_actuel', $validated['quantite']);

            // 5. Mettre à jour le statut de la commande si applicable
            if (!empty($validated['commande_id'])) {
                Commande::findOrFail($validated['commande_id'])->update(['statut' => 'livree']);
            }

            DB::commit();
            return redirect()->route('entrees.show', $entree)
                ->with('success', 'Entrée enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement de l\'entrée : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(String $id)
    {
        $entree = Entree::with(['cuve.carburant', 'commande', 'user', 'jaugeage'])->findOrFail($id);
        return view('entrees.show', compact('entree'));
    }

    public function edit(String $id)
    {
        $entree = Entree::findOrFail($id);
        $user = Auth::user();

        if (!$user->isAdmin() && $entree->cuve->station_id !== $user->station_id) {
            abort(403, 'Unauthorized');
        }

        $cuves = $user->isAdmin()
            ? Cuve::with('carburant')->get()
            : Cuve::where('station_id', $user->station_id)->with('carburant')->get();

        $commandes = $user->isAdmin()
            ? Commande::where('statut', 'validee')->orWhere('id', $entree->commande_id)->get()
            : Commande::where('station_id', $user->station_id)
                ->where(function($q) use ($entree) {
                    $q->where('statut', 'validee')
                      ->orWhere('id', $entree->commande_id);
                })->get();

        return view('entrees.edit', compact('entree', 'cuves', 'commandes'));
    }

    public function update(Request $request, String $id)
    {
        $entree = Entree::findOrFail($id);
        $user = Auth::user();

        if (!$user->isAdmin() && $entree->cuve->station_id !== $user->station_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'cuve_id' => 'required|exists:cuves,id',
            'commande_id' => 'nullable|exists:commandes,id',
            'quantite' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'date_entree' => 'required|date',
            'numero_bon_livraison' => 'nullable|string|max:255',
            'observation' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Mettre à jour le stock de la cuve (ajuster en fonction de la nouvelle quantité)
            $cuve = Cuve::findOrFail($validated['cuve_id']);
            $stockAdjustment = $validated['quantite'] - $entree->quantite;
            $cuve->increment('stock_actuel', $stockAdjustment);

            // Mettre à jour le statut de l'ancienne commande si applicable
            if ($entree->commande_id && $entree->commande_id != $validated['commande_id']) {
                $oldCommande = Commande::findOrFail($entree->commande_id);
                $oldCommande->update(['statut' => 'validee']);
            }

            // Mettre à jour le statut de la nouvelle commande si applicable
            if ($validated['commande_id'] && $validated['commande_id'] != $entree->commande_id) {
                $newCommande = Commande::findOrFail($validated['commande_id']);
                $newCommande->update(['statut' => 'livree']);
            }

            $validated['montant_total'] = $validated['quantite'] * $validated['prix_unitaire'];
            $entree->update($validated);

            DB::commit();

            return redirect()->route('entrees.index')
                ->with('success', 'Entrée mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de l\'entrée: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function print(String $id)
    {
        $entree = Entree::with(['cuve.carburant', 'commande', 'user', 'cuve.station'])->findOrFail($id);
        $user = Auth::user();

        if (!$user->isAdmin() && $entree->cuve->station_id !== $user->station_id) {
            abort(403, 'Unauthorized');
        }

        $station = $entree->cuve->station;
        return view('entrees.print', compact('entree', 'station'));
    }

    public function destroy(String $id)
    {
        $entree = Entree::findOrFail($id);
        DB::beginTransaction();
        try {
            // Décrémenter le stock de la cuve
            $cuve = $entree->cuve;
            $cuve->decrement('stock_actuel', $entree->quantite);

            $entree->delete();

            DB::commit();

            return redirect()->route('entrees.index')
                ->with('success', 'Entrée supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'entrée: ' . $e->getMessage());
        }
    }
}
