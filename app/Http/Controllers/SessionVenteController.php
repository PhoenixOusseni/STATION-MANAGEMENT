<?php

namespace App\Http\Controllers;

use App\Models\SessionVente;
use App\Models\Jaugeage;
use App\Models\IndexPompe;
use App\Models\Cuve;
use App\Models\Pompe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionVenteController extends Controller
{
    /* ──────────────────────── index ──────────────────────── */

    public function index()
    {
        $user  = Auth::user();
        $query = SessionVente::with(['station', 'user'])->withCount('ventes');

        if (!$user->isAdmin()) {
            $query->where('station_id', $user->station_id);
        }

        $sessions = $query->latest('date_debut')->paginate(15);
        return view('session_ventes.index', compact('sessions'));
    }

    /* ──────────────────────── create ──────────────────────── */

    public function create()
    {
        $user = Auth::user();

        // Vérifier qu'il n'y a pas déjà une session en cours pour cette station
        $sessionEnCours = SessionVente::where('station_id', $user->station_id)
            ->where('statut', 'en_cours')
            ->first();

        if ($sessionEnCours) {
            return redirect()->route('session_ventes.show', $sessionEnCours)
                ->with('warning', 'Une session est déjà en cours. Clôturez-la avant d\'en ouvrir une nouvelle.');
        }

        // Cuves de la station (pour jaugeage début)
        $cuves = Cuve::where('station_id', $user->station_id)
            ->with('carburant')
            ->get();

        // Pompes actives de la station (pour relevé index départ)
        $pompes = Pompe::whereHas('cuve', function ($q) use ($user) {
            $q->where('station_id', $user->station_id);
        })->where('etat', 'actif')->with('cuve.carburant')->get();

        return view('session_ventes.create', compact('cuves', 'pompes'));
    }

    /* ──────────────────────── store ──────────────────────── */

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'date_debut'              => 'required|date',
            'observation'             => 'nullable|string',
            'jaugeages.*.cuve_id'     => 'nullable|exists:cuves,id',
            'jaugeages.*.quantite'    => 'nullable|numeric|min:0',
            'index_pompes.*.pompe_id' => 'nullable|exists:pompes,id',
            'index_pompes.*.index'    => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $numero = 'SES-' . date('Ymd') . '-' . str_pad(SessionVente::count() + 1, 4, '0', STR_PAD_LEFT);

            $session = SessionVente::create([
                'numero_session' => $numero,
                'station_id'     => $user->station_id,
                'user_id'        => $user->id,
                'statut'         => 'en_cours',
                'date_debut'     => $request->date_debut,
                'observation'    => $request->observation,
            ]);

            // Enregistrer les jaugeages début de session
            foreach ($request->jaugeages ?? [] as $j) {
                if (empty($j['quantite']) && $j['quantite'] !== '0') continue;

                $cuve = Cuve::findOrFail($j['cuve_id']);
                $ecart = (float)$j['quantite'] - (float)$cuve->stock_actuel;

                Jaugeage::create([
                    'numero_jaugeage'    => 'JAU-' . date('Ymd') . '-' . str_pad(Jaugeage::count() + 1, 5, '0', STR_PAD_LEFT),
                    'cuve_id'            => $j['cuve_id'],
                    'user_id'            => $user->id,
                    'session_vente_id'   => $session->id,
                    'type'               => 'debut_session',
                    'quantite_mesuree'   => $j['quantite'],
                    'quantite_theorique' => $cuve->stock_actuel,
                    'ecart'              => $ecart,
                    'date_jaugeage'      => $request->date_debut,
                    'observation'        => $j['observation'] ?? null,
                ]);
            }

            // Enregistrer les index de pompe au départ
            foreach ($request->index_pompes ?? [] as $ip) {
                if (empty($ip['index']) && $ip['index'] !== '0') continue;

                IndexPompe::create([
                    'pompe_id'           => $ip['pompe_id'],
                    'session_vente_id'   => $session->id,
                    'user_id'            => $user->id,
                    'index_depart'       => $ip['index'],
                    'date_releve_depart' => $request->date_debut,
                    'observation'        => $ip['observation'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('session_ventes.show', $session)
                ->with('success', 'Session ' . $numero . ' ouverte avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'ouverture de la session : ' . $e->getMessage())
                ->withInput();
        }
    }

    /* ──────────────────────── show ──────────────────────── */

    public function show(SessionVente $sessionVente)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $sessionVente->station_id !== $user->station_id) {
            abort(403);
        }

        $sessionVente->load([
            'station',
            'user',
            'jaugeages.cuve.carburant',
            'jaugeages.user',
            'indexPompes.pompe.cuve.carburant',
            'indexPompes.user',
            'ventes.pistolet.pompe.cuve.carburant',
        ]);

        return view('session_ventes.show', compact('sessionVente'));
    }

    /* ──────────────────────── cloture (form) ──────────────────────── */

    public function cloture(SessionVente $sessionVente)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $sessionVente->station_id !== $user->station_id) {
            abort(403);
        }

        if ($sessionVente->isCloturee()) {
            return redirect()->route('session_ventes.show', $sessionVente)
                ->with('info', 'Cette session est déjà clôturée.');
        }

        $sessionVente->load([
            'indexPompes.pompe.cuve.carburant',
            'jaugeages.cuve.carburant',
        ]);

        // Cuves de la station pour le jaugeage fin
        $cuves = Cuve::where('station_id', $sessionVente->station_id)->with('carburant')->get();

        // Pompes déjà dans la session (pour saisir index_final)
        $indexPompes = $sessionVente->indexPompes()->with('pompe.cuve.carburant')->get();

        return view('session_ventes.cloture', compact('sessionVente', 'cuves', 'indexPompes'));
    }

    /* ──────────────────────── cloturer (traitement) ──────────────────────── */

    public function cloturer(Request $request, SessionVente $sessionVente)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $sessionVente->station_id !== $user->station_id) {
            abort(403);
        }

        if ($sessionVente->isCloturee()) {
            return redirect()->route('session_ventes.show', $sessionVente)
                ->with('info', 'Cette session est déjà clôturée.');
        }

        $request->validate([
            'date_fin'              => 'required|date|after_or_equal:' . $sessionVente->date_debut->format('Y-m-d H:i'),
            'jaugeages.*.cuve_id'   => 'nullable|exists:cuves,id',
            'jaugeages.*.quantite'  => 'nullable|numeric|min:0',
            'index_finaux.*.id'     => 'nullable|exists:index_pompes,id',
            'index_finaux.*.index'  => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Enregistrer les jaugeages de fin de session
            foreach ($request->jaugeages ?? [] as $j) {
                if (empty($j['quantite']) && $j['quantite'] !== '0') continue;

                $cuve = Cuve::findOrFail($j['cuve_id']);
                $ecart = (float)$j['quantite'] - (float)$cuve->stock_actuel;

                Jaugeage::create([
                    'numero_jaugeage'    => 'JAU-' . date('Ymd') . '-' . str_pad(Jaugeage::count() + 1, 5, '0', STR_PAD_LEFT),
                    'cuve_id'            => $j['cuve_id'],
                    'user_id'            => $user->id,
                    'session_vente_id'   => $sessionVente->id,
                    'type'               => 'fin_session',
                    'quantite_mesuree'   => $j['quantite'],
                    'quantite_theorique' => $cuve->stock_actuel,
                    'ecart'              => $ecart,
                    'date_jaugeage'      => $request->date_fin,
                    'observation'        => $j['observation'] ?? null,
                ]);
            }

            // Enregistrer les index finaux + calculer quantité vendue compteur
            foreach ($request->index_finaux ?? [] as $if) {
                if (!isset($if['id']) || !isset($if['index']) || $if['index'] === '') continue;

                $indexPompe = IndexPompe::findOrFail($if['id']);

                if ((float)$if['index'] > (float)$indexPompe->index_depart) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'L\'index final ne peut pas être supérieur à l\'index de départ pour la pompe ' . $indexPompe->pompe->nom . '.')
                        ->withInput();
                }

                $qteVendue = (float)$indexPompe->index_depart - (float)$if['index'];

                $indexPompe->update([
                    'index_final'              => $if['index'],
                    'quantite_vendue_compteur' => $qteVendue,
                    'date_releve_final'        => $request->date_fin,
                ]);
            }

            // Clôturer la session
            $sessionVente->update([
                'statut'   => 'cloturee',
                'date_fin' => $request->date_fin,
            ]);

            DB::commit();
            return redirect()->route('session_ventes.show', $sessionVente)
                ->with('success', 'Session clôturée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la clôture : ' . $e->getMessage())
                ->withInput();
        }
    }

    /* ──────────────────────── destroy ──────────────────────── */

    public function destroy(SessionVente $sessionVente)
    {
        if ($sessionVente->ventes()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer une session contenant des ventes.');
        }

        $sessionVente->jaugeages()->delete();
        $sessionVente->indexPompes()->delete();
        $sessionVente->delete();

        return redirect()->route('session_ventes.index')
            ->with('success', 'Session supprimée avec succès.');
    }
}
