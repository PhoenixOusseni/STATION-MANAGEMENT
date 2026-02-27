@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="user"></i></div>
                                Profil Utilisateur
                            </h1>
                        </div>
                        <div class="col-auto mt-4">
                            @if (auth()->user()->isAdmin() || (auth()->user()->isGestionnaire() && $user->role != 'admin'))
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                                    <i data-feather="edit" class="me-2"></i>Modifier
                                </a>
                            @endif
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                                <i data-feather="arrow-left" class="me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="avatar-xl mx-auto mb-3">
                                <div class="avatar-initials-xl bg-primary">
                                    {{ strtoupper(substr($user->prenom, 0, 1) . substr($user->nom, 0, 1)) }}
                                </div>
                            </div>
                            <h4>{{ $user->prenom }} {{ $user->nom }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>
                            <div class="mb-3">
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger fs-6">Administrateur</span>
                                    @break

                                    @case('gestionnaire')
                                        <span class="badge bg-info fs-6">Gestionnaire</span>
                                    @break

                                    @case('pompiste')
                                        <span class="badge bg-success fs-6">Pompiste</span>
                                    @break
                                @endswitch
                            </div>
                            @if ($user->actif)
                                <span class="badge bg-success">Compte Actif</span>
                            @else
                                <span class="badge bg-secondary">Compte Inactif</span>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light text-dark">Informations</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Station:</strong>
                                <p>{{ $user->station->nom }}</p>
                            </div>
                            @if ($user->telephone)
                                <div class="mb-3">
                                    <strong>Téléphone:</strong>
                                    <p>{{ $user->telephone }}</p>
                                </div>
                            @endif
                            <div class="mb-3">
                                <strong>Membre depuis:</strong>
                                <p>{{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    @if ($user->role == 'pompiste' || $user->role == 'admin')
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark">
                                <i data-feather="zap"></i> Pistolets Assignés
                            </div>
                            <div class="card-body">
                                @if ($user->pistolets->count() > 0)
                                    <table class="table table-hover table-bordered table-striped mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>N° Pistolet</th>
                                                <th>Pompe</th>
                                                <th>Carburant</th>
                                                <th>Cuve</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->pistolets as $pistolet)
                                                <tr>
                                                    <td><strong>{{ $pistolet->numero }}</strong></td>
                                                    <td>{{ $pistolet->pompe->numero }}</td>
                                                    <td><span
                                                            class="badge bg-info">{{ $pistolet->pompe->cuve->carburant->nom }}</span>
                                                    </td>
                                                    <td>{{ $pistolet->pompe->cuve->nom }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted">Aucun pistolet assigné.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">
                            <i data-feather="trending-up"></i> Statistiques d'Activité
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <h3 class="text-primary">{{ $user->ventes->count() }}</h3>
                                        <small class="text-muted">Ventes Enregistrées</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <h3 class="text-success">{{ $user->entrees->count() }}</h3>
                                        <small class="text-muted">Entrées Enregistrées</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <h3 class="text-info">{{ $user->commandes->count() }}</h3>
                                        <small class="text-muted">Commandes Passées</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Activité Récente</div>
                        <div class="card-body">
                            @php
                                $recentActivities = collect()
                                    ->merge(
                                        $user
                                            ->ventes()
                                            ->latest()
                                            ->take(5)
                                            ->get()
                                            ->map(function ($vente) {
                                                return [
                                                    'type' => 'vente',
                                                    'icon' => 'dollar-sign',
                                                    'color' => 'success',
                                                    'description' =>
                                                        'Vente de ' .
                                                        number_format($vente->quantite, 0) .
                                                        'L - ' .
                                                        number_format($vente->montant_total, 0) .
                                                        ' FCFA',
                                                    'date' => $vente->created_at,
                                                ];
                                            }),
                                    )
                                    ->merge(
                                        $user
                                            ->entrees()
                                            ->latest()
                                            ->take(5)
                                            ->get()
                                            ->map(function ($entree) {
                                                return [
                                                    'type' => 'entree',
                                                    'icon' => 'arrow-down-circle',
                                                    'color' => 'primary',
                                                    'description' =>
                                                        'Réception de ' .
                                                        number_format($entree->quantite, 0) .
                                                        'L dans ' .
                                                        $entree->cuve->nom,
                                                    'date' => $entree->created_at,
                                                ];
                                            }),
                                    )
                                    ->merge(
                                        $user
                                            ->commandes()
                                            ->latest()
                                            ->take(5)
                                            ->get()
                                            ->map(function ($commande) {
                                                return [
                                                    'type' => 'commande',
                                                    'icon' => 'shopping-cart',
                                                    'color' => 'info',
                                                    'description' =>
                                                        'Commande ' .
                                                        $commande->numero_commande .
                                                        ' - ' .
                                                        $commande->statut,
                                                    'date' => $commande->created_at,
                                                ];
                                            }),
                                    )
                                    ->sortByDesc('date')
                                    ->take(10);
                            @endphp

                            @if ($recentActivities->count() > 0)
                                <ul class="list-unstyled">
                                    @foreach ($recentActivities as $activity)
                                        <li class="mb-3 pb-3 border-bottom">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    <span class="badge bg-{{ $activity['color'] }}">
                                                        <i data-feather="{{ $activity['icon'] }}"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="mb-1">{{ $activity['description'] }}</p>
                                                    <small
                                                        class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">Aucune activité récente.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .avatar-xl {
            width: 100px;
            height: 100px;
            display: inline-block;
        }

        .avatar-initials-xl {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-weight: bold;
            font-size: 2.5rem;
        }
    </style>
@endsection
