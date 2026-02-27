<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon d'Entrée {{ $entree->numero_entree }}</title>
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
            font-weight: 600;
        }

        .financial-table tfoot tr {
            background-color: #f3f3f3;
        }

        .financial-table tfoot td {
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 700;
        }

        .financial-table tfoot td:last-child {
            text-align: right;
            font-size: 16px;
        }

        /* ─── Observation ─── */
        .observation-box {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 12px;
            background: #fafafa;
            margin-bottom: 28px;
            min-height: 50px;
            font-size: 12px;
            color: #444;
        }

        /* ─── Signatures ─── */
        .signatures {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 40px;
            margin-bottom: 28px;
        }

        .signature-block {
            text-align: center;
        }

        .signature-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 40px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin: 0 20px;
            padding-top: 6px;
            font-size: 11px;
            color: #777;
        }

        /* ─── Pied de page ─── */
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        /* ─── Boutons (masqués à l'impression) ─── */
        .print-actions {
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-print {
            background: #1a1a1a;
            color: #fff;
            border: none;
            padding: 10px 28px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 6px;
        }

        .btn-back {
            background: #f3f3f3;
            color: #1a1a1a;
            border: 1px solid #ccc;
            padding: 10px 28px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 6px;
            text-decoration: none;
        }

        @media print {
            body {
                padding: 20px 20px 20px 20px;
            }

            .print-actions {
                display: none;
            }

            @page {
                size: A4;
                margin: 15px 15px 15px 15px;
            }
        }
    </style>
</head>
<body>

    {{-- ─── Boutons d'action ─── --}}
    <div class="print-actions">
        <a href="{{ route('entrees.show', $entree) }}" class="btn-back">&#8592; Retour</a>
        <button class="btn-print" onclick="window.print()">&#128438; Imprimer</button>
    </div>

    {{-- ─── En-tête du document ─── --}}
    <div class="header">
        <div class="header-left">
            <div class="company-name">{{ $station->nom ?? 'Station Service' }}</div>
            <div class="company-info">
                @if($station->adresse ?? false){{ $station->adresse }}<br>@endif
                @if($station->ville ?? false){{ $station->ville }}<br>@endif
                @if($station->telephone ?? false)Tél : {{ $station->telephone }}<br>@endif
                @if($station->email ?? false)Email : {{ $station->email }}@endif
            </div>
        </div>
        <div class="header-right">
            <div class="document-title">Bon d'Entrée</div>
            <div class="document-number">{{ $entree->numero_entree }}</div>
            <div class="document-date">
                Date d'entrée : {{ $entree->date_entree->format('d/m/Y à H:i') }}
            </div>
            <div class="document-date">
                Imprimé le : {{ now()->format('d/m/Y à H:i') }}
            </div>
        </div>
    </div>

    {{-- ─── Informations générales ─── --}}
    <div class="section-title">Informations Générales</div>
    <div class="info-grid">
        <div class="info-item">
            <label>N° d'Entrée</label>
            <div class="value">{{ $entree->numero_entree }}</div>
        </div>
        <div class="info-item">
            <label>Cuve de Destination</label>
            <div class="value">{{ $entree->cuve->nom }}</div>
        </div>
        <div class="info-item">
            <label>Carburant</label>
            <div class="value">{{ $entree->cuve->carburant->nom }}</div>
        </div>
        <div class="info-item">
            <label>Date d'Entrée</label>
            <div class="value">{{ $entree->date_entree->format('d/m/Y à H:i') }}</div>
        </div>
        <div class="info-item">
            <label>N° Bon de Livraison</label>
            <div class="value {{ !$entree->numero_bon_livraison ? 'muted' : '' }}">
                {{ $entree->numero_bon_livraison ?? 'Non renseigné' }}
            </div>
        </div>
        <div class="info-item">
            <label>Commande Associée</label>
            <div class="value {{ !$entree->commande ? 'muted' : '' }}">
                {{ $entree->commande->numero_commande ?? 'Aucune' }}
            </div>
        </div>
    </div>

    {{-- ─── Détail financier ─── --}}
    <div class="section-title">Détail Financier</div>
    <table class="financial-table">
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Carburant</th>
                <th>Quantité (L)</th>
                <th>Prix Unitaire (FCFA)</th>
                <th>Montant Total (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Entrée de carburant</td>
                <td>{{ $entree->cuve->carburant->nom }}</td>
                <td>{{ number_format($entree->quantite, 2, ',', ' ') }}</td>
                <td>{{ number_format($entree->prix_unitaire, 0, ',', ' ') }}</td>
                <td>{{ number_format($entree->montant_total, 0, ',', ' ') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><strong>TOTAL</strong></td>
                <td><strong>{{ number_format($entree->quantite, 2, ',', ' ') }} L</strong></td>
                <td></td>
                <td><strong>{{ number_format($entree->montant_total, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
        </tfoot>
    </table>

    {{-- ─── Impact sur la cuve ─── --}}
    <div class="section-title">Impact sur le Stock de la Cuve</div>
    <div class="info-grid">
        <div class="info-item">
            <label>Stock avant entrée</label>
            <div class="value">{{ number_format($entree->cuve->stock_actuel - $entree->quantite, 0, ',', ' ') }} L</div>
        </div>
        <div class="info-item">
            <label>Quantité ajoutée</label>
            <div class="value large">+ {{ number_format($entree->quantite, 0, ',', ' ') }} L</div>
        </div>
        <div class="info-item">
            <label>Stock après entrée</label>
            <div class="value large">{{ number_format($entree->cuve->stock_actuel, 0, ',', ' ') }} L</div>
        </div>
    </div>

    {{-- ─── Observation ─── --}}
    <div class="section-title">Observation</div>
    <div class="observation-box">
        {{ $entree->observation ?? 'Aucune observation.' }}
    </div>

    {{-- ─── Enregistrement ─── --}}
    <div class="info-grid cols-2" style="margin-bottom:0">
        <div class="info-item">
            <label>Enregistré par</label>
            <div class="value">{{ $entree->user->prenom }} {{ $entree->user->nom }}</div>
        </div>
        <div class="info-item">
            <label>Date d'enregistrement</label>
            <div class="value">{{ $entree->created_at->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    {{-- ─── Signatures ─── --}}
    <div class="signatures">
        <div class="signature-block">
            <div class="signature-label">Responsable Livraison</div>
            <div class="signature-line">Signature & Cachet</div>
        </div>
        <div class="signature-block">
            <div class="signature-label">Réceptionniste</div>
            <div class="signature-line">{{ $entree->user->prenom }} {{ $entree->user->nom }}</div>
        </div>
        <div class="signature-block">
            <div class="signature-label">Responsable Station</div>
            <div class="signature-line">{{ $station->responsable ?? 'Signature & Cachet' }}</div>
        </div>
    </div>

    {{-- ─── Pied de page ─── --}}
    <div class="footer">
        {{ $station->nom ?? 'Station Service' }} — {{ $station->adresse ?? '' }}
        @if($station->telephone ?? false) | Tél : {{ $station->telephone }}@endif
        @if($station->email ?? false) | {{ $station->email }}@endif
        <br>Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>
