@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="droplet"></i></div>
                                Ajouter un Carburant
                            </h1>
                            <p>
                                <small class="text-white">Vous pouvez ajouter un nouveau carburant ici. Tous les champs marqués d'un astérisque (*) sont obligatoires.</small>
                            </p>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('carburants.index') }}" class="btn btn-light btn-sm">
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
                        <div class="card-header bg-light text-dark">Informations du Carburant</div>
                        <div class="card-body">
                            <form action="{{ route('carburants.store') }}" method="POST">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" name="nom"
                                            class="form-control @error('nom') is-invalid @enderror"
                                            value="{{ old('nom') }}" placeholder="Ex: Super 91, Gazoil" required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code"
                                            class="form-control @error('code') is-invalid @enderror"
                                            value="{{ old('code') }}" placeholder="Ex: S91, GAZ" required>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Prix Unitaire (FCFA) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="prix_unitaire"
                                        class="form-control @error('prix_unitaire') is-invalid @enderror"
                                        value="{{ old('prix_unitaire') }}" min="0" step="0.01" required>
                                    @error('prix_unitaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-1">
                                        <i data-feather="save" class="me-2"></i>Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-light text-dark">Conseils pour le Carburant</div>
                        <div class="card-body">
                            <ul>
                                <li>Utilisez des noms clairs et descriptifs (ex: Super 91, Gazoil).</li>
                                <li>Le code doit être court et facilement identifiable (ex: S91, GAZ).</li>
                                <li>Assurez-vous que le prix unitaire est à jour et reflète les coûts actuels.</li>
                                <li>La description peut inclure des informations sur la qualité ou les spécifications du
                                    carburant.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
