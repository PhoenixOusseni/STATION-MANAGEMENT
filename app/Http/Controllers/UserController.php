<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = User::with('station');

        // Filtrer les utilisateurs en fonction du rôle
        if (!$user->isAdmin()) {
            // Les gestionnaires ne voient que les utilisateurs de leur station
            $query->where('station_id', $user->station_id);
        }

        // Filtrer par rôle si demandé
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $user = Auth::user();
        $stations = $user->isAdmin()
            ? Station::all()
            : Station::where('id', $user->station_id)->get();

        return view('users.create', compact('stations'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'gestionnaire', 'pompiste'])],
            'station_id' => 'required|exists:stations,id',
            'actif' => 'nullable|boolean',
        ]);

        // Vérifier que seuls les admins peuvent créer des admins
        if ($validated['role'] === 'admin' && !Auth::user()->isAdmin()) {
            return redirect()->back()
                ->with('error', 'Vous n\'avez pas l\'autorisation de créer un administrateur.')
                ->withInput();
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['actif'] = $request->has('actif') ? true : false;

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load(['station', 'pistolets.pompe.cuve.carburant', 'ventes', 'entrees', 'commandes']);
        return view('users.show', compact('user'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(User $user)
    {
        // Empêcher les gestionnaires de modifier les admins
        if (!Auth::user()->isAdmin() && $user->role === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Vous n\'avez pas l\'autorisation de modifier cet utilisateur.');
        }

        $authUser = Auth::user();
        $stations = $authUser->isAdmin()
            ? Station::all()
            : Station::where('id', $authUser->station_id)->get();

        return view('users.edit', compact('user', 'stations'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telephone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'gestionnaire', 'pompiste'])],
            'station_id' => 'required|exists:stations,id',
            'actif' => 'nullable|boolean',
        ]);

        // Vérifier que seuls les admins peuvent modifier le rôle admin
        if ($validated['role'] === 'admin' && !Auth::user()->isAdmin()) {
            return redirect()->back()
                ->with('error', 'Vous n\'avez pas l\'autorisation de définir le rôle administrateur.')
                ->withInput();
        }

        // Empêcher de modifier son propre rôle
        if ($user->id === Auth::id() && $user->role !== $validated['role']) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas modifier votre propre rôle.')
                ->withInput();
        }

        // Mettre à jour le mot de passe uniquement s'il est fourni
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['actif'] = $request->has('actif') ? true : false;

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        // Empêcher de supprimer son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Empêcher les gestionnaires de supprimer les admins
        if (!Auth::user()->isAdmin() && $user->role === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Vous n\'avez pas l\'autorisation de supprimer cet utilisateur.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
