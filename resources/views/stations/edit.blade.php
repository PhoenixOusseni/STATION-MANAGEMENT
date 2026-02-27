@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit"></i></div>
                            Modifier la Station
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card">
            <div class="card-header">Informations de la Station</div>
            <div class="card-body">
                <form action="{{ route('stations.update', $station) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom de la Station <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom', $station->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control @error('ville') is-invalid @enderror"
                                   value="{{ old('ville', $station->ville) }}">
                            @error('ville')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse <span class="text-danger">*</span></label>
                        <textarea name="adresse" class="form-control @error('adresse') is-invalid @enderror"
                                  rows="2" required>{{ old('adresse', $station->adresse) }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                                   value="{{ old('telephone', $station->telephone) }}">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $station->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Responsable</label>
                        <input type="text" name="responsable" class="form-control @error('responsable') is-invalid @enderror"
                               value="{{ old('responsable', $station->responsable) }}">
                        @error('responsable')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save" class="me-2"></i>Mettre à jour
                        </button>
                        <a href="{{ route('stations.index') }}" class="btn btn-light">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
