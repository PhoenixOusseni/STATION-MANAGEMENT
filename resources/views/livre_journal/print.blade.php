<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre Journal — {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('D MMMM YYYY') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            background: #fff;
            padding: 20px;
        }

        /* ─── Boutons d'action ─── */
        .print-actions {
            text-align: center;
            margin-bottom: 24px;
        }

        .btn-print {
            background: #1a1a1a;
            color: #fff;
            border: none;
            padding: 10px 28px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 6px;
        }

        .btn-back {
            background: #f3f3f3;
            color: #1a1a1a;
            border: 1px solid #ccc;
            padding: 10px 28px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 6px;
            text-decoration: none;
            display: inline-block;
        }

        /* ─── En-tête ─── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #1a1a1a;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .header-left .company-name {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-left .company-info {
            color: #555;
            margin-top: 4px;
            line-height: 1.6;
            font-size: 11px;
        }

        .header-right {
            text-align: right;
        }

        .document-title {
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1a1a1a;
        }

        .document-date {
            font-size: 12px;
            color: #555;
            margin-top: 4px;
        }

        .document-meta {
            font-size: 10px;
            color: #999;
            margin-top: 6px;
        }

        /* ─── Titre de section ─── */
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            margin-bottom: 12px;
        }

        /* ─── Tableau principal ─── */
        .journal-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 11px;
        }

        .journal-table thead tr {
            background-color: #1a1a1a;
            color: #fff;
        }

        .journal-table thead th {
            padding: 8px 10px;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: 700;
        }

        .journal-table thead th:first-child {
            text-align: left;
        }

        .journal-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .journal-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .journal-table tbody td {
            padding: 7px 10px;
            text-align: center;
        }

        .journal-table tbody td:first-child {
            text-align: left;
            font-weight: 600;
        }

        .journal-table tfoot tr {
            background-color: #e8e8e8;
        }

        .journal-table tfoot td {
            padding: 8px 10px;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            border-top: 2px solid #1a1a1a;
        }

        .journal-table tfoot td:first-child {
            text-align: left;
        }

        .text-danger { color: #c0392b; }
        .text-success { color: #1e8449; }
        .text-muted   { color: #888; }

        /* ─── Récapitulatif ─── */
        .recap-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .recap-card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 12px 14px;
            background: #fafafa;
        }

        .recap-card .recap-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            margin-bottom: 4px;
        }

        .recap-card .recap-value {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .recap-card .recap-value.danger { color: #c0392b; }
        .recap-card .recap-value.success { color: #1e8449; }
        .recap-card .recap-value.neutral { color: #555; }

        /* ─── Légende ─── */
        .legend {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 14px;
            background: #fafafa;
            margin-bottom: 24px;
            font-size: 10px;
            color: #555;
            line-height: 1.8;
        }

        .legend strong {
            color: #1a1a1a;
        }

        /* ─── Signatures ─── */
        .signatures {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 36px;
            margin-bottom: 24px;
        }

        .signature-block {
            text-align: center;
        }

        .signature-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 36px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin: 0 10px;
            padding-top: 5px;
            font-size: 10px;
            color: #777;
        }

        /* ─── Pied de page ─── */
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 8px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }

        /* ─── Impression ─── */
        @media print {
            body {
                padding: 10px 15px;
            }

            .print-actions {
                display: none;
            }

            @page {
                size: A4 landscape;
                margin: 10mm 12mm;
            }
        }
    </style>
</head>
<body>

    {{-- ─── Boutons d'action ─── --}}
    <div class="print-actions">
        <a href="{{ route('livre_journal.index', ['date' => $date]) }}" class="btn-back">&#8592; Retour</a>
        <button class="btn-print" onclick="window.print()">&#128438; Imprimer</button>
    </div>

    {{-- ─── En-tête du document ─── --}}
    <div class="header">
        <div class="header-left">
            <div class="company-name">{{ $station->nom ?? 'Station Service' }}</div>
            <div class="company-info">
                @if($station->adresse ?? false){{ $station->adresse }}<br>@endif
                @if($station->telephone ?? false)Tél : {{ $station->telephone }}<br>@endif
                @if($station->email ?? false){{ $station->email }}@endif
            </div>
        </div>
        <div class="header-right">
            <div class="document-title">Livre Journal</div>
            <div class="document-date">
                Journée du {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
            </div>
            <div class="document-meta">Imprimé le : {{ now()->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    {{-- ─── Tableau du livre journal ─── --}}
    <div class="section-title">État journalier des stocks par cuve</div>

    <table class="journal-table">
        <thead>
            <tr>
                <th>Cuve</th>
                <th>Vente du Jour (L)</th>
                <th>Jauge Début (L)</th>
                <th>Qté Reçue (L)</th>
                <th>Bordereau</th>
                <th>Stock Total (L)</th>
                <th>Stock Théorique (L)</th>
                <th>Jauge Fin (L)</th>
                <th>Écart de Stock (L)</th>
                <th>Cumul Écart (L)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $ligne)
                <tr>
                    <td>{{ $ligne['cuve'] }}</td>
                    <td>{{ number_format($ligne['vente_jour'], 1, ',', ' ') }}</td>
                    <td>{{ number_format($ligne['jauge_debut'], 0, ',', ' ') }}</td>
                    <td>{{ number_format($ligne['qte_recu'], 1, ',', ' ') }}</td>
                    <td class="text-muted">{{ $ligne['bordereau'] ?: '—' }}</td>
                    <td>{{ number_format($ligne['stock_total'], 1, ',', ' ') }}</td>
                    <td>{{ number_format($ligne['stock_theorique'], 0, ',', ' ') }}</td>
                    <td>{{ number_format($ligne['jauge_fin'], 0, ',', ' ') }}</td>
                    <td class="{{ $ligne['ecart_stock'] < 0 ? 'text-danger' : ($ligne['ecart_stock'] > 0 ? 'text-success' : 'text-muted') }}">
                        {{ number_format($ligne['ecart_stock'], 0, ',', ' ') }}
                    </td>
                    <td>{{ number_format($ligne['cumul_ecart'], 1, ',', ' ') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center; padding:20px; color:#888;">
                        Aucune donnée pour cette journée.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($lignes) > 0)
            <tfoot>
                <tr>
                    <td>TOTAL</td>
                    <td>{{ number_format($totals['vente_jour'], 1, ',', ' ') }}</td>
                    <td>{{ number_format($totals['jauge_debut'], 1, ',', ' ') }}</td>
                    <td>{{ number_format($totals['qte_recu'], 1, ',', ' ') }}</td>
                    <td></td>
                    <td>{{ number_format($totals['stock_total'], 1, ',', ' ') }}</td>
                    <td>{{ number_format($totals['stock_theorique'], 1, ',', ' ') }}</td>
                    <td>{{ number_format($totals['jauge_fin'], 1, ',', ' ') }}</td>
                    <td class="{{ $totals['ecart_stock'] < 0 ? 'text-danger' : ($totals['ecart_stock'] > 0 ? 'text-success' : '') }}">
                        {{ number_format($totals['ecart_stock'], 1, ',', ' ') }}
                    </td>
                    <td>{{ number_format($totals['cumul_ecart'], 1, ',', ' ') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- ─── Récapitulatif des écarts ─── --}}
    @if(count($lignes) > 0)
        <div class="section-title">Récapitulatif des écarts</div>
        <div class="recap-grid">
            <div class="recap-card">
                <div class="recap-label">Total ventes du jour</div>
                <div class="recap-value">{{ number_format($totals['vente_jour'], 1, ',', ' ') }} L</div>
            </div>
            <div class="recap-card">
                <div class="recap-label">Somme écart de stock</div>
                <div class="recap-value {{ $totals['ecart_stock'] < 0 ? 'danger' : ($totals['ecart_stock'] > 0 ? 'success' : 'neutral') }}">
                    {{ number_format($totals['ecart_stock'], 1, ',', ' ') }} L
                </div>
            </div>
            <div class="recap-card">
                <div class="recap-label">Somme cumul des écarts</div>
                <div class="recap-value {{ $totals['cumul_ecart'] < 0 ? 'danger' : ($totals['cumul_ecart'] > 0 ? 'success' : 'neutral') }}">
                    {{ number_format($totals['cumul_ecart'], 1, ',', ' ') }} L
                </div>
            </div>
        </div>

        {{-- ─── Légende ─── --}}
        <div class="legend">
            <strong>Légende :</strong> &nbsp;
            <strong>Jauge Début</strong> = stock mesuré en début de session &nbsp;|&nbsp;
            <strong>Stock Total</strong> = Jauge Début + Qté Reçue &nbsp;|&nbsp;
            <strong>Stock Théorique</strong> = Stock Total − Vente du Jour &nbsp;|&nbsp;
            <strong>Écart de Stock</strong> = Jauge Fin − Stock Théorique &nbsp;|&nbsp;
            <span class="text-danger"><strong>Négatif</strong> = perte</span> &nbsp;|&nbsp;
            <span class="text-success"><strong>Positif</strong> = surplus</span>
        </div>
    @endif

    {{-- ─── Signatures ─── --}}
    <div class="signatures">
        <div class="signature-block">
            <div class="signature-label">Responsable de station</div>
            <div class="signature-line">Nom &amp; Signature</div>
        </div>
        <div class="signature-block">
            <div class="signature-label">Gérant / Superviseur</div>
            <div class="signature-line">Nom &amp; Signature</div>
        </div>
        <div class="signature-block">
            <div class="signature-label">Contrôleur</div>
            <div class="signature-line">Nom &amp; Signature</div>
        </div>
    </div>

    {{-- ─── Pied de page ─── --}}
    <div class="footer">
        {{ $station->nom ?? 'Station Service' }}
        @if($station->adresse ?? false) — {{ $station->adresse }}@endif
        @if($station->telephone ?? false) | Tél : {{ $station->telephone }}@endif
        <br>Livre Journal du {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('D MMMM YYYY') }}
        — Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>
