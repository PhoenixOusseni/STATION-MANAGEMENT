<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Vente {{ $vente->numero_vente }}</title>
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

        /* ─── Badge mode paiement ─── */
        .badge-paiement {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-especes   { background: #d4edda; color: #155724; }
        .badge-carte     { background: #cce5ff; color: #004085; }
        .badge-mobile    { background: #d1ecf1; color: #0c5460; }
        .badge-credit    { background: #fff3cd; color: #856404; }

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

        /* ─── Alerte crédit ─── */
        .alert-credit {
            border: 2px solid #ffc107;
            background: #fff8e1;
            border-radius: 4px;
            padding: 12px 16px;
            margin-bottom: 28px;
            font-size: 13px;
        }

        .alert-credit strong {
            font-size: 15px;
            color: #856404;
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
            grid-template-columns: repeat(2, 1fr);
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
        <a href="{{ route('ventes.show', $vente) }}" class="btn-back">&#8592; Retour</a>
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
            <div class="document-title">Reçu de Vente</div>
            <div class="document-number">{{ $vente->numero_vente }}</div>
            @if($vente->numero_ticket)
                <div class="document-date">N° Ticket : {{ $vente->numero_ticket }}</div>
            @endif
            <div class="document-date">
                Date de vente : {{ $vente->date_vente->format('d/m/Y à H:i') }}
            </div>
            <div class="document-date">
                Imprimé le : {{ now()->format('d/m/Y à H:i') }}
            </div>
        </div>
    </div>

    {{-- ─── Informations générales ─── --}}
    <div class="section-title">Informations de la Vente</div>
    <div class="info-grid">
        <div class="info-item">
            <label>N° de Vente</label>
            <div class="value">{{ $vente->numero_vente }}</div>
        </div>
        <div class="info-item">
            <label>Date de Vente</label>
            <div class="value">{{ $vente->date_vente->format('d/m/Y à H:i') }}</div>
        </div>
        <div class="info-item">
            <label>Mode de Paiement</label>
            <div class="value">
                @switch($vente->mode_paiement)
                    @case('especes')
                        <span class="badge-paiement badge-especes">Espèces</span>
                    @break
                    @case('carte')
                        <span class="badge-paiement badge-carte">Carte Bancaire</span>
                    @break
                    @case('mobile_money')
                        <span class="badge-paiement badge-mobile">Mobile Money</span>
                    @break
                    @case('credit')
                        <span class="badge-paiement badge-credit">À Crédit</span>
                    @break
                @endswitch
            </div>
        </div>
        <div class="info-item">
            <label>Pistolet</label>
            <div class="value">{{ $vente->pistolet->numero }}</div>
        </div>
        <div class="info-item">
            <label>Pompe</label>
            <div class="value">{{ $vente->pistolet->pompe->numero }}</div>
        </div>
        <div class="info-item">
            <label>Cuve</label>
            <div class="value">{{ $vente->pistolet->pompe->cuve->nom }}</div>
        </div>
        <div class="info-item">
            <label>Carburant</label>
            <div class="value">{{ $vente->pistolet->pompe->cuve->carburant->nom }}</div>
        </div>
        <div class="info-item">
            <label>Client</label>
            <div class="value {{ !$vente->client ? 'muted' : '' }}">
                {{ $vente->client ?? 'Client comptant' }}
            </div>
        </div>
        @if($vente->numero_ticket)
        <div class="info-item">
            <label>N° Ticket</label>
            <div class="value">{{ $vente->numero_ticket }}</div>
        </div>
        @endif
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
                <td>Vente de carburant</td>
                <td>{{ $vente->pistolet->pompe->cuve->carburant->nom }}</td>
                <td>{{ number_format($vente->quantite_vendue, 2, ',', ' ') }}</td>
                <td>{{ number_format($vente->prix_unitaire, 0, ',', ' ') }}</td>
                <td>{{ number_format($vente->montant_total, 0, ',', ' ') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><strong>TOTAL</strong></td>
                <td><strong>{{ number_format($vente->quantite_vendue, 2, ',', ' ') }} L</strong></td>
                <td></td>
                <td><strong>{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
        </tfoot>
    </table>

    {{-- ─── Alerte crédit ─── --}}
    @if($vente->mode_paiement === 'credit')
        <div class="alert-credit">
            &#9888; <strong>Montant à recouvrer : {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</strong>
            &nbsp;—&nbsp; Client : {{ $vente->client ?? 'Non renseigné' }}
        </div>
    @endif

    {{-- ─── Observation ─── --}}
    <div class="section-title">Observation</div>
    <div class="observation-box">
        {{ $vente->observation ?? 'Aucune observation.' }}
    </div>

    {{-- ─── Enregistrement ─── --}}
    <div class="info-grid cols-2" style="margin-bottom:0">
        <div class="info-item">
            <label>Vendu par</label>
            <div class="value">{{ $vente->pompiste->prenom ?? '' }} {{ $vente->pompiste->nom ?? '' }}</div>
        </div>
        <div class="info-item">
            <label>Date d'enregistrement</label>
            <div class="value">{{ $vente->created_at->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    {{-- ─── Signatures ─── --}}
    <div class="signatures">
        <div class="signature-block">
            <div class="signature-label">Pompiste / Vendeur</div>
            <div class="signature-line">{{ $vente->pompiste->prenom ?? '' }} {{ $vente->pompiste->nom ?? '' }}</div>
        </div>
        <div class="signature-block">
            <div class="signature-label">Client</div>
            <div class="signature-line">{{ $vente->client ?? 'Signature' }}</div>
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
