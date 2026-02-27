<?php

namespace App\Http\Controllers;

use App\Models\Carburant;
use Illuminate\Http\Request;

class CarburantController extends Controller
{
    public function index()
    {
        $carburants = Carburant::withCount('cuves')->paginate(10);
        return view('carburants.index', compact('carburants'));
    }

    public function create()
    {
        return view('carburants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:carburants',
            'prix_unitaire' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Carburant::create($validated);

        return redirect()->route('carburants.index')
            ->with('success', 'Carburant créé avec succès.');
    }

    public function show(Carburant $carburant)
    {
        $carburant->load('cuves.station');
        return view('carburants.show', compact('carburant'));
    }

    public function edit(Carburant $carburant)
    {
        return view('carburants.edit', compact('carburant'));
    }

    public function update(Request $request, Carburant $carburant)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:carburants,code,' . $carburant->id,
            'prix_unitaire' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $carburant->update($validated);

        return redirect()->route('carburants.index')
            ->with('success', 'Carburant mis à jour avec succès.');
    }

    public function destroy(Carburant $carburant)
    {
        $carburant->delete();

        return redirect()->route('carburants.index')
            ->with('success', 'Carburant supprimé avec succès.');
    }
}
