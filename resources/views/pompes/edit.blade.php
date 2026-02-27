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
                                Modifier — {{ $pompe->nom }}
                            </h1>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('pompes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header bg-light text-dark">Informations de la Pompe</div>
                        <div class="card-body">
                            <form action="{{ route('pompes.update', $pompe) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom"
                                        class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom', $pompe->nom) }}" required>
                                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Cuve de rattachement <span class="text-danger">*</span></label>
                                    <select name="cuve_id" class="form-select @error('cuve_id') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner une cuve --</option>
                                        @foreach($cuves as $cuve)
                                            <option value="{{ $cuve->id }}"
                                                {{ old('cuve_id', $pompe->cuve_id) == $cuve->id ? 'selected' : '' }}>
                                                {{ $cuve->nom }} — {{ $cuve->carburant->nom }}
                                                ({{ $cuve->station->nom ?? 'Sans station' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cuve_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Numéro de Série</label>
                                    <input type="text" name="numero_serie"
                                        class="form-control @error('numero_serie') is-invalid @enderror"
                                        value="{{ old('numero_serie', $pompe->numero_serie) }}">
                                    @error('numero_serie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">État <span class="text-danger">*</span></label>
                                        <select name="etat" class="form-select @error('etat') is-invalid @enderror" required>
                                            <option value="actif"       {{ old('etat', $pompe->etat) == 'actif'       ? 'selected' : '' }}>Actif</option>
                                            <option value="inactif"     {{ old('etat', $pompe->etat) == 'inactif'     ? 'selected' : '' }}>Inactif</option>
                                            <option value="maintenance" {{ old('etat', $pompe->etat) == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                                        </select>
                                        @error('etat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Date dernière maintenance</label>
                                        <input type="date" name="date_maintenance"
                                            class="form-control @error('date_maintenance') is-invalid @enderror"
                                            value="{{ old('date_maintenance', $pompe->date_maintenance?->format('Y-m-d')) }}">
                                        @error('date_maintenance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
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
            </div>
        </div>
    </main>
@endsection
