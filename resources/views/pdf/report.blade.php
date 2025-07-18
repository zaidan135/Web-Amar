<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan Penjualan' }}</title>
    {{-- Menggunakan style yang sama persis dengan pdf.transaction untuk konsistensi --}}
    <style>
        body { 
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            color: #333; 
        }
        .container { 
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .header p { margin: 5px 0 0; font-size: 14px; }
        
        .report-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        .report-table thead th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .report-table tbody td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        .report-table tfoot td {
            font-weight: bold;
            border: 1px solid #ddd;
            padding: 8px;
            background-color: #f2f2f2;
        }
        
        .footer { 
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center; 
            font-size: 10px; 
            color: #777;
        }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kedai Amar</h1>
            <p>{{ $title }}</p>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>No. Meja</th>
                    <th>Kasir</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td>{{ $r->nomor_transaksi }}</td>
                        <td>{{ $r->tanggal_pembayaran->format('d M Y, H:i') }}</td>
                        <td>{{ $r->pelanggan->nama ?? '-' }}</td>
                        <td>{{ $r->no_table ?? '-' }}</td>
                        <td>{{ $r->user->name }}</td>
                        <td class="text-right">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right">GRAND TOTAL</td>
                    <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        
        <div class="footer">
            Dicetak pada {{ now()->format('d F Y, H:i') }}
        </div>
    </div>
</body>
</html>
