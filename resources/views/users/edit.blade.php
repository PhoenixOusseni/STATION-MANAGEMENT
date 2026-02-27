@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="edit"></i></div>
                                Modifier l'Utilisateur
                            </h1>
                            <p>
                                <small class="text-white">Vous pouvez modifier les informations de l'utilisateur ici. Laissez
                                    le champ du mot de passe vide pour conserver l'ancien mot de passe.</small>
                            </p>
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
                            <form action="{{ route('users.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                        <input type="text" name="prenom"
                                            class="form-control @error('prenom') is-invalid @enderror"
                                            value="{{ old('prenom', $user->prenom) }}" required>
                                        @error('prenom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" name="nom"
                                            class="form-control @error('nom') is-invalid @enderror"
                                            value="{{ old('nom', $user->nom) }}" required>
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
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Téléphone</label>
                                        <input type="text" name="telephone"
                                            class="form-control @error('telephone') is-invalid @enderror"
                                            value="{{ old('telephone', $user->telephone) }}">
                                        @error('telephone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Rôle <span class="text-danger">*</span></label>
                                        <select name="role" id="role"
                                            class="form-select @error('role') is-invalid @enderror" required
                                            @if (!auth()->user()->isAdmin() && $user->role == 'admin') disabled @endif>
                                            <option value="">-- Sélectionner --</option>
                                            @if (auth()->user()->isAdmin())
                                                <option value="admin"
                                                    {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                                    Administrateur</option>
                                            @endif
                                            <option value="gestionnaire"
                                                {{ old('role', $user->role) == 'gestionnaire' ? 'selected' : '' }}>
                                                Gestionnaire</option>
                                            <option value="pompiste"
                                                {{ old('role', $user->role) == 'pompiste' ? 'selected' : '' }}>Pompiste
                                            </option>
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
                                                    {{ old('station_id', $user->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('station_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h5 class="mb-3">Changer le mot de passe (optionnel)</h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nouveau mot de passe</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Laissez vide pour conserver l'ancien mot de passe</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="actif" id="actif"
                                            value="1" {{ old('actif', $user->actif) == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="actif">
                                            Compte actif
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-1">
                                        <i data-feather="save" class="me-2"></i>Enregistrer les Modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Informations de la Station</div>
                        <div class="card-body">
                            @if ($user->station)
                                <h5>{{ $user->station->nom }}</h5>
                                <p><i data-feather="map-pin" class="me-2"></i>{{ $user->station->ville }}</p>
                                <p><i data-feather="phone" class="me-2"></i>{{ $user->station->telephone }}</p>
                                <p><i data-feather="mail" class="me-2"></i>{{ $user->station->email }}</p>
                            @else
                                <p>Aucune station assignée.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
