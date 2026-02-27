<?php

namespace App\Http\Controllers;

use App\Models\Pompe;
use App\Models\Cuve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PompeController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = Pompe::with(['cuve.station', 'cuve.carburant', 'pistolets']);

        if (!$user->isAdmin()) {
            $query->whereHas('cuve', fn($q) => $q->where('station_id', $user->station_id));
        }

        $pompes = $query->latest()->paginate(20);
        return view('pompes.index', compact('pompes'));
    }

    public function create()
    {
        $user  = Auth::user();
        $query = Cuve::with(['station', 'carburant']);

        if (!$user->isAdmin()) {
            $query->where('station_id', $user->station_id);
        }

        $cuves = $query->get();
        return view('pompes.create', compact('cuves'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cuve_id'          => 'required|exists:cuves,id',
            'nom'              => 'required|string|max:255',
            'numero_serie'     => 'nullable|string|max:255|unique:pompes',
            'etat'             => 'required|in:actif,inactif,maintenance',
            'date_maintenance' => 'nullable|date',
        ]);

        Pompe::create($validated);

        return redirect()->route('pompes.index')
            ->with('success', 'Pompe créée avec succès.');
    }

    public function show(Pompe $pompe)
    {
        $pompe->load(['cuve.station', 'cuve.carburant', 'pistolets.pompiste']);
        return view('pompes.show', compact('pompe'));
    }

    public function edit(Pompe $pompe)
    {
        $user  = Auth::user();
        $query = Cuve::with(['station', 'carburant']);

        if (!$user->isAdmin()) {
            $query->where('station_id', $user->station_id);
        }

        $cuves = $query->get();
        return view('pompes.edit', compact('pompe', 'cuves'));
    }

    public function update(Request $request, Pompe $pompe)
    {
        $validated = $request->validate([
            'cuve_id'          => 'required|exists:cuves,id',
            'nom'              => 'required|string|max:255',
            'numero_serie'     => 'nullable|string|max:255|unique:pompes,numero_serie,' . $pompe->id,
            'etat'             => 'required|in:actif,inactif,maintenance',
            'date_maintenance' => 'nullable|date',
        ]);

        $pompe->update($validated);

        return redirect()->route('pompes.index')
            ->with('success', 'Pompe mise à jour avec succès.');
    }

    public function destroy(Pompe $pompe)
    {
        if ($pompe->pistolets()->count() > 0) {
            return redirect()->route('pompes.index')
                ->with('error', 'Impossible de supprimer cette pompe : elle possède des pistolets associés.');
        }

        $pompe->delete();

        return redirect()->route('pompes.index')
            ->with('success', 'Pompe supprimée avec succès.');
    }
}
