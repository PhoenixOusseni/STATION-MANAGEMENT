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
                                Modifier la Vente {{ $vente->numero_vente }}
                            </h1>
                            <p>
                                <small>Mettez à jour les informations de la vente de carburant.</small>
                            </p>
                        </div>
                        <div class="col-12 col-md-auto mt-4">
                            <a href="{{ route('ventes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>&nbsp; Retour à la Liste
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
                        <div class="card-header bg-light text-dark">Informations de la Vente</div>
                        <div class="card-body">
                            <form action="{{ route('ventes.update', $vente) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- N° vente (lecture seule) --}}
                                <div class="mb-3">
                                    <label class="form-label">N° de Vente</label>
                                    <input type="text" class="form-control" value="{{ $vente->numero_vente }}" readonly>
                                </div>

                                {{-- Pistolet --}}
                                <div class="mb-3">
                                    <label class="form-label">Pistolet <span class="text-danger">*</span></label>
                                    <select name="pistolet_id" id="pistolet_id"
                                        class="form-select @error('pistolet_id') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner un pistolet --</option>
                                        @foreach ($pistolets as $pistolet)
                                            <option value="{{ $pistolet->id }}"
                                                data-carburant="{{ $pistolet->pompe->cuve->carburant->nom }}"
                                                data-prix="{{ $pistolet->pompe->cuve->carburant->prix_unitaire }}"
                                                data-stock="{{ $pistolet->pompe->cuve->stock_actuel }}"
                                                {{ old('pistolet_id', $vente->pistolet_id) == $pistolet->id ? 'selected' : '' }}>
                                                {{ $pistolet->numero }} - Pompe {{ $pistolet->pompe->numero }} -
                                                {{ $pistolet->pompe->cuve->carburant->nom }}
                                                (Stock: {{ number_format($pistolet->pompe->cuve->stock_actuel, 0) }} L)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pistolet_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Quantité / Prix / Montant --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Quantité (Litres) <span class="text-danger">*</span></label>
                                        <input type="number" name="quantite" id="quantite"
                                            class="form-control @error('quantite') is-invalid @enderror"
                                            value="{{ old('quantite', $vente->quantite) }}" min="0" step="0.01" required>
                                        @error('quantite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Stock disponible : <span id="stock_disponible">--</span> L</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Prix Unitaire (FCFA) <span class="text-danger">*</span></label>
                                        <input type="number" name="prix_unitaire" id="prix_unitaire"
                                            class="form-control @error('prix_unitaire') is-invalid @enderror"
                                            value="{{ old('prix_unitaire', $vente->prix_unitaire) }}"
                                            min="0" step="0.01" required>
                                        @error('prix_unitaire')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Montant Total</label>
                                        <input type="text" id="montant_total" class="form-control bg-light" readonly>
                                    </div>
                                </div>

                                {{-- Mode de paiement --}}
                                <div class="mb-3">
                                    <label class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                                    <div class="@error('mode_paiement') is-invalid @enderror">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mode_paiement"
                                                id="especes" value="especes"
                                                {{ old('mode_paiement', $vente->mode_paiement) == 'especes' ? 'checked' : '' }}
                                                required>
                                            <label class="form-check-label" for="especes">
                                                <i data-feather="dollar-sign"></i> Espèces
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mode_paiement"
                                                id="carte" value="carte"
                                                {{ old('mode_paiement', $vente->mode_paiement) == 'carte' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="carte">
                                                <i data-feather="credit-card"></i> Carte Bancaire
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mode_paiement"
                                                id="mobile_money" value="mobile_money"
                                                {{ old('mode_paiement', $vente->mode_paiement) == 'mobile_money' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mobile_money">
                                                <i data-feather="smartphone"></i> Mobile Money
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mode_paiement"
                                                id="credit" value="credit"
                                                {{ old('mode_paiement', $vente->mode_paiement) == 'credit' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="credit">
                                                <i data-feather="clock"></i> À Crédit
                                            </label>
                                        </div>
                                    </div>
                                    @error('mode_paiement')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Champ client (crédit) --}}
                                <div class="mb-3" id="client_field"
                                    style="{{ old('mode_paiement', $vente->mode_paiement) == 'credit' ? '' : 'display:none;' }}">
                                    <label class="form-label">Nom du Client (pour crédit)</label>
                                    <input type="text" name="client"
                                        class="form-control @error('client') is-invalid @enderror"
                                        value="{{ old('client', $vente->client) }}">
                                    @error('client')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Date / N° Ticket --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Date de Vente <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="date_vente"
                                            class="form-control @error('date_vente') is-invalid @enderror"
                                            value="{{ old('date_vente', $vente->date_vente->format('Y-m-d\TH:i')) }}" required>
                                        @error('date_vente')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">N° de Ticket</label>
                                        <input type="text" name="numero_ticket"
                                            class="form-control @error('numero_ticket') is-invalid @enderror"
                                            value="{{ old('numero_ticket', $vente->numero_ticket) }}">
                                        @error('numero_ticket')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Observation --}}
                                <div class="mb-3">
                                    <label class="form-label">Observation</label>
                                    <textarea name="observation"
                                        class="form-control @error('observation') is-invalid @enderror"
                                        rows="3">{{ old('observation', $vente->observation) }}</textarea>
                                    @error('observation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-warning">
                                    <i data-feather="alert-triangle"></i>
                                    La modification ajustera automatiquement le stock de la cuve selon l'écart de quantité.
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-1">
                                        <i data-feather="save" class="me-2"></i>Mettre à jour la Vente
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Colonne latérale --}}
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light text-dark">Résumé actuel</div>
                        <div class="card-body">
                            <p><strong>Pistolet :</strong> {{ $vente->pistolet->numero }}</p>
                            <p><strong>Pompe :</strong> {{ $vente->pistolet->pompe->numero }}</p>
                            <p><strong>Carburant :</strong> {{ $vente->pistolet->pompe->cuve->carburant->nom }}</p>
                            <p><strong>Quantité :</strong> {{ number_format($vente->quantite, 2) }} L</p>
                            <p><strong>Montant :</strong> {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light text-dark">Aide</div>
                        <div class="card-body">
                            <p>
                                <i data-feather="info" class="me-2"></i>
                                Si vous changez le pistolet, le stock de l'ancienne cuve sera restauré et celui de la
                                nouvelle cuve sera décrémenté.
                            </p>
                            <p>
                                <i data-feather="check-circle" class="me-2"></i>
                                Le montant total est recalculé automatiquement selon la quantité et le prix unitaire.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pistoletSelect    = document.getElementById('pistolet_id');
            const quantiteInput     = document.getElementById('quantite');
            const prixUnitaireInput = document.getElementById('prix_unitaire');
            const montantTotalInput = document.getElementById('montant_total');
            const stockSpan         = document.getElementById('stock_disponible');
            const modePaiementRadios = document.querySelectorAll('input[name="mode_paiement"]');
            const clientField       = document.getElementById('client_field');

            // ── Calcul du montant total ──────────────────────────────────
            function calculerMontant() {
                const q = parseFloat(quantiteInput.value)     || 0;
                const p = parseFloat(prixUnitaireInput.value) || 0;
                montantTotalInput.value = new Intl.NumberFormat('fr-FR').format(q * p) + ' FCFA';
            }

            // ── Mise à jour au changement de pistolet ─────────────────────
            pistoletSelect.addEventListener('change', function () {
                const opt   = this.options[this.selectedIndex];
                const prix  = opt.getAttribute('data-prix');
                const stock = opt.getAttribute('data-stock');

                if (prix) {
                    prixUnitaireInput.value       = prix;
                    stockSpan.textContent         = new Intl.NumberFormat('fr-FR').format(stock);
                } else {
                    prixUnitaireInput.value = '';
                    stockSpan.textContent   = '--';
                }
                calculerMontant();
            });

            quantiteInput.addEventListener('input', calculerMontant);
            prixUnitaireInput.addEventListener('input', calculerMontant);

            // ── Affichage du champ client ─────────────────────────────────
            modePaiementRadios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    clientField.style.display = this.value === 'credit' ? 'block' : 'none';
                });
            });

            // ── Initialisation au chargement ──────────────────────────────
            (function init() {
                // Stock du pistolet déjà sélectionné
                const opt   = pistoletSelect.options[pistoletSelect.selectedIndex];
                const stock = opt ? opt.getAttribute('data-stock') : null;
                if (stock !== null) {
                    stockSpan.textContent = new Intl.NumberFormat('fr-FR').format(stock);
                }
                calculerMontant();

                // Champ client si mode crédit actif
                const creditRadio = document.getElementById('credit');
                if (creditRadio && creditRadio.checked) {
                    clientField.style.display = 'block';
                }
            })();
        });
    </script>
@endsection
