@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="user-plus"></i></div>
                                Nouvel Utilisateur
                            </h1>
                            <p><small class="text-white">Vous pouvez créer un nouvel utilisateur ici. Tous les champs marqués
                                    d'un astérisque (*) sont obligatoires.</small></p>
                        </div>
                        <div class="col-auto mt-4">
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
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light text-dark">Informations de l'Utilisateur</div>
                        <div class="card-body">
                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                        <input type="text" name="prenom"
                                            class="form-control @error('prenom') is-invalid @enderror"
                                            value="{{ old('prenom') }}" required>
                                        @error('prenom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" name="nom"
                                            class="form-control @error('nom') is-invalid @enderror"
                                            value="{{ old('nom') }}" required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Téléphone</label>
                                        <input type="text" name="telephone"
                                            class="form-control @error('telephone') is-invalid @enderror"
                                            value="{{ old('telephone') }}">
                                        @error('telephone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Rôle <span class="text-danger">*</span></label>
                                        <select name="role" id="role"
                                            class="form-select @error('role') is-invalid @enderror" required>
                                            <option value="">-- Sélectionner --</option>
                                            @if (auth()->user()->isAdmin())
                                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                                    Administrateur</option>
                                            @endif
                                            <option value="gestionnaire"
                                                {{ old('role') == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                                            <option value="pompiste" {{ old('role') == 'pompiste' ? 'selected' : '' }}>
                                                Pompiste</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Station <span class="text-danger">*</span></label>
                                        <select name="station_id"
                                            class="form-select @error('station_id') is-invalid @enderror" required>
                                            <option value="">-- Sélectionner --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                                    {{ $station->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('station_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Minimum 8 caractères</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Confirmer le mot de passe <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="actif" id="actif"
                                            value="1" {{ old('actif', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="actif">
                                            Compte actif
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-1">
                                        <i data-feather="save" class="me-2"></i>Créer l'Utilisateur
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Rôles et Permissions</div>
                        <div class="card-body">
                            <p>Assignez un rôle à cet utilisateur pour définir ses permissions dans le système. Les rôles
                                disponibles sont:</p>
                            <ul>
                                <li><strong>Admin:</strong> Accès complet à toutes les fonctionnalités.</li>
                                <li><strong>Gestionnaire:</strong> Peut gérer les stations, cuves, carburants et
                                    utilisateurs (sauf les admins).</li>
                                <li><strong>Pompiste:</strong> Accès limité aux fonctions de vente et de gestion des
                                    pistolets.</li>
                            </ul>
                            <p>Choisissez le rôle approprié en fonction des responsabilités de l'utilisateur.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
