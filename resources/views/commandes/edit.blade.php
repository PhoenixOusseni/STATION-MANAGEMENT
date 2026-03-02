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
                                Modifier la Commande
                            </h1>
                            <p class="text-white">
                                <small>Utilisez le formulaire ci-dessous pour modifier les détails de la commande
                                    <strong>{{ $commande->numero_commande }}</strong>.</small>
                            </p>
                        </div>
                        <div class="col-auto mt-4">
                            <a href="{{ route('commandes.show', $commande) }}" class="btn btn-light btn-sm">
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
                        <div class="card-header bg-light text-dark">
                            Informations de la Commande - {{ $commande->numero_commande }}
                            <span class="badge bg-{{ $commande->statut == 'en_attente' ? 'warning' : 'info' }} float-end">
                                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('commandes.update', $commande) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="small">Station <span class="text-danger">*</span></label>
                                        <input type="hidden" name="station_id" value="{{ auth()->user()->station_id }}">
                                        <input type="text" class="form-control"
                                            value="{{ auth()->user()->station->nom ?? 'N/A' }}" disabled>
                                        @error('station_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="small">Carburant <span class="text-danger">*</span></label>
                                        <select name="carburant_id"
                                            class="form-select @error('carburant_id') is-invalid @enderror" required>
                                            <option value="">-- Sélectionner --</option>
                                            @foreach ($carburants as $carburant)
                                                <option value="{{ $carburant->id }}"
                                                    data-prix="{{ $carburant->prix_unitaire }}"
                                                    {{ old('carburant_id', $commande->carburant_id) == $carburant->id ? 'selected' : '' }}>
                                                    {{ $carburant->nom }} -
                                                    {{ number_format($carburant->prix_unitaire, 0) }} FCFA/L
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('carburant_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="small">Quantité (Litres) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="quantite" id="quantite"
                                            class="form-control @error('quantite') is-invalid @enderror"
                                            value="{{ old('quantite', $commande->quantite) }}" min="0"
                                            step="0.01" required>
                                        @error('quantite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small">Prix Unitaire (FCFA) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="prix_unitaire" id="prix_unitaire"
                                            class="form-control @error('prix_unitaire') is-invalid @enderror"
                                            value="{{ old('prix_unitaire', $commande->prix_unitaire) }}" min="0"
                                            step="0.01" required>
                                        @error('prix_unitaire')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small">Montant Total</label>
                                        <input type="text" id="montant_total" class="form-control" readonly
                                            value="{{ number_format($commande->montant_total, 0) }} FCFA">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="small">Fournisseur <span class="text-danger">*</span></label>
                                    <input type="text" name="fournisseur"
                                        class="form-control @error('fournisseur') is-invalid @enderror"
                                        value="{{ old('fournisseur', $commande->fournisseur) }}" required>
                                    @error('fournisseur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="small">Date de Commande <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="date_commande"
                                            class="form-control @error('date_commande') is-invalid @enderror"
                                            value="{{ old('date_commande', $commande->date_commande->format('Y-m-d')) }}"
                                            required>
                                        @error('date_commande')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small">Date de Livraison Prévue</label>
                                        <input type="date" name="date_livraison_prevue"
                                            class="form-control @error('date_livraison_prevue') is-invalid @enderror"
                                            value="{{ old('date_livraison_prevue', $commande->date_livraison_prevue?->format('Y-m-d')) }}">
                                        @error('date_livraison_prevue')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small">Statut <span class="text-danger">*</span></label>
                                        <select name="statut" class="form-select @error('statut') is-invalid @enderror"
                                            required>
                                            <option value="en_attente"
                                                {{ old('statut', $commande->statut) == 'en_attente' ? 'selected' : '' }}>En
                                                Attente</option>
                                            <option value="validee"
                                                {{ old('statut', $commande->statut) == 'validee' ? 'selected' : '' }}>
                                                Validée</option>
                                            @if ($commande->entree)
                                                <option value="livree"
                                                    {{ old('statut', $commande->statut) == 'livree' ? 'selected' : '' }}>
                                                    Livrée</option>
                                            @endif
                                            <option value="annulee"
                                                {{ old('statut', $commande->statut) == 'annulee' ? 'selected' : '' }}>
                                                Annulée</option>
                                        </select>
                                        @error('statut')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @if ($commande->entree)
                                    <div class="alert alert-info">
                                        <i data-feather="info"></i>
                                        Cette commande a déjà été réceptionnée le
                                        {{ $commande->entree->date_entree->format('d/m/Y à H:i') }}.
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-1">
                                        <i data-feather="save" class="me-2"></i>Enregistrer les Modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-light text-dark">
                            Détails de la Commande
                        </div>
                        <div class="card-body">
                            <p><strong>Numéro de Commande:</strong> {{ $commande->numero_commande }}</p>
                            <p><strong>Station:</strong> {{ $commande->station->nom ?? 'N/A' }}</p>
                            <p><strong>Carburant:</strong> {{ $commande->carburant->nom ?? 'N/A' }}</p>
                            <p><strong>Quantité:</strong> {{ number_format($commande->quantite, 2) }} L</p>
                            <p><strong>Prix Unitaire:</strong> {{ number_format($commande->prix_unitaire, 0) }} FCFA/L</p>
                            <p><strong>Montant Total:</strong> {{ number_format($commande->montant_total, 0) }} FCFA</p>
                            <p><strong>Fournisseur:</strong> {{ $commande->fournisseur }}</p>
                            <p><strong>Date de Commande:</strong> {{ $commande->date_commande->format('d/m/Y') }}</p>
                            @if ($commande->date_livraison_prevue)
                                <p><strong>Date de Livraison Prévue:</strong>
                                    {{ $commande->date_livraison_prevue->format('d/m/Y') }}</p>
                            @endif
                            <p><strong>Statut:</strong>
                                <span
                                    class="badge bg-{{ $commande->statut == 'en_attente' ? 'warning' : ($commande->statut == 'validee' ? 'info' : ($commande->statut == 'livree' ? 'success' : 'danger')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carburantSelect = document.querySelector('select[name="carburant_id"]');
            const quantiteInput = document.getElementById('quantite');
            const prixUnitaireInput = document.getElementById('prix_unitaire');
            const montantTotalInput = document.getElementById('montant_total');

            // Remplir le prix unitaire quand on sélectionne un carburant
            carburantSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const prix = selectedOption.getAttribute('data-prix');
                if (prix) {
                    prixUnitaireInput.value = prix;
                    calculerMontant();
                }
            });

            // Calculer le montant total
            function calculerMontant() {
                const quantite = parseFloat(quantiteInput.value) || 0;
                const prixUnitaire = parseFloat(prixUnitaireInput.value) || 0;
                const montant = quantite * prixUnitaire;
                montantTotalInput.value = new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
            }

            quantiteInput.addEventListener('input', calculerMontant);
            prixUnitaireInput.addEventListener('input', calculerMontant);
        });
    </script>
@endsection
