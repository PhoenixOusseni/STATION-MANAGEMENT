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
								Modifier l'Entrée {{ $entree->numero_entree }}
							</h1>
							<p>
								<small>Mettez à jour les informations de l'entrée de carburant.</small>
							</p>
						</div>
						<div class="col-12 col-md-auto mt-4">
							<a href="{{ route('entrees.index') }}" class="btn btn-light btn-sm">
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
						<div class="card-header bg-light text-dark">Informations de l'Entrée</div>
						<div class="card-body">
							<form action="{{ route('entrees.update', $entree) }}" method="POST">
								@csrf
								@method('PUT')

								<div class="mb-3">
									<label class="form-label">N° Entrée</label>
									<input type="text" class="form-control" value="{{ $entree->numero_entree }}" readonly>
								</div>

								<div class="mb-3">
									<label class="form-label">Cuve de Destination <span class="text-danger">*</span></label>
									<select name="cuve_id" id="cuve_id"
										class="form-select @error('cuve_id') is-invalid @enderror" required>
										<option value="">-- Sélectionner une cuve --</option>
										@foreach ($cuves as $cuve)
											<option value="{{ $cuve->id }}" data-carburant="{{ $cuve->carburant->nom }}"
												data-capacite="{{ $cuve->capacite_max }}"
												data-stock="{{ $cuve->stock_actuel }}"
												{{ old('cuve_id', $entree->cuve_id) == $cuve->id ? 'selected' : '' }}>
												{{ $cuve->nom }} - {{ $cuve->carburant->nom }}
												(Stock:
												{{ number_format($cuve->stock_actuel, 0) }}/{{ number_format($cuve->capacite_max, 0) }}
												L)
											</option>
										@endforeach
									</select>
									@error('cuve_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">Commande Associée (si applicable)</label>
									<select name="commande_id"
										class="form-select @error('commande_id') is-invalid @enderror">
										<option value="">-- Aucune commande --</option>
										@foreach ($commandes as $commande)
											<option value="{{ $commande->id }}"
												{{ old('commande_id', $entree->commande_id) == $commande->id ? 'selected' : '' }}>
												{{ $commande->numero_commande }} - {{ $commande->carburant->nom }}
												({{ number_format($commande->quantite, 0) }} L)
											</option>
										@endforeach
									</select>
									@error('commande_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								{{-- ===== JAUGEAGE AVANT DÉPOTAGE ===== --}}
								<div class="card mb-4 border-warning">
									<div class="card-header bg-warning text-dark fw-bold">
										<i data-feather="droplet" class="me-2"></i>
										Jaugeage Avant Dépotage <small class="fw-normal">(recommandé)</small>
									</div>
									<div class="card-body">
										<p class="small text-muted mb-3">
											Mesurez physiquement le niveau de la cuve <strong>avant</strong> de dépoter pour détecter les écarts.
										</p>
										<div class="row">
											<div class="col-md-6">
												<label class="form-label">Quantité mesurée dans la cuve (L)</label>
												<input type="number" name="quantite_jaugee_avant"
													class="form-control @error('quantite_jaugee_avant') is-invalid @enderror"
													value="{{ old('quantite_jaugee_avant', $entree->quantite_jaugee_avant) }}"
													min="0" step="0.01"
													placeholder="Laisser vide si pas de jaugeage">
												@error('quantite_jaugee_avant')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
											<div class="col-md-6">
												<label class="form-label">Observation jaugeage</label>
												<input type="text" name="observation_jaugeage"
													class="form-control @error('observation_jaugeage') is-invalid @enderror"
													value="{{ old('observation_jaugeage', $entree->observation_jaugeage) }}"
													placeholder="Ex: Ecart constaté, conditions mesure...">
												@error('observation_jaugeage')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-md-4">
										<label class="form-label">Quantité (Litres) <span class="text-danger">*</span></label>
										<input type="number" name="quantite" id="quantite"
											class="form-control @error('quantite') is-invalid @enderror"
											value="{{ old('quantite', $entree->quantite) }}" min="0" step="0.01" required>
										@error('quantite')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>

									<div class="col-md-4">
										<label class="form-label">Prix Unitaire (FCFA) <span
												class="text-danger">*</span></label>
										<input type="number" name="prix_unitaire" id="prix_unitaire"
											class="form-control @error('prix_unitaire') is-invalid @enderror"
											value="{{ old('prix_unitaire', $entree->prix_unitaire) }}" min="0"
											step="0.01" required>
										@error('prix_unitaire')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>

									<div class="col-md-4">
										<label class="form-label">Montant Total</label>
										<input type="text" id="montant_total" class="form-control" readonly>
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-md-6">
										<label class="form-label">Date d'Entrée <span class="text-danger">*</span></label>
										<input type="datetime-local" name="date_entree"
											class="form-control @error('date_entree') is-invalid @enderror"
											value="{{ old('date_entree', $entree->date_entree->format('Y-m-d\TH:i')) }}" required>
										@error('date_entree')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>

									<div class="col-md-6">
										<label class="form-label">N° Bon de Livraison</label>
										<input type="text" name="numero_bon_livraison"
											class="form-control @error('numero_bon_livraison') is-invalid @enderror"
											value="{{ old('numero_bon_livraison', $entree->numero_bon_livraison) }}">
										@error('numero_bon_livraison')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="mb-3">
									<label class="form-label">Observation</label>
									<textarea name="observation" class="form-control @error('observation') is-invalid @enderror" rows="3">{{ old('observation', $entree->observation) }}</textarea>
									@error('observation')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="alert alert-warning">
									<i data-feather="alert-triangle"></i>
									La modification ajustera automatiquement le stock de la cuve et l'état des commandes liées.
								</div>

								<div class="mt-4">
									<button type="submit" class="btn btn-1">
										<i data-feather="save" class="me-2"></i>Mettre à jour l'Entrée
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="col-lg-4">
					<div class="card mb-4">
						<div class="card-header bg-light text-dark">Résumé actuel</div>
						<div class="card-body">
							<p><strong>Cuve:</strong> {{ $entree->cuve->nom }}</p>
							<p><strong>Carburant:</strong> {{ $entree->cuve->carburant->nom }}</p>
							<p><strong>Quantité actuelle:</strong> {{ number_format($entree->quantite, 2) }} L</p>
							<p><strong>Montant actuel:</strong>
								{{ number_format($entree->montant_total, 0, ',', ' ') }} FCFA</p>
						</div>
					</div>

					<div class="card">
						<div class="card-header bg-light text-dark">Aide & Conseils</div>
						<div class="card-body">
							<p>
								<i data-feather="info" class="me-2"></i>
								Vérifiez les nouvelles valeurs avant validation pour éviter des écarts de stock.
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
		document.addEventListener('DOMContentLoaded', function() {
			const quantiteInput = document.getElementById('quantite');
			const prixUnitaireInput = document.getElementById('prix_unitaire');
			const montantTotalInput = document.getElementById('montant_total');

			function calculerMontant() {
				const quantite = parseFloat(quantiteInput.value) || 0;
				const prixUnitaire = parseFloat(prixUnitaireInput.value) || 0;
				const montant = quantite * prixUnitaire;
				montantTotalInput.value = new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
			}

			quantiteInput.addEventListener('input', calculerMontant);
			prixUnitaireInput.addEventListener('input', calculerMontant);
			calculerMontant();
		});
	</script>
@endsection
