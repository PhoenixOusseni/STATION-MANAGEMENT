<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Valider les données reçues
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Tenter l'authentification
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate(); // protège contre les attaques de session fixation

            return redirect()->intended(route('dashboard'))->with('success', 'Connexion réussie');
        }

        // Échec : on retourne avec un message sans révéler la cause exacte
        return back()->withErrors(['login' => 'Les identifiants sont invalides.'])->onlyInput('login', 'password');
    }

    public function register(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Générer le login : première lettre du nom + point + prénom (tout en minuscule)
        $login = strtolower(substr($validated['nom'], 0, 1) . '.' . $validated['prenom']);

        // Vérifier si le login existe déjà et ajouter un numéro si nécessaire
        $originalLogin = $login;
        $counter = 1;
        while (User::where('login', $login)->exists()) {
            $login = $originalLogin . $counter;
            $counter++;
        }

        // Créer l'utilisateur
        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'login' => $login,
            'password' => Hash::make($validated['password']),
        ]);

        // Connecter automatiquement l'utilisateur après l'inscription
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Inscription réussie ! Bienvenue ' . $user->prenom . ' !');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnexion réussie');
    }
}
