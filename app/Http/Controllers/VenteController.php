<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Pistolet;
use App\Models\SessionVente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Vente::with(['pistolet.pompe.cuve.carburant', 'pompiste', 'station']);

        if ($user->isPompiste()) {
            $query->where('pompiste_id', $user->id);
        } elseif ($user->isGestionnaire()) {
            $query->where('station_id', $user->station_id);
        }

        $ventes = $query->latest('date_vente')->paginate(15);
        return view('ventes.index', compact('ventes'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isPompiste()) {
            $pistolets = Pistolet::where('pompiste_id', $user->id)
                ->where('etat', 'actif')
                ->with('pompe.cuve.carburant')
                ->get();
        } else {
            $pistolets = Pistolet::whereHas('pompe.cuve', function($q) use ($user) {
                if (!$user->isAdmin()) {
                    $q->where('station_id', $user->station_id);
                }
            })
            ->where('etat', 'actif')
            ->with('pompe.cuve.carburant')
            ->get();
        }

        // Session en cours pour la station de l'utilisateur
        $sessionActive = SessionVente::where('station_id', $user->station_id)
            ->where('statut', 'en_cours')
            ->first();

        return view('ventes.create', compact('pistolets', 'sessionActive'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pistolet_id'      => 'required|exists:pistolets,id',
            'quantite'         => 'required|numeric|min:0',
            'prix_unitaire'    => 'required|numeric|min:0',
            'mode_paiement'    => 'required|in:especes,carte,mobile_money,credit',
            'date_vente'       => 'required|date',
            'numero_ticket'    => 'nullable|string|max:255',
            'client'           => 'nullable|string|max:255',
            'observation'      => 'nullable|string',
            'session_vente_id' => 'nullable|exists:session_ventes,id',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $session = null;

            // Vérifier / auto-attacher la session
            if (!empty($validated['session_vente_id'])) {
                $session = SessionVente::findOrFail($validated['session_vente_id']);
                if ($session->isCloturee()) {
                    return redirect()->back()
                        ->with('error', 'Impossible d\'enregistrer une vente : la session ' . $session->numero_session . ' est déjà clôturée.')
                        ->withInput();
                }
            } else {
                // Chercher automatiquement la session en cours
                $session = SessionVente::where('station_id', $user->station_id)
                    ->where('statut', 'en_cours')
                    ->first();
                if ($session) {
                    $validated['session_vente_id'] = $session->id;
                }
            }

            $pistolet = Pistolet::with('pompe.cuve')->findOrFail($validated['pistolet_id']);
            $cuve = $pistolet->pompe->cuve;

            // Vérifier le stock disponible
            if ($cuve->stock_actuel < $validated['quantite']) {
                return redirect()->back()
                    ->with('error', 'Stock insuffisant dans la cuve.')
                    ->withInput();
            }

            $validated['station_id']    = $user->station_id;
            $validated['pompiste_id']   = $user->id;
            $validated['numero_vente']  = 'VTE-' . date('Ymd') . '-' . str_pad(Vente::count() + 1, 5, '0', STR_PAD_LEFT);
            $validated['montant_total'] = $validated['quantite'] * $validated['prix_unitaire'];

            Vente::create($validated);

            // Décrémenter le stock de la cuve
            $cuve->decrement('stock_actuel', $validated['quantite']);

            DB::commit();

            $redirect = $session
                ? redirect()->route('session_ventes.show', $session)
                : redirect()->route('ventes.index');

            return $redirect->with('success', 'Vente enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement de la vente : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Vente $vente)
    {
        $vente->load(['pistolet.pompe.cuve.carburant', 'pompiste', 'station']);
        return view('ventes.show', compact('vente'));
    }

    public function edit(Vente $vente)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $vente->station_id !== $user->station_id) {
            abort(403, 'Unauthorized');
        }

        if ($user->isPompiste()) {
            $pistolets = Pistolet::where('pompiste_id', $user->id)
                ->where('etat', 'actif')
                ->with('pompe.cuve.carburant')
                ->get();
        } else {
            $pistolets = Pistolet::whereHas('pompe.cuve', function ($q) use ($user) {
                if (!$user->isAdmin()) {
                    $q->where('station_id', $user->station_id);
                }
            })
            ->where('etat', 'actif')
            ->with('pompe.cuve.carburant')
            ->get();
        }

        // S'assurer que le pistolet actuel de la vente est dans la liste (même inactif)
        if (!$pistolets->contains('id', $vente->pistolet_id)) {
            $current = Pistolet::with('pompe.cuve.carburant')->find($vente->pistolet_id);
            if ($current) {
                $pistolets->prepend($current);
            }
        }

        return view('ventes.edit', compact('vente', 'pistolets'));
    }

    public function update(Request $request, Vente $vente)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $vente->station_id !== $user->station_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'pistolet_id'    => 'required|exists:pistolets,id',
            'quantite'       => 'required|numeric|min:0',
            'prix_unitaire'  => 'required|numeric|min:0',
            'mode_paiement'  => 'required|in:especes,carte,mobile_money,credit',
            'date_vente'     => 'required|date',
            'numero_ticket'  => 'nullable|string|max:255',
            'client'         => 'nullable|string|max:255',
            'observation'    => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $newPistolet = Pistolet::with('pompe.cuve')->findOrFail($validated['pistolet_id']);
            $newCuve     = $newPistolet->pompe->cuve;
            $oldCuve     = $vente->pistolet->pompe->cuve;

            if ($vente->pistolet_id !== (int) $validated['pistolet_id']) {
                // Pistolet changé : restaurer l'ancien stock, vérifier et décrémenter le nouveau
                $oldCuve->increment('stock_actuel', $vente->quantite);

                if ($newCuve->stock_actuel < $validated['quantite']) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Stock insuffisant dans la nouvelle cuve.')
                        ->withInput();
                }

                $newCuve->decrement('stock_actuel', $validated['quantite']);
            } else {
                // Même pistolet : ajuster l'écart de quantité
                $adjustment = $validated['quantite'] - $vente->quantite;

                if ($adjustment > 0 && $oldCuve->stock_actuel < $adjustment) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Stock insuffisant dans la cuve.')
                        ->withInput();
                }

                $oldCuve->increment('stock_actuel', -$adjustment);
            }

            $validated['montant_total'] = $validated['quantite'] * $validated['prix_unitaire'];
            $vente->update($validated);

            DB::commit();

            return redirect()->route('ventes.show', $vente)
                ->with('success', 'Vente mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de la vente : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function print(Vente $vente)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $vente->station_id !== $user->station_id) {
            abort(403, 'Unauthorized');
        }

        $vente->load(['pistolet.pompe.cuve.carburant', 'pompiste', 'station']);
        $station = $vente->station;
        return view('ventes.print', compact('vente', 'station'));
    }

    public function destroy(Vente $vente)
    {
        DB::beginTransaction();
        try {
            // Incrémenter le stock de la cuve
            $cuve = $vente->pistolet->pompe->cuve;
            $cuve->increment('stock_actuel', $vente->quantite);

            $vente->delete();

            DB::commit();

            return redirect()->route('ventes.index')
                ->with('success', 'Vente supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la vente: ' . $e->getMessage());
        }
    }
}
