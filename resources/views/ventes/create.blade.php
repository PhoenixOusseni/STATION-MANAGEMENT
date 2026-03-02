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
                                <small>Enregistrez une nouvelle vente de carburant en sélectionnant le pistolet, la quantité
                                    vendue et le mode de paiement.</small>
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
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light text-dark">Informations de la Vente</div>
                        <div class="card-body">
                            <form action="{{ route('ventes.store') }}" method="POST">
                                @csrf
                                @if ($sessionActive)
                                    <input type="hidden" name="session_vente_id" value="{{ $sessionActive->id }}">
                                @endif

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
                                                {{ old('pistolet_id') == $pistolet->id ? 'selected' : '' }}>
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

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Quantité (Litres) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="quantite" id="quantite"
                                            class="form-control @error('quantite') is-invalid @enderror"
                                            value="{{ old('quantite') }}" min="0" step="0.01" required>
                                        @error('quantite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Stock disponible: <span id="stock_disponible">--</span>
                                            L</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Prix Unitaire (FCFA)</label>
                                        <input type="number" name="prix_unitaire" id="prix_unitaire" class="form-control"
                                            value="{{ old('prix_unitaire') }}" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Montant Total</label>
                                        <input type="text" id="montant_total" class="form-control bg-light" readonly>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                                    <div class="@error('mode_paiement') is-invalid @enderror">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mode_paiement"
                                                id="especes" value="especes"
                                                {{ old('mode_paiement', 'especes') == 'especes' ? 'checked' : '' }}
                                                required>
                                            <label class="form-check-label" for="especes">
                                                <i data-feather="dollar-sign"></i> Espèces
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mode_paiement"
                                                id="carte" value="carte"
                                                {{ old('mode_paiement') == 'carte' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="carte">
                                                <i data-feather="credit-card"></i> Carte Bancaire
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mode_paiement"
                                                id="mobile_money" value="mobile_money"
                                                {{ old('mode_paiement') == 'mobile_money' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mobile_money">
                                                <i data-feather="smartphone"></i> Mobile Money
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mode_paiement"
                                                id="credit" value="credit"
                                                {{ old('mode_paiement') == 'credit' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="credit">
                                                <i data-feather="clock"></i> À Crédit
                                            </label>
                                        </div>
                                    </div>
                                    @error('mode_paiement')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3" id="client_field" style="display: none;">
                                    <label class="form-label">Nom du Client (pour crédit)</label>
                                    <input type="text" name="client"
                                        class="form-control @error('client') is-invalid @enderror"
                                        value="{{ old('client') }}">
                                    @error('client')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
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
                                        @error('numero_ticket')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="alert alert-warning">
                                    <i data-feather="alert-triangle"></i>
                                    Le stock de la cuve sera automatiquement réduit après l'enregistrement de cette vente.
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-1">
                                        <i data-feather="save" class="me-2"></i>Enregistrer la Vente
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-light text-dark">Information de la session</div>
                        <div class="card-body">
                            @if ($sessionActive)
                                <p><strong>Session N°{{ $sessionActive->numero_session }}</strong></p>
                                <p>Ouverte le {{ $sessionActive->date_debut->format('d/m/Y à H:i') }} par
                                    {{ $sessionActive->user->prenom }} {{ $sessionActive->user->nom }}.</p>
                                <p><a href="{{ route('session_ventes.show', $sessionActive) }}"
                                        class="btn btn-sm btn-outline-primary">Voir la session</a></p>
                            @else
                                <p>Aucune session active. Cette vente ne sera pas rattachée à une session.</p>
                                <p><a href="{{ route('session_ventes.create') }}"
                                        class="btn btn-sm btn-outline-success">Ouvrir une session</a></p>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-light text-dark">Aide</div>
                        <div class="card-body">
                            <p>Pour enregistrer une vente, sélectionnez d'abord le pistolet utilisé. Le prix unitaire et le
                                stock disponible seront affichés automatiquement.</p>
                            <p>Entrez la quantité vendue en litres, puis choisissez le mode de paiement. Si vous
                                sélectionnez "À Crédit", n'oubliez pas de renseigner le nom du client.</p>
                            <p>La date de vente est pré-remplie avec la date et l'heure actuelles, mais vous pouvez la
                                modifier si nécessaire.</p>
                            <p>Après avoir enregistré la vente, le stock de la cuve associée sera automatiquement mis à
                                jour.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pistoletSelect = document.getElementById('pistolet_id');
            const quantiteInput = document.getElementById('quantite');
            const prixUnitaireInput = document.getElementById('prix_unitaire');
            const montantTotalInput = document.getElementById('montant_total');
            const stockDisponibleSpan = document.getElementById('stock_disponible');
            const modePaiementRadios = document.querySelectorAll('input[name="mode_paiement"]');
            const clientField = document.getElementById('client_field');

            // Mise à jour du prix et stock quand on sélectionne un pistolet
            pistoletSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const prix = selectedOption.getAttribute('data-prix');
                const stock = selectedOption.getAttribute('data-stock');

                if (prix) {
                    prixUnitaireInput.value = prix;
                    stockDisponibleSpan.textContent = new Intl.NumberFormat('fr-FR').format(stock);
                    calculerMontant();
                }
            });

            // Calcul du montant total
            function calculerMontant() {
                const quantite = parseFloat(quantiteInput.value) || 0;
                const prixUnitaire = parseFloat(prixUnitaireInput.value) || 0;
                const montant = quantite * prixUnitaire;
                montantTotalInput.value = new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
            }

            quantiteInput.addEventListener('input', calculerMontant);

            // Afficher le champ client si crédit
            modePaiementRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'credit') {
                        clientField.style.display = 'block';
                    } else {
                        clientField.style.display = 'none';
                    }
                });
            });

            // Vérifier à l'initialisation
            const creditRadio = document.getElementById('credit');
            if (creditRadio && creditRadio.checked) {
                clientField.style.display = 'block';
            }
        });
    </script>
@endsection
