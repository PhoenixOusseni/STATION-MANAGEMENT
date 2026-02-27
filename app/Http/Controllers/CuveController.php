<?php

namespace App\Http\Controllers;

use App\Models\Cuve;
use App\Models\Station;
use App\Models\Carburant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Cuve::with(['station', 'carburant', 'pompes']);

        if (!$user->isAdmin()) {
            $query->where('station_id', $user->station_id);
        }

        $cuves = $query->get();
        return view('cuves.index', compact('cuves'));
    }

    public function create()
    {
        $stations = Station::all();
        $carburants = Carburant::all();
        return view('cuves.create', compact('stations', 'carburants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'carburant_id' => 'required|exists:carburants,id',
            'nom' => 'required|string|max:255',
            'capacite_max' => 'required|numeric|min:0',
            'stock_actuel' => 'required|numeric|min:0',
            'stock_min' => 'required|numeric|min:0',
            'numero_serie' => 'nullable|string|max:255|unique:cuves',
        ]);

        Cuve::create($validated);

        return redirect()->route('cuves.index')
            ->with('success', 'Cuve créée avec succès.');
    }

    public function show(String $id)
    {
        $cuve = Cuve::with(['station', 'carburant', 'pompes.pistolets', 'entrees'])->findOrFail($id);
        return view('cuves.show', compact('cuve'));
    }

    public function edit(String $id)
    {
        $cuve = Cuve::findOrFail($id);
        $stations = Station::all();
        $carburants = Carburant::all();
        return view('cuves.edit', compact('cuve', 'stations', 'carburants'));
    }

    public function update(Request $request, String $id)
    {
        $cuve = Cuve::findOrFail($id);
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'carburant_id' => 'required|exists:carburants,id',
            'nom' => 'required|string|max:255',
            'capacite_max' => 'required|numeric|min:0',
            'stock_actuel' => 'required|numeric|min:0',
            'stock_min' => 'required|numeric|min:0',
            'numero_serie' => 'nullable|string|max:255|unique:cuves,numero_serie,' . $cuve->id,
        ]);

        $cuve->update($validated);

        return redirect()->route('cuves.index')
            ->with('success', 'Cuve mise à jour avec succès.');
    }

    public function destroy(String $id)
    {
        $cuve = Cuve::findOrFail($id);
        $cuve->delete();

        return redirect()->route('cuves.index')
            ->with('success', 'Cuve supprimée avec succès.');
    }
}
