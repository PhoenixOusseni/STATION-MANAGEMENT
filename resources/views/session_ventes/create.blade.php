@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="play-circle"></i></div>
                                Ouvrir une Session de Vente
                            </h1>
                            <p><small>Enregistrez les jaugeages de début et les index de départ des pompes.</small></p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('session_ventes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>&nbsp; Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <form action="{{ route('session_ventes.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- ─── Colonne principale ─── --}}
                    <div class="col-lg-8">

                        {{-- Informations générales --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark fw-bold">
                                <i data-feather="info" class="me-2"></i>Informations de la Session
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Date & Heure d'ouverture <span
                                                class="text-danger">*</span></label>
                                        <input type="datetime-local" name="date_debut"
                                            class="form-control @error('date_debut') is-invalid @enderror"
                                            value="{{ old('date_debut', date('Y-m-d\TH:i')) }}" required>
                                        @error('date_debut')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Observation</label>
                                    <textarea name="observation" class="form-control" rows="2">{{ old('observation') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ─── Jaugeages Début de Session ─── --}}
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white fw-bold">
                                <i data-feather="droplet" class="me-2"></i>Jaugeages Début de Session
                                <small class="d-block fw-normal mt-1">Mesurez physiquement chaque cuve avant de commencer la
                                    vente.</small>
                            </div>
                            <div class="card-body">
                                @if ($cuves->isEmpty())
                                    <p class="text-muted">Aucune cuve disponible pour cette station.</p>
                                @else
                                    @foreach ($cuves as $index => $cuve)
                                        <input type="hidden" name="jaugeages[{{ $index }}][cuve_id]"
                                            value="{{ $cuve->id }}">
                                        <div class="border rounded p-3 mb-3 bg-light">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">
                                                    <span class="badge bg-info me-2">{{ $cuve->carburant->nom }}</span>
                                                    {{ $cuve->nom }}
                                                </h6>
                                                <small class="text-muted">
                                                    Stock théorique : <strong>{{ number_format($cuve->stock_actuel, 0) }}
                                                        L</strong>
                                                    / {{ number_format($cuve->capacite_max, 0) }} L
                                                </small>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Quantité mesurée (L)</label>
                                                    <input type="number" name="jaugeages[{{ $index }}][quantite]"
                                                        class="form-control form-control-sm @error('jaugeages.' . $index . '.quantite') is-invalid @enderror"
                                                        value="{{ old('jaugeages.' . $index . '.quantite') }}"
                                                        min="0" step="0.01"
                                                        placeholder="ex : {{ number_format($cuve->stock_actuel, 0) }}">
                                                    @error('jaugeages.' . $index . '.quantite')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Observation</label>
                                                    <input type="text"
                                                        name="jaugeages[{{ $index }}][observation]"
                                                        class="form-control form-control-sm"
                                                        value="{{ old('jaugeages.' . $index . '.observation') }}"
                                                        placeholder="Optionnel">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        {{-- ─── Index Départ Pompes ─── --}}
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white fw-bold">
                                <i data-feather="activity" class="me-2"></i>Relevés Index Départ des Pompes
                                <small class="d-block fw-normal mt-1">Notez le compteur de chaque pompe avant le
                                    démarrage.</small>
                            </div>
                            <div class="card-body">
                                @if ($pompes->isEmpty())
                                    <p class="text-muted">Aucune pompe active disponible.</p>
                                @else
                                    @foreach ($pompes as $index => $pompe)
                                        <input type="hidden" name="index_pompes[{{ $index }}][pompe_id]"
                                            value="{{ $pompe->id }}">
                                        <div class="border rounded p-3 mb-3 bg-light">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">
                                                    <span
                                                        class="badge bg-success me-2">{{ $pompe->cuve->carburant->nom }}</span>
                                                    {{ $pompe->nom }}
                                                </h6>
                                                <small class="text-muted">Cuve : {{ $pompe->cuve->nom }}</small>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Index départ (m³ ou L)</label>
                                                    <input type="number" name="index_pompes[{{ $index }}][index]"
                                                        class="form-control form-control-sm @error('index_pompes.' . $index . '.index') is-invalid @enderror"
                                                        value="{{ old('index_pompes.' . $index . '.index') }}"
                                                        min="0" step="0.01" placeholder="ex : 12450.50">
                                                    @error('index_pompes.' . $index . '.index')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Observation</label>
                                                    <input type="text"
                                                        name="index_pompes[{{ $index }}][observation]"
                                                        class="form-control form-control-sm"
                                                        value="{{ old('index_pompes.' . $index . '.observation') }}"
                                                        placeholder="Optionnel">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-1">
                            <i data-feather="play-circle" class="me-2"></i>Ouvrir la Session
                        </button>
                    </div>

                    {{-- ─── Colonne latérale ─── --}}
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header bg-light text-dark">Guide d'ouverture</div>
                            <div class="card-body">
                                <ol class="ps-3">
                                    <li class="mb-2">
                                        <strong>Jaugeage début</strong> : mesurez physiquement le niveau de carburant dans
                                        chaque cuve avec une jauge (barrette ou sonde). Notez la quantité réelle en litres.
                                    </li>
                                    <li class="mb-2">
                                        <strong>Index départ</strong> : relevez le compteur affiché sur chaque pompe. Cette
                                        valeur servira à calculer la quantité vendue en fin de session.
                                    </li>
                                    <li>
                                        Après ouverture, enregistrez toutes vos ventes en les associant à cette session.
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
