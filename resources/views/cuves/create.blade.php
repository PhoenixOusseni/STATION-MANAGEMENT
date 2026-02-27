@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="database"></i></div>
                            Ajouter une Cuve
                        </h1>
                        <p class="text-white">Remplissez le formulaire ci-dessous pour ajouter une nouvelle cuve.</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('cuves.index') }}" class="btn btn-dark btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>&nbsp; Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card">
            <div class="card-header">Informations de la Cuve</div>
            <div class="card-body">
                <form action="{{ route('cuves.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Station <span class="text-danger">*</span></label>
                            <select name="station_id" class="form-select @error('station_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                        {{ $station->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('station_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Carburant <span class="text-danger">*</span></label>
                            <select name="carburant_id" class="form-select @error('carburant_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($carburants as $carburant)
                                    <option value="{{ $carburant->id }}" {{ old('carburant_id') == $carburant->id ? 'selected' : '' }}>
                                        {{ $carburant->nom }} ({{ $carburant->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('carburant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom de la Cuve <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                               value="{{ old('nom') }}" placeholder="Ex: Cuve Super 91 - A" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Capacité Maximale (Litres) <span class="text-danger">*</span></label>
                            <input type="number" name="capacite_max" class="form-control @error('capacite_max') is-invalid @enderror"
                                   value="{{ old('capacite_max') }}" min="0" step="0.01" required>
                            @error('capacite_max')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stock Actuel (Litres) <span class="text-danger">*</span></label>
                            <input type="number" name="stock_actuel" class="form-control @error('stock_actuel') is-invalid @enderror"
                                   value="{{ old('stock_actuel', 0) }}" min="0" step="0.01" required>
                            @error('stock_actuel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stock Minimum (Litres) <span class="text-danger">*</span></label>
                            <input type="number" name="stock_min" class="form-control @error('stock_min') is-invalid @enderror"
                                   value="{{ old('stock_min') }}" min="0" step="0.01" required>
                            @error('stock_min')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Numéro de Série</label>
                        <input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror"
                               value="{{ old('numero_serie') }}" placeholder="Ex: CUV-S91-001">
                        @error('numero_serie')
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
</main>
@endsection
