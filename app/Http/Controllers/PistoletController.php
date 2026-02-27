<?php

namespace App\Http\Controllers;

use App\Models\Pistolet;
use App\Models\Pompe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PistoletController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = Pistolet::with(['pompe.cuve.station', 'pompe.cuve.carburant', 'pompiste']);

        if (!$user->isAdmin()) {
            $query->whereHas('pompe.cuve', fn($q) => $q->where('station_id', $user->station_id));
        }

        $pistolets = $query->latest()->paginate(20);
        return view('pistolets.index', compact('pistolets'));
    }

    public function create(Request $request)
    {
        $user  = Auth::user();
        $query = Pompe::with(['cuve.station', 'cuve.carburant']);

        if (!$user->isAdmin()) {
            $query->whereHas('cuve', fn($q) => $q->where('station_id', $user->station_id));
        }

        $pompes    = $query->get();
        $pompistes = User::where('role', 'pompiste')
            ->when(!$user->isAdmin(), fn($q) => $q->where('station_id', $user->station_id))
            ->orderBy('prenom')
            ->get();

        $preselectedPompe = $request->query('pompe_id');
        return view('pistolets.create', compact('pompes', 'pompistes', 'preselectedPompe'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pompe_id'    => 'required|exists:pompes,id',
            'nom'         => 'required|string|max:255',
            'numero'      => 'required|string|max:255|unique:pistolets',
            'etat'        => 'required|in:actif,inactif,maintenance',
            'pompiste_id' => 'nullable|exists:users,id',
        ]);

        Pistolet::create($validated);

        return redirect()->route('pistolets.index')
            ->with('success', 'Pistolet créé avec succès.');
    }

    public function show(Pistolet $pistolet)
    {
        $pistolet->load(['pompe.cuve.station', 'pompe.cuve.carburant', 'pompiste', 'ventes' => fn($q) => $q->latest('date_vente')->take(10)]);
        return view('pistolets.show', compact('pistolet'));
    }

    public function edit(Pistolet $pistolet)
    {
        $user  = Auth::user();
        $query = Pompe::with(['cuve.station', 'cuve.carburant']);

        if (!$user->isAdmin()) {
            $query->whereHas('cuve', fn($q) => $q->where('station_id', $user->station_id));
        }

        $pompes    = $query->get();
        $pompistes = User::where('role', 'pompiste')
            ->when(!$user->isAdmin(), fn($q) => $q->where('station_id', $user->station_id))
            ->orderBy('prenom')
            ->get();

        return view('pistolets.edit', compact('pistolet', 'pompes', 'pompistes'));
    }

    public function update(Request $request, Pistolet $pistolet)
    {
        $validated = $request->validate([
            'pompe_id'    => 'required|exists:pompes,id',
            'nom'         => 'required|string|max:255',
            'numero'      => 'required|string|max:255|unique:pistolets,numero,' . $pistolet->id,
            'etat'        => 'required|in:actif,inactif,maintenance',
            'pompiste_id' => 'nullable|exists:users,id',
        ]);

        $pistolet->update($validated);

        return redirect()->route('pistolets.index')
            ->with('success', 'Pistolet mis à jour avec succès.');
    }

    public function destroy(Pistolet $pistolet)
    {
        if ($pistolet->ventes()->count() > 0) {
            return redirect()->route('pistolets.index')
                ->with('error', 'Impossible de supprimer ce pistolet : il est lié à des ventes.');
        }

        $pistolet->delete();

        return redirect()->route('pistolets.index')
            ->with('success', 'Pistolet supprimé avec succès.');
    }
}
