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
                                Nouveau Jaugeage
                            </h1>
                            <p><small>Enregistrez une mesure physique du niveau de carburant dans une cuve.</small></p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('jaugeages.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>&nbsp; Retour
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
                        <div class="card-header bg-light text-dark">Informations du Jaugeage</div>
                        <div class="card-body">
                            <form action="{{ route('jaugeages.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Cuve <span class="text-danger">*</span></label>
                                    <select name="cuve_id" id="cuve_id"
                                        class="form-select @error('cuve_id') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner une cuve --</option>
                                        @foreach($cuves as $cuve)
                                            <option value="{{ $cuve->id }}"
                                                data-stock="{{ $cuve->stock_actuel }}"
                                                data-carburant="{{ $cuve->carburant->nom }}"
                                                {{ old('cuve_id') == $cuve->id ? 'selected' : '' }}>
                                                {{ $cuve->nom }} — {{ $cuve->carburant->nom }}
                                                (Théorique : {{ number_format($cuve->stock_actuel, 0) }} L)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cuve_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Type de Jaugeage <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner --</option>
                                        <option value="debut_session" {{ old('type') == 'debut_session' ? 'selected' : '' }}>
                                            Jauge Début Session
                                        </option>
                                        <option value="fin_session" {{ old('type') == 'fin_session' ? 'selected' : '' }}>
                                            Jauge Fin Session
                                        </option>
                                        <option value="avant_depotage" {{ old('type') == 'avant_depotage' ? 'selected' : '' }}>
                                            Jauge Avant Dépotage
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Quantité Mesurée (L) <span class="text-danger">*</span></label>
                                        <input type="number" name="quantite_mesuree" id="quantite_mesuree"
                                            class="form-control @error('quantite_mesuree') is-invalid @enderror"
                                            value="{{ old('quantite_mesuree') }}" min="0" step="0.01" required>
                                        @error('quantite_mesuree')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Stock théorique : <span id="stock_theorique">--</span> L</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Écart calculé</label>
                                        <input type="text" id="ecart_display" class="form-control bg-light" readonly
                                            placeholder="Sélectionnez une cuve et saisissez la quantité">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Date du Jaugeage <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="date_jaugeage"
                                        class="form-control @error('date_jaugeage') is-invalid @enderror"
                                        value="{{ old('date_jaugeage', date('Y-m-d\TH:i')) }}" required>
                                    @error('date_jaugeage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Observation</label>
                                    <textarea name="observation"
                                        class="form-control @error('observation') is-invalid @enderror"
                                        rows="3">{{ old('observation') }}</textarea>
                                    @error('observation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-1">
                                        <i data-feather="save" class="me-2"></i>Enregistrer le Jaugeage
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header bg-light text-dark">Aide</div>
                        <div class="card-body">
                            <p><i data-feather="droplet" class="me-2"></i>
                                Le <strong>jaugeage</strong> est la mesure physique du niveau de carburant dans la cuve, effectuée à l'aide d'une jauge (barrette ou sonde).
                            </p>
                            <p><i data-feather="info" class="me-2"></i>
                                <strong>L'écart</strong> (mesure − stock théorique) indique un surplus (positif, en vert) ou une perte (négatif, en rouge).
                            </p>
                            <p><i data-feather="alert-triangle" class="me-2"></i>
                                Pour les jaugeages liés à une <strong>session de vente</strong> ou à un <strong>dépotage</strong>, utilisez directement le formulaire correspondant : le jaugeage sera créé automatiquement et lié à l'opération.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const cuveSelect       = document.getElementById('cuve_id');
        const qteInput         = document.getElementById('quantite_mesuree');
        const stockSpan        = document.getElementById('stock_theorique');
        const ecartDisplay     = document.getElementById('ecart_display');

        function updateEcart() {
            const opt   = cuveSelect.options[cuveSelect.selectedIndex];
            const stock = parseFloat(opt?.getAttribute('data-stock')) || null;
            const qte   = parseFloat(qteInput.value) || null;

            if (stock !== null) {
                stockSpan.textContent = new Intl.NumberFormat('fr-FR').format(stock);
            }

            if (stock !== null && qte !== null) {
                const ecart = qte - stock;
                ecartDisplay.value = (ecart >= 0 ? '+' : '') + new Intl.NumberFormat('fr-FR', {maximumFractionDigits: 2}).format(ecart) + ' L';
                ecartDisplay.style.color = ecart > 0 ? '#198754' : ecart < 0 ? '#dc3545' : '#6c757d';
            } else {
                ecartDisplay.value = '';
            }
        }

        cuveSelect.addEventListener('change', updateEcart);
        qteInput.addEventListener('input', updateEcart);
    </script>
@endsection
