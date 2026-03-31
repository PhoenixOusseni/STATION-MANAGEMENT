@extends('layouts.master')

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="dollar-sign" class="text-white"></i></div>
                                Nouvelle Vente
                            </h1>
                            <p class="text-white">
                                <small>Sélectionnez le pistolet, saisissez l'index final et le retour cuve.</small>
                            </p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('ventes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>&nbsp; Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-n10">
            <div class="row">
                {{-- ─── Formulaire principal ─── --}}
                <div class="col-lg-8">
                    <form action="{{ route('ventes.store') }}" method="POST">
                        @csrf
                        @if ($sessionActive)
                            <input type="hidden" name="session_vente_id" value="{{ $sessionActive->id }}">
                        @endif

                        {{-- Pistolet + Date --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark fw-bold">
                                <i data-feather="settings" class="me-2"></i>Sélection Pistolet
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Pistolet <span class="text-danger">*</span></label>
                                        <select name="pistolet_id" id="pistolet_id"
                                            class="form-select @error('pistolet_id') is-invalid @enderror" required>
                                            <option value="">-- Sélectionner un pistolet --</option>
                                            @foreach ($pistolets as $pistolet)
                                                <option value="{{ $pistolet->id }}"
                                                    data-carburant="{{ $pistolet->pompe->cuve->carburant->nom }}"
                                                    data-prix="{{ $pistolet->pompe->cuve->carburant->prix_unitaire }}"
                                                    data-stock="{{ $pistolet->pompe->cuve->stock_actuel }}"
                                                    data-pompe-id="{{ $pistolet->pompe_id }}"
                                                    data-index-depart="{{ $indexDepartMap[$pistolet->pompe_id] ?? '' }}"
                                                    {{ old('pistolet_id') == $pistolet->id ? 'selected' : '' }}>
                                                    {{ $pistolet->numero }} — {{ $pistolet->pompe->nom ?? 'Pompe '.$pistolet->pompe->numero }}
                                                    ({{ $pistolet->pompe->cuve->carburant->nom }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pistolet_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Carburant</label>
                                        <input type="text" id="carburant_nom" class="form-control bg-light" readonly
                                            placeholder="-- auto --">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Date de Vente <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="date_vente"
                                            class="form-control @error('date_vente') is-invalid @enderror"
                                            value="{{ old('date_vente', date('Y-m-d\TH:i')) }}" required>
                                        @error('date_vente')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">N° de Ticket</label>
                                        <input type="text" name="numero_ticket"
                                            class="form-control @error('numero_ticket') is-invalid @enderror"
                                            value="{{ old('numero_ticket') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Index & Quantités --}}
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white fw-bold">
                                <i data-feather="activity" class="me-2"></i>Relevé Compteur & Quantités
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    {{-- Index Initial --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Index Initial (départ session)</label>
                                        <div class="input-group">
                                            <input type="number" name="index_depart" id="index_depart"
                                                class="form-control bg-light @error('index_depart') is-invalid @enderror"
                                                value="{{ old('index_depart') }}"
                                                min="0" step="0.01" readonly
                                                placeholder="-- auto depuis session --">
                                            <span class="input-group-text text-muted" id="index_depart_badge">
                                                <i data-feather="lock" style="width:14px;height:14px;"></i>
                                            </span>
                                        </div>
                                        <small class="text-muted" id="index_depart_info">Rempli automatiquement depuis l'ouverture de session.</small>
                                        @error('index_depart')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Index Final --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Index Final <span class="text-danger">*</span></label>
                                        <input type="number" name="index_final" id="index_final"
                                            class="form-control @error('index_final') is-invalid @enderror"
                                            value="{{ old('index_final') }}"
                                            min="0" step="0.01" placeholder="Relevez le compteur">
                                        @error('index_final')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Quantité compteur --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Quantité Compteur (L)</label>
                                        <input type="number" name="quantite" id="quantite"
                                            class="form-control bg-light @error('quantite') is-invalid @enderror"
                                            value="{{ old('quantite', 0) }}"
                                            min="0" step="0.01" readonly>
                                        <small class="text-muted">= Index Initial − Index Final</small>
                                        @error('quantite')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Retour cuve --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Retour Cuve (L)</label>
                                        <input type="number" name="retour_cuve" id="retour_cuve"
                                            class="form-control @error('retour_cuve') is-invalid @enderror"
                                            value="{{ old('retour_cuve', 0) }}"
                                            min="0" step="0.01" placeholder="0">
                                        <small class="text-muted">Carburant retourné en cuve</small>
                                        @error('retour_cuve')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Quantité vendue --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-success">Quantité Vendue (L)</label>
                                        <input type="number" name="quantite_vendue" id="quantite_vendue"
                                            class="form-control bg-light fw-bold @error('quantite_vendue') is-invalid @enderror"
                                            value="{{ old('quantite_vendue', 0) }}"
                                            min="0" step="0.01" readonly>
                                        <small class="text-muted">= Quantité − Retour Cuve</small>
                                        @error('quantite_vendue')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Prix & Montant --}}
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white fw-bold">
                                <i data-feather="dollar-sign" class="me-2"></i>Prix & Montant
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">Prix Unitaire (FCFA)</label>
                                        <input type="number" name="prix_unitaire" id="prix_unitaire"
                                            class="form-control bg-light @error('prix_unitaire') is-invalid @enderror"
                                            value="{{ old('prix_unitaire') }}" readonly>
                                        <small class="text-muted">Prix automatique du carburant</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Montant Théorique</label>
                                        <input type="text" id="montant_theorique" class="form-control bg-light" readonly
                                            placeholder="0 FCFA">
                                        <small class="text-muted">Quantité Compteur × Prix</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-success">Montant Réel (Total)</label>
                                        <input type="text" id="montant_reel" class="form-control bg-light fw-bold text-success" readonly
                                            placeholder="0 FCFA">
                                        <small class="text-muted">Qté Vendue × Prix</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Mode de paiement --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light text-dark fw-bold">
                                <i data-feather="credit-card" class="me-2"></i>Mode de Paiement
                            </div>
                            <div class="card-body">
                                <div class="row g-2 mb-3">
                                    @foreach ([
                                        'especes'      => ['label' => 'Espèces',      'icon' => 'dollar-sign'],
                                        'carte'        => ['label' => 'Carte Bancaire','icon' => 'credit-card'],
                                        'mobile_money' => ['label' => 'Mobile Money', 'icon' => 'smartphone'],
                                        'cheque'       => ['label' => 'Chèque',        'icon' => 'file-text'],
                                        'credit'       => ['label' => 'À Crédit',      'icon' => 'clock'],
                                    ] as $value => $opt)
                                        <div class="col-auto">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                    name="mode_paiement" id="mp_{{ $value }}"
                                                    value="{{ $value }}"
                                                    {{ old('mode_paiement', 'especes') == $value ? 'checked' : '' }}
                                                    required>
                                                <label class="form-check-label" for="mp_{{ $value }}">
                                                    <i data-feather="{{ $opt['icon'] }}" style="width:14px;height:14px;" class="me-1"></i>
                                                    {{ $opt['label'] }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('mode_paiement')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                <div id="client_field" style="display:none;">
                                    <label class="form-label">Nom du Client <span class="text-danger">*</span></label>
                                    <input type="text" name="client"
                                        class="form-control @error('client') is-invalid @enderror"
                                        value="{{ old('client') }}" placeholder="Nom du client créditeur">
                                    @error('client')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Observation --}}
                        <div class="card mb-4">
                            <div class="card-body">
                                <label class="form-label">Observation</label>
                                <textarea name="observation" class="form-control" rows="2"
                                    placeholder="Optionnel">{{ old('observation') }}</textarea>
                            </div>
                        </div>

                        <div class="alert alert-info d-flex align-items-center gap-2">
                            <i data-feather="info" style="flex-shrink:0;"></i>
                            <span>Seule la <strong>quantité vendue</strong> (Quantité − Retour cuve) sera déduite du stock de la cuve.</span>
                        </div>

                        <div class="mt-2 mb-5">
                            <button type="submit" class="btn btn-1">
                                <i data-feather="save" class="me-2"></i>Enregistrer la Vente
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ─── Colonne latérale ─── --}}
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Session Active</div>
                        <div class="card-body">
                            @if ($sessionActive)
                                <p><strong>Session N°{{ $sessionActive->numero_session }}</strong></p>
                                <p class="text-muted small">Ouverte le {{ $sessionActive->date_debut->format('d/m/Y à H:i') }}
                                    par {{ $sessionActive->user->prenom }} {{ $sessionActive->user->nom }}.</p>
                                @if (count($indexDepartMap))
                                    <p class="text-success small mb-0">
                                        <i data-feather="check-circle" style="width:14px;height:14px;"></i>
                                        {{ count($indexDepartMap) }} index de départ disponible(s).
                                    </p>
                                @else
                                    <p class="text-warning small mb-0">
                                        <i data-feather="alert-triangle" style="width:14px;height:14px;"></i>
                                        Aucun index de départ saisi pour cette session.
                                    </p>
                                @endif
                                <a href="{{ route('session_ventes.show', $sessionActive) }}"
                                    class="btn btn-sm btn-outline-primary mt-2">Voir la session</a>
                            @else
                                <p class="text-warning">Aucune session active.</p>
                                <a href="{{ route('session_ventes.create') }}"
                                    class="btn btn-sm btn-outline-success">Ouvrir une session</a>
                            @endif
                        </div>
                    </div>

                    {{-- Récapitulatif dynamique --}}
                    <div class="card" id="recap_card" style="display:none;">
                        <div class="card-header bg-primary text-white">Récapitulatif</div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><td class="text-muted">Carburant</td><td id="r_carburant" class="fw-bold text-end">--</td></tr>
                                <tr><td class="text-muted">Index départ</td><td id="r_index_dep" class="fw-bold text-end">--</td></tr>
                                <tr><td class="text-muted">Index final</td><td id="r_index_fin" class="fw-bold text-end">--</td></tr>
                                <tr><td class="text-muted">Qté compteur</td><td id="r_qte" class="fw-bold text-end">--</td></tr>
                                <tr><td class="text-muted">Retour cuve</td><td id="r_retour" class="fw-bold text-end">--</td></tr>
                                <tr class="table-success"><td class="fw-bold">Qté vendue</td><td id="r_vendue" class="fw-bold text-end text-success">--</td></tr>
                                <tr><td class="text-muted">Prix unitaire</td><td id="r_prix" class="fw-bold text-end">--</td></tr>
                                <tr class="table-success"><td class="fw-bold">Montant Réel</td><td id="r_montant" class="fw-bold text-end text-success">--</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const pistoletSel   = document.getElementById('pistolet_id');
        const indexDepartIn = document.getElementById('index_depart');
        const indexFinalIn  = document.getElementById('index_final');
        const quantiteIn    = document.getElementById('quantite');
        const retourCuveIn  = document.getElementById('retour_cuve');
        const qtVendueIn    = document.getElementById('quantite_vendue');
        const prixIn        = document.getElementById('prix_unitaire');
        const carburantNom  = document.getElementById('carburant_nom');
        const mthIn         = document.getElementById('montant_theorique');
        const mrIn          = document.getElementById('montant_reel');
        const clientField   = document.getElementById('client_field');
        const recapCard     = document.getElementById('recap_card');

        const fmt = v => new Intl.NumberFormat('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(v);

        function recalc() {
            const idep = parseFloat(indexDepartIn.value) || 0;
            const ifin = parseFloat(indexFinalIn.value)  || 0;
            const prix = parseFloat(prixIn.value)         || 0;

            const qteCompteur = Math.max(0, idep - ifin);
            const retour      = parseFloat(retourCuveIn.value) || 0;
            const qteVendue   = Math.max(0, qteCompteur - retour);

            quantiteIn.value   = qteCompteur.toFixed(2);
            qtVendueIn.value   = qteVendue.toFixed(2);
            mthIn.value        = fmt(qteCompteur * prix) + ' FCFA';
            mrIn.value         = fmt(qteVendue * prix) + ' FCFA';

            // Récapitulatif
            document.getElementById('r_carburant').textContent  = carburantNom.value || '--';
            document.getElementById('r_index_dep').textContent  = idep ? fmt(idep) : '--';
            document.getElementById('r_index_fin').textContent  = ifin ? fmt(ifin) : '--';
            document.getElementById('r_qte').textContent        = fmt(qteCompteur) + ' L';
            document.getElementById('r_retour').textContent     = fmt(retour) + ' L';
            document.getElementById('r_vendue').textContent     = fmt(qteVendue) + ' L';
            document.getElementById('r_prix').textContent       = fmt(prix) + ' FCFA';
            document.getElementById('r_montant').textContent    = fmt(qteVendue * prix) + ' FCFA';

            if (pistoletSel.value) recapCard.style.display = 'block';
        }

        pistoletSel.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const prix       = opt.getAttribute('data-prix')        || '';
            const stock      = opt.getAttribute('data-stock')       || '--';
            const carburant  = opt.getAttribute('data-carburant')   || '';
            const indexDep   = opt.getAttribute('data-index-depart') || '';

            prixIn.value       = prix;
            carburantNom.value = carburant;

            if (indexDep !== '') {
                indexDepartIn.value = indexDep;
                document.getElementById('index_depart_info').textContent =
                    'Index de départ récupéré depuis l\'ouverture de session.';
            } else {
                indexDepartIn.value = '';
                document.getElementById('index_depart_info').textContent =
                    'Aucun index de départ saisi pour cette pompe dans la session.';
            }

            indexFinalIn.value = '';
            recalc();
        });

        prixIn.addEventListener('change', recalc);

        indexFinalIn.addEventListener('input', recalc);
        indexFinalIn.addEventListener('change', recalc);
        retourCuveIn.addEventListener('input', recalc);
        retourCuveIn.addEventListener('change', recalc);

        // Mode paiement → afficher champ client
        document.querySelectorAll('input[name="mode_paiement"]').forEach(r => {
            r.addEventListener('change', function () {
                clientField.style.display = (this.value === 'credit') ? 'block' : 'none';
            });
        });

        const creditRadio = document.getElementById('mp_credit');
        if (creditRadio && creditRadio.checked) clientField.style.display = 'block';

        // Init si old() rempli
        if (pistoletSel.value) {
            pistoletSel.dispatchEvent(new Event('change'));
            if ('{{ old('index_final') }}') {
                indexFinalIn.value = '{{ old('index_final') }}';
            }
            recalc();
        }
    });
    </script>
@endsection
