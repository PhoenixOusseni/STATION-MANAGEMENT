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
                                Modifier le Carburant
                            </h1>
                            <p>
                                <small class="text-white">Utilisez ce formulaire pour modifier les informations du carburant. Assurez-vous que tous les champs obligatoires sont remplis avant de soumettre.</small>
                            </p>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('carburants.index') }}" class="btn btn-light btn-sm">Retour</a>
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
                            <form action="{{ route('carburants.update', $carburant) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" name="nom"
                                            class="form-control @error('nom') is-invalid @enderror"
                                            value="{{ old('nom', $carburant->nom) }}" required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code"
                                            class="form-control @error('code') is-invalid @enderror"
                                            value="{{ old('code', $carburant->code) }}" required>
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
                                        value="{{ old('prix_unitaire', $carburant->prix_unitaire) }}" min="0"
                                        step="0.01" required>
                                    @error('prix_unitaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $carburant->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-1">
                                        <i data-feather="save" class="me-2"></i>Mettre à jour
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-light text-dark">Aide</div>
                        <div class="card-body">
                            <p>Utilisez ce formulaire pour modifier les informations du carburant. Assurez-vous que tous les champs obligatoires sont remplis avant de soumettre.</p>
                            <ul>
                                <li><strong>Nom:</strong> Le nom du carburant (ex: Super 91, Gazoil).</li>
                                <li><strong>Code:</strong> Un code unique pour le carburant (ex: S91, GAZ).</li>
                                <li><strong>Prix Unitaire:</strong> Le prix par litre en FCFA.</li>
                                <li><strong>Description:</strong> Toute information supplémentaire sur le carburant.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
