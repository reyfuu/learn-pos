<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - {{ $periodLabel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1e293b; background: #fff; font-size: 12px; }

        .header { text-align: center; padding: 28px 0 20px; border-bottom: 2px solid #6366f1; margin-bottom: 24px; }
        .header .logo-badge { display: inline-block; background: #6366f1; color: white; font-size: 22px; font-weight: 800; padding: 6px 20px; border-radius: 8px; letter-spacing: 1px; margin-bottom: 10px; }
        .header h1 { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .header p { font-size: 12px; color: #64748b; }

        .meta-row { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .meta-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; flex: 1; margin: 0 4px; text-align: center; }
        .meta-box:first-child { margin-left: 0; }
        .meta-box:last-child { margin-right: 0; }
        .meta-box .val { font-size: 16px; font-weight: 700; color: #6366f1; }
        .meta-box .lbl { font-size: 10px; color: #94a3b8; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.5px; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead { background: #6366f1; }
        thead th { color: white; font-weight: 600; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        thead th:last-child { text-align: right; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 9px 12px; color: #374151; font-size: 11.5px; }
        tbody td:last-child { text-align: right; font-weight: 600; color: #059669; }
        tfoot tr { background: #f1f5f9; border-top: 2px solid #6366f1; }
        tfoot td { padding: 10px 12px; font-weight: 700; font-size: 12px; }
        tfoot td:last-child { text-align: right; color: #6366f1; }

        .footer { text-align: center; margin-top: 28px; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-badge">KasirPro</div>
        <h1>Laporan Penjualan</h1>
        <p>Periode: {{ $periodLabel }} &nbsp;|&nbsp; Dicetak: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    @php
        $totalRevenue = $transactions->sum('grand_total');
        $totalTax     = $transactions->sum('tax');
        $totalItems   = $transactions->sum(fn($t) => $t->details->sum('quantity'));
    @endphp

    <div class="meta-row">
        <div class="meta-box">
            <div class="val">{{ $transactions->count() }}</div>
            <div class="lbl">Total Transaksi</div>
        </div>
        <div class="meta-box">
            <div class="val">{{ $totalItems }}</div>
            <div class="lbl">Total Item Terjual</div>
        </div>
        <div class="meta-box">
            <div class="val" style="color:#059669;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="lbl">Total Pendapatan</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Item</th>
                <th>Subtotal</th>
                <th>Pajak</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-family: monospace; font-size: 11px;">{{ $t->invoice_number }}</td>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $t->details->sum('quantity') }} item</td>
                <td>Rp {{ number_format($t->subtotal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($t->tax, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">TOTAL KESELURUHAN</td>
                <td>Rp {{ number_format($transactions->sum('subtotal'), 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalTax, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh sistem KasirPro &bull; {{ config('app.name') }}
    </div>
</body>
</html>
