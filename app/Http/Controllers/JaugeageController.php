<?php

namespace App\Http\Controllers;

use App\Models\Jaugeage;
use App\Models\Cuve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JaugeageController extends Controller
{
    /* ──────────────────────── index ──────────────────────── */

    public function index()
    {
        $user  = Auth::user();
        $query = Jaugeage::with(['cuve.carburant', 'user', 'sessionVente']);

        if (!$user->isAdmin()) {
            $query->whereHas('cuve', function ($q) use ($user) {
                $q->where('station_id', $user->station_id);
            });
        }

        $jaugeages = $query->latest('date_jaugeage')->paginate(20);
        return view('jaugeages.index', compact('jaugeages'));
    }

    /* ──────────────────────── create (jaugeage autonome) ──────────────────────── */

    public function create()
    {
        $user = Auth::user();

        $cuves = $user->isAdmin()
            ? Cuve::with('carburant')->get()
            : Cuve::where('station_id', $user->station_id)->with('carburant')->get();

        return view('jaugeages.create', compact('cuves'));
    }

    /* ──────────────────────── store ──────────────────────── */

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'cuve_id'          => 'required|exists:cuves,id',
            'type'             => 'required|in:debut_session,fin_session,avant_depotage',
            'quantite_mesuree' => 'required|numeric|min:0',
            'date_jaugeage'    => 'required|date',
            'observation'      => 'nullable|string',
        ]);

        $cuve  = Cuve::findOrFail($validated['cuve_id']);
        $ecart = (float)$validated['quantite_mesuree'] - (float)$cuve->stock_actuel;

        $jaugeage = Jaugeage::create([
            'numero_jaugeage'    => 'JAU-' . date('Ymd') . '-' . str_pad(Jaugeage::count() + 1, 5, '0', STR_PAD_LEFT),
            'cuve_id'            => $validated['cuve_id'],
            'user_id'            => $user->id,
            'session_vente_id'   => null,
            'entree_id'          => null,
            'type'               => $validated['type'],
            'quantite_mesuree'   => $validated['quantite_mesuree'],
            'quantite_theorique' => $cuve->stock_actuel,
            'ecart'              => $ecart,
            'date_jaugeage'      => $validated['date_jaugeage'],
            'observation'        => $validated['observation'] ?? null,
        ]);

        return redirect()->route('jaugeages.show', $jaugeage)
            ->with('success', 'Jaugeage enregistré avec succès.');
    }

    /* ──────────────────────── show ──────────────────────── */

    public function show(Jaugeage $jaugeage)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $jaugeage->cuve->station_id !== $user->station_id) {
            abort(403);
        }

        $jaugeage->load(['cuve.carburant', 'user', 'sessionVente', 'entree']);
        return view('jaugeages.show', compact('jaugeage'));
    }
}
