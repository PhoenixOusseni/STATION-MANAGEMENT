@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="stop-circle"></i></div>
                                Clôturer la Session {{ $sessionVente->numero_session }}
                            </h1>
                            <p>
                                <small>
                                    Enregistrez les index finaux des pompes et les jaugeages de fin de session.
                                </small>
                            </p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('session_ventes.show', $sessionVente) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>&nbsp; Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <form action="{{ route('session_ventes.cloturer', $sessionVente) }}" method="POST">
                @csrf

                <div class="row">
                    {{-- ─── Colonne principale ─── --}}
                    <div class="col-lg-8">

                        {{-- Récap session --}}
                        <div class="alert alert-info d-flex align-items-center mb-4">
                            <i data-feather="info" class="me-3 flex-shrink-0"></i>
                            <div>
                                Session ouverte le <strong>{{ $sessionVente->date_debut->format('d/m/Y à H:i') }}</strong>
                                — <strong>{{ $sessionVente->ventes()->count() }}</strong> vente(s) enregistrée(s)
                                — <strong>{{ number_format($sessionVente->quantiteTotaleVendue(), 2) }} L</strong> vendus
                            </div>
                        </div>

                        {{-- Date de clôture --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark fw-bold">
                                <i data-feather="clock" class="me-2"></i>Date & Heure de Clôture
                            </div>
                            <div class="card-body">
                                <div class="col-md-6">
                                    <label class="form-label">Date & Heure <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="date_fin"
                                        class="form-control @error('date_fin') is-invalid @enderror"
                                        value="{{ old('date_fin', date('Y-m-d\TH:i')) }}" required>
                                    @error('date_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ─── Index Final des Pompes ─── --}}
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white fw-bold">
                                <i data-feather="activity" class="me-2"></i>Index Final des Pompes
                                <small class="d-block fw-normal mt-1">Relevez le compteur de chaque pompe à la fin de la session.</small>
                            </div>
                            <div class="card-body">
                                @if($indexPompes->isEmpty())
                                    <p class="text-muted">Aucun index de départ enregistré pour cette session.</p>
                                @else
                                    @foreach($indexPompes as $i => $ip)
                                        <input type="hidden" name="index_finaux[{{ $i }}][id]" value="{{ $ip->id }}">
                                        <div class="border rounded p-3 mb-3 bg-light">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">
                                                    <span class="badge bg-success me-2">{{ $ip->pompe->cuve->carburant->nom }}</span>
                                                    {{ $ip->pompe->nom }}
                                                </h6>
                                                <small class="text-muted">Cuve : {{ $ip->pompe->cuve->nom }}</small>
                                            </div>
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label form-label-sm">Index départ</label>
                                                    <input type="text" class="form-control form-control-sm bg-white"
                                                        value="{{ number_format($ip->index_depart, 2) }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label form-label-sm">Index final <span class="text-danger">*</span></label>
                                                    <input type="number"
                                                        name="index_finaux[{{ $i }}][index]"
                                                        class="form-control form-control-sm @error('index_finaux.' . $i . '.index') is-invalid @enderror"
                                                        value="{{ old('index_finaux.' . $i . '.index') }}"
                                                        min="{{ $ip->index_depart }}" step="0.01"
                                                        placeholder="ex : {{ number_format($ip->index_depart + 500, 2) }}"
                                                        data-depart="{{ $ip->index_depart }}"
                                                        oninput="calcQte(this)">
                                                    @error('index_finaux.' . $i . '.index')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label form-label-sm">Qté vendue compteur</label>
                                                    <input type="text" class="form-control form-control-sm bg-white qte-result"
                                                        value="-" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        {{-- ─── Jaugeages Fin de Session ─── --}}
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white fw-bold">
                                <i data-feather="droplet" class="me-2"></i>Jaugeages Fin de Session
                                <small class="d-block fw-normal mt-1">Mesurez physiquement chaque cuve après la session.</small>
                            </div>
                            <div class="card-body">
                                @if($cuves->isEmpty())
                                    <p class="text-muted">Aucune cuve disponible.</p>
                                @else
                                    @foreach($cuves as $index => $cuve)
                                        <input type="hidden" name="jaugeages[{{ $index }}][cuve_id]" value="{{ $cuve->id }}">
                                        <div class="border rounded p-3 mb-3 bg-light">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">
                                                    <span class="badge bg-info me-2">{{ $cuve->carburant->nom }}</span>
                                                    {{ $cuve->nom }}
                                                </h6>
                                                <small class="text-muted">
                                                    Stock théorique actuel : <strong>{{ number_format($cuve->stock_actuel, 0) }} L</strong>
                                                    / {{ number_format($cuve->capacite_max, 0) }} L
                                                </small>
                                            </div>

                                            {{-- Jaugeage début de cette session pour comparaison --}}
                                            @php
                                                $jaugeDebut = $sessionVente->jaugeages->where('cuve_id', $cuve->id)->where('type', 'debut_session')->first();
                                            @endphp
                                            @if($jaugeDebut)
                                                <div class="small text-muted mb-2">
                                                    Jauge début : <strong>{{ number_format($jaugeDebut->quantite_mesuree, 2) }} L</strong>
                                                    (écart début : {{ $jaugeDebut->ecart >= 0 ? '+' : '' }}{{ number_format($jaugeDebut->ecart, 2) }} L)
                                                </div>
                                            @endif

                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Quantité mesurée (L)</label>
                                                    <input type="number"
                                                        name="jaugeages[{{ $index }}][quantite]"
                                                        class="form-control form-control-sm"
                                                        value="{{ old('jaugeages.' . $index . '.quantite') }}"
                                                        min="0" step="0.01"
                                                        placeholder="ex : {{ number_format($cuve->stock_actuel, 0) }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Observation</label>
                                                    <input type="text"
                                                        name="jaugeages[{{ $index }}][observation]"
                                                        class="form-control form-control-sm"
                                                        placeholder="Optionnel">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-1"
                            onclick="return confirm('Confirmer la clôture de la session ' + '{{ $sessionVente->numero_session }}' + ' ?')">
                            <i data-feather="stop-circle" class="me-2"></i>Clôturer la Session
                        </button>
                    </div>

                    {{-- ─── Colonne latérale ─── --}}
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header bg-light text-dark">Guide de clôture</div>
                            <div class="card-body">
                                <ol class="ps-3">
                                    <li class="mb-2">
                                        <strong>Index final</strong> : relevez la valeur affichée sur le compteur de chaque pompe. L'écart avec l'index de départ donne la quantité vendue selon le compteur.
                                    </li>
                                    <li class="mb-2">
                                        <strong>Jaugeage fin</strong> : mesurez à nouveau physiquement le niveau de chaque cuve. L'écart avec le stock théorique révèle les pertes ou surplus éventuels.
                                    </li>
                                    <li>
                                        Comparez la quantité vendue (ventes saisies) avec la quantité selon le compteur pour détecter toute anomalie.
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        function calcQte(input) {
            const depart = parseFloat(input.getAttribute('data-depart')) || 0;
            const final  = parseFloat(input.value) || 0;
            const qte    = final >= depart ? (final - depart).toFixed(2) : '-';
            const block  = input.closest('.border');
            if (block) {
                const result = block.querySelector('.qte-result');
                if (result) result.value = qte !== '-' ? qte + ' L' : '-';
            }
        }
    </script>
@endsection
