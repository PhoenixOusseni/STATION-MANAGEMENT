<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::withCount(['cuves', 'users'])->paginate(10);
        return view('stations.index', compact('stations'));
    }

    public function create()
    {
        return view('stations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'ville' => 'nullable|string|max:100',
            'responsable' => 'nullable|string|max:255',
        ]);

        Station::create($validated);

        return redirect()->route('stations.index')
            ->with('success', 'Station créée avec succès.');
    }

    public function show(Station $station)
    {
        $station->load(['cuves.carburant', 'users']);
        return view('stations.show', compact('station'));
    }

    public function edit(Station $station)
    {
        return view('stations.edit', compact('station'));
    }

    public function update(Request $request, Station $station)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'ville' => 'nullable|string|max:100',
            'responsable' => 'nullable|string|max:255',
        ]);

        $station->update($validated);

        return redirect()->route('stations.index')
            ->with('success', 'Station mise à jour avec succès.');
    }

    public function destroy(Station $station)
    {
        $station->delete();

        return redirect()->route('stations.index')
            ->with('success', 'Station supprimée avec succès.');
    }
}
