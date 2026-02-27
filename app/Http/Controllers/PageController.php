<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    // Afficher la page de connexion
    public function home()
    {
        return view('pages.auth.login');
    }

    // Afficher la page d'inscription des utilisateurs
    public function add_users()
    {
        return view('pages.auth.register');
    }

    // Afficher le tableau de bord
    public function dashboard()
    {
        return view('pages.dashboard.index');
    }

    // Afficher le profil utilisateur
    public function profile()
    {
        $user = Auth::user();
        return view('pages.users.profil', compact('user'));
    }
}
