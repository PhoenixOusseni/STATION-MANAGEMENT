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
                                Modifier — {{ $pistolet->nom }}
                            </h1>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('pistolets.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Retour
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
                        <div class="card-header bg-light text-dark">Informations du Pistolet</div>
                        <div class="card-body">
                            <form action="{{ route('pistolets.update', $pistolet) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom"
                                        class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom', $pistolet->nom) }}" required>
                                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Numéro (unique) <span class="text-danger">*</span></label>
                                    <input type="text" name="numero"
                                        class="form-control @error('numero') is-invalid @enderror"
                                        value="{{ old('numero', $pistolet->numero) }}" required>
                                    @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pompe <span class="text-danger">*</span></label>
                                    <select name="pompe_id" class="form-select @error('pompe_id') is-invalid @enderror" required>
                                        <option value="" disabled>-- Sélectionner une pompe --</option>
                                        @foreach($pompes as $pompe)
                                            <option value="{{ $pompe->id }}"
                                                {{ old('pompe_id', $pistolet->pompe_id) == $pompe->id ? 'selected' : '' }}>
                                                {{ $pompe->nom }} — {{ $pompe->cuve->carburant->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pompe_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pompiste assigné</label>
                                    <select name="pompiste_id" class="form-select @error('pompiste_id') is-invalid @enderror">
                                        <option value="" disabled>-- Aucun --</option>
                                        @foreach($pompistes as $pompiste)
                                            <option value="{{ $pompiste->id }}"
                                                {{ old('pompiste_id', $pistolet->pompiste_id) == $pompiste->id ? 'selected' : '' }}>
                                                {{ $pompiste->prenom }} {{ $pompiste->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pompiste_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">État <span class="text-danger">*</span></label>
                                    <select name="etat" class="form-select @error('etat') is-invalid @enderror" required>
                                        <option value="actif"       {{ old('etat', $pistolet->etat) == 'actif'       ? 'selected' : '' }}>Actif</option>
                                        <option value="inactif"     {{ old('etat', $pistolet->etat) == 'inactif'     ? 'selected' : '' }}>Inactif</option>
                                        <option value="maintenance" {{ old('etat', $pistolet->etat) == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                                    </select>
                                    @error('etat')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                            <p>Modifiez les informations du pistolet. Les champs marqués d'une astérisque (<span class="text-danger">*</span>) sont obligatoires.</p>
                            <ul>
                                <li><strong>Nom :</strong> Le nom du pistolet (ex : Pistolet A).</li>
                                <li><strong>Numéro :</strong> Un identifiant unique pour le pistolet (ex : P001).</li>
                                <li><strong>Pompe :</strong> La pompe à laquelle ce pistolet est associé.</li>
                                <li><strong>Pompiste assigné :</strong> Le pompiste responsable de ce pistolet (optionnel).</li>
                                <li><strong>État :</strong> L'état actuel du pistolet (actif, inactif, en maintenance).</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
