<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Commande {{ $commande->numero_commande }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            color: #1a1a1a;
            background: #fff;
            padding: 20px;
        }

        /* ─── En-tête ─── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #1a1a1a;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .header-left .company-name {
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-left .company-info {
            color: #555;
            margin-top: 4px;
            line-height: 1.6;
        }

        .header-right {
            text-align: right;
        }

        .document-title {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1a1a1a;
        }

        .document-number {
            font-size: 15px;
            color: #444;
            margin-top: 4px;
        }

        .document-date {
            font-size: 12px;
            color: #666;
            margin-top: 6px;
        }

        /* ─── Séparateur de section ─── */
        .section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }

        /* ─── Grille d'informations ─── */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px 20px;
            margin-bottom: 28px;
        }

        .info-grid.cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .info-item label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            margin-bottom: 2px;
        }

        .info-item .value {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .info-item .value.large {
            font-size: 16px;
        }

        .info-item .value.muted {
            font-weight: 400;
            color: #555;
        }

        /* ─── Tableau financier ─── */
        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
        }

        .financial-table thead tr {
            background-color: #1a1a1a;
            color: #fff;
        }

        .financial-table thead th {
            padding: 9px 12px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .financial-table thead th:last-child {
            text-align: right;
        }

        .financial-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .financial-table tbody td {
            padding: 9px 12px;
            font-size: 13px;
        }

        .financial-table tbody td:last-child {
            text-align: right;
        }

        /* ─── Total ─── */
        .totals-block {
            margin-left: auto;
            width: 300px;
            margin-bottom: 28px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }

        .total-row.grand-total {
            border-top: 2px solid #1a1a1a;
            border-bottom: none;
            font-weight: 700;
            font-size: 15px;
            padding-top: 10px;
        }

        .total-row label {
            color: #555;
        }

        .total-row.grand-total label,
        .total-row.grand-total .amount {
            color: #1a1a1a;
        }

        /* ─── Statut badge (texte seul pour impression) ─── */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #1a1a1a;
        }

        .status-en_attente  { border-color: #f59e0b; color: #92400e; }
        .status-validee     { border-color: #3b82f6; color: #1e40af; }
        .status-livree      { border-color: #22c55e; color: #166534; }
        .status-annulee     { border-color: #ef4444; color: #991b1b; }

        /* ─── Pied de page ─── */
        .footer {
            margin-top: 32px;
            border-top: 1px solid #ddd;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #888;
        }

        /* ─── Signatures ─── */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            gap: 20px;
        }

        .signature-box {
            flex: 1;
            border-top: 1px solid #aaa;
            padding-top: 8px;
            font-size: 11px;
            text-align: center;
            color: #555;
        }

        /* ─── Impression ─── */
        .no-print {
            margin-bottom: 20px;
            text-align: right;
        }

        .btn-print {
            background: #1a1a1a;
            color: #fff;
            border: none;
            padding: 8px 20px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #333;
        }

        @media print {
            .no-print { display: none; }
            body { padding: 10px; }
        }
    </style>
</head>
<body>

    {{-- Bouton impression (masqué à l'impression) --}}
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Imprimer</button>
        <button class="btn-print" style="background:#555; margin-left:8px;" onclick="window.close()">✕ Fermer</button>
    </div>

    {{-- En-tête --}}
    <div class="header">
        <div class="header-left">
            <div class="company-name">{{ $commande->station->nom }}</div>
            <div class="company-info">
                Station-Service<br>
                Système de Gestion de Station
            </div>
        </div>
        <div class="header-right">
            <div class="document-title">Bon de Commande</div>
            <div class="document-number">{{ $commande->numero_commande }}</div>
            <div class="document-date">Émis le {{ $commande->created_at->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    {{-- Statut --}}
    <div style="margin-bottom: 20px;">
        <span class="status-badge status-{{ $commande->statut }}">
            @switch($commande->statut)
                @case('en_attente') En Attente @break
                @case('validee')   Validée    @break
                @case('livree')    Livrée     @break
                @case('annulee')   Annulée    @break
            @endswitch
        </span>
    </div>

    {{-- Informations générales --}}
    <div class="section-title">Informations Générales</div>
    <div class="info-grid cols-2">
        <div class="info-item">
            <label>Station</label>
            <div class="value">{{ $commande->station->nom }}</div>
        </div>
        <div class="info-item">
            <label>Passée par</label>
            <div class="value muted">{{ $commande->user->prenom }} {{ $commande->user->nom }}</div>
        </div>
        <div class="info-item">
            <label>Date de Commande</label>
            <div class="value">{{ $commande->date_commande->format('d/m/Y') }}</div>
        </div>
        <div class="info-item">
            <label>Date de Livraison Prévue</label>
            <div class="value">{{ $commande->date_livraison_prevue ? $commande->date_livraison_prevue->format('d/m/Y') : 'Non définie' }}</div>
        </div>
    </div>

    {{-- Fournisseur --}}
    <div class="section-title">Fournisseur</div>
    <div class="info-grid cols-2" style="margin-bottom: 28px;">
        <div class="info-item">
            <label>Nom du Fournisseur</label>
            <div class="value">{{ $commande->fournisseur }}</div>
        </div>
    </div>

    {{-- Détail de la Commande --}}
    <div class="section-title">Détail de la Commande</div>
    <table class="financial-table">
        <thead>
            <tr>
                <th>Désignation (Carburant)</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Montant Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $commande->carburant->nom }} ({{ $commande->carburant->code ?? '' }})</td>
                <td>{{ number_format($commande->quantite, 2) }} L</td>
                <td>{{ number_format($commande->prix_unitaire, 0) }} FCFA</td>
                <td>{{ number_format($commande->montant_total, 0) }} FCFA</td>
            </tr>
        </tbody>
    </table>

    {{-- Total --}}
    <div class="totals-block">
        <div class="total-row grand-total">
            <label>Montant Total</label>
            <span class="amount">{{ number_format($commande->montant_total, 0) }} FCFA</span>
        </div>
    </div>

    {{-- Réception liée --}}
    @if($commande->entree)
    <div class="section-title">Réception / Entrée Liée</div>
    <div class="info-grid">
        <div class="info-item">
            <label>N° Entrée</label>
            <div class="value">{{ $commande->entree->numero_entree }}</div>
        </div>
        <div class="info-item">
            <label>Date de Réception</label>
            <div class="value">{{ $commande->entree->date_entree->format('d/m/Y H:i') }}</div>
        </div>
        <div class="info-item">
            <label>Quantité Reçue</label>
            <div class="value large">{{ number_format($commande->entree->quantite, 2) }} L</div>
        </div>
        <div class="info-item">
            <label>Cuve</label>
            <div class="value">{{ $commande->entree->cuve->nom }}</div>
        </div>
    </div>
    @endif

    {{-- Signatures --}}
    <div class="signatures">
        <div class="signature-box">Établi par<br><br><br>{{ $commande->user->prenom }} {{ $commande->user->nom }}</div>
        <div class="signature-box">Validé par<br><br><br>____________________</div>
        <div class="signature-box">Fournisseur<br><br><br>____________________</div>
    </div>

    {{-- Pied de page --}}
    <div class="footer">
        <span>{{ $commande->numero_commande }} — {{ $commande->station->nom }}</span>
        <span>Imprimé le {{ now()->format('d/m/Y à H:i') }}</span>
    </div>

</body>
</html>
