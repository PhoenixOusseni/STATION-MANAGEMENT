@extends('layouts.master')

@section('style')
    @include('partials.style')
    <style>
        .profile-header {
            background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .profile-card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 500;
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
        }

        .stat-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateX(5px);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);
        }

        .activity-item {
            border-left: 2px solid #e9ecef;
            padding-left: 1.5rem;
            position: relative;
        }

        .activity-item:before {
            content: '';
            position: absolute;
            left: -6px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #c41e3a;
        }
    </style>
@endsection

@section('content')
    <main>
        <!-- Profile Header -->
        <header class="profile-header">
            <div class="container-xl px-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="{{ asset('images/avatar-default.png') }}" alt="Profile" class="profile-avatar"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name ?? $user->login) }}&size=150&background=c41e3a&color=fff'">
                    </div>
                    <div class="col">
                        <h1 class="text-white mb-2">{{ $user->name ?? $user->login }}</h1>
                        <p class="text-white-50 mb-2">
                            <i data-feather="mail" class="me-2"></i>{{ $user->email }}
                        </p>
                        <span class="badge badge-status bg-dark text-white">
                            <i data-feather="check-circle" class="me-1" style="width: 14px; height: 14px;"></i>
                            Compte actif
                        </span>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i data-feather="edit-2" class="me-2"></i>Modifier le profil
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Profile Content -->
        <div class="container-xl px-4 mt-n4 mb-5">
            <div class="row">
                <!-- Left Column -->
                <div class="col-xl-4 mb-4">
                    <!-- Informations personnelles -->
                    <div class="card profile-card mb-4">
                        <div class="card-header bg-transparent">
                            <h5 class="section-title mb-0">
                                <i data-feather="user" class="me-2"></i>Informations personnelles
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="small">Nom d'utilisateur</div>
                                <div class="info-value">{{ $user->login }}</div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <div class="small">Nom complet</div>
                                <div class="info-value">{{ $user->nom ?? 'N/A' }} {{ $user->prenom ?? 'N/A' }}</div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <div class="small">Email</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <div class="small">Module</div>
                                <div class="info-value text-capitalize">
                                    <span class="badge bg-primary">{{ $user->module }}</span>
                                </div>
                            </div>
                            @if ($user->telephone)
                                <hr>
                                <div class="mb-3">
                                    <div class="small">Téléphone</div>
                                    <div class="info-value">{{ $user->telephone }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informations de compte -->
                    <div class="card profile-card">
                        <div class="card-header bg-transparent">
                            <h5 class="section-title mb-0">
                                <i data-feather="shield" class="me-2"></i>Informations du compte
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="small">Date de création</div>
                                <div class="info-value">
                                    {{ $user->created_at ? $user->created_at->format('d/m/Y à H:i') : 'N/A' }}
                                </div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <div class="small">Dernière mise à jour</div>
                                <div class="info-value">
                                    {{ $user->updated_at ? $user->updated_at->format('d/m/Y à H:i') : 'N/A' }}
                                </div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <div class="small">ID Utilisateur</div>
                                <div class="info-value">#{{ $user->id }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-xl-8">
                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-6 col-xl-6 mb-4">
                            <div class="card profile-card stat-card border-start-primary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="small text-muted mb-1">Factures créées</div>
                                            <div class="h3 mb-0">0</div>
                                        </div>
                                        <div class="icon-box bg-primary-soft text-primary">
                                            <i data-feather="file-text"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 mb-4">
                            <div class="card profile-card stat-card border-start-success">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="small text-muted mb-1">Règlements traités</div>
                                            <div class="h3 mb-0">0</div>
                                        </div>
                                        <div class="icon-box bg-success-soft text-success">
                                            <i data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modules accessibles -->
                    <div class="card profile-card mb-4">
                        <div class="card-header bg-transparent">
                            <h5 class="section-title mb-0">
                                <i data-feather="grid" class="me-2"></i>Modules accessibles
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if (in_array($user->module, ['facturation', 'tous']))
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center p-3 bg-light rounded">
                                            <div class="icon-box bg-primary-soft text-primary me-3">
                                                <i data-feather="file-text"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Facturation</h6>
                                                <small class="text-muted">Gestion des factures et règlements</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array($user->module, ['diligence', 'tous']))
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center p-3 bg-light rounded">
                                            <div class="icon-box bg-success-soft text-success me-3">
                                                <i data-feather="check-square"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Diligences</h6>
                                                <small class="text-muted">Gestion des diligences</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->module == 'tous')
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center p-3 bg-light rounded">
                                            <div class="icon-box bg-warning-soft text-warning me-3">
                                                <i data-feather="settings"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Administration</h6>
                                                <small class="text-muted">Accès complet au système</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Activités récentes -->
                    <div class="card profile-card">
                        <div class="card-header bg-transparent">
                            <h5 class="section-title mb-0">
                                <i data-feather="activity" class="me-2"></i>Activités récentes
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="activity-item mb-3 pb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Connexion au système</h6>
                                        <p class="text-muted mb-0 small">Connexion réussie à votre compte</p>
                                    </div>
                                    <small class="text-muted">{{ now()->format('H:i') }}</small>
                                </div>
                            </div>
                            <div class="activity-item mb-3 pb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Consultation du profil</h6>
                                        <p class="text-muted mb-0 small">Vous avez consulté votre profil utilisateur</p>
                                    </div>
                                    <small class="text-muted">{{ now()->format('H:i') }}</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Accès au tableau de bord</h6>
                                        <p class="text-muted mb-0 small">Navigation vers le tableau de bord</p>
                                    </div>
                                    <small class="text-muted">{{ now()->subMinutes(5)->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de modification du profil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%); color: white;">
                    <h5 class="modal-title text-white" id="editProfileModalLabel">
                        <i data-feather="edit-2" class="me-2"></i>Modifier le profil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <h6 class="mb-3">Informations personnelles</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="{{ $user->nom }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="{{ $user->prenom }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" value="{{ $user->telephone ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="login" class="form-label">Nom d'utilisateur</label>
                                <input type="text" class="form-control" id="login" name="login" value="{{ $user->login }}" readonly>
                            </div>
                        </div>
                        <hr>
                        <h6 class="mb-3">Changer le mot de passe</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="current_password" class="form-label">Mot de passe actuel</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="m-3">
                        <button type="submit" class="btn btn-1">
                            <i data-feather="save" class="me-2"></i>Enregistrer les modifications
                        </button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                            <i data-feather="x" class="me-2"></i>Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('partials.script')
    <script>
        // Initialiser Feather Icons
        feather.replace();
    </script>
@endsection
