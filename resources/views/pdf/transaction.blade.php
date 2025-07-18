<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan Transaksi' }}</title>
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
        
        .info-section {
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            width: 120px;
        }

        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        .items-table thead th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .items-table tbody td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        .items-table .item-name { font-weight: bold; }
        .items-table .item-details { font-size: 10px; color: #666; }
        
        .totals-section {
            float: right;
            width: 40%;
        }
        .totals-table {
            width: 100%;
        }
        .totals-table td { padding: 5px; }
        .totals-table tr.grand-total td {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 8px;
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

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="label">No. Transaksi</td>
                    <td>: {{ $trx->nomor_transaksi }}</td>
                    <td class="label">Kasir</td>
                    <td>: {{ $trx->user->name }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Bayar</td>
                    <td>: {{ $trx->tanggal_pembayaran->format('d F Y, H:i') }}</td>
                    <td class="label">Pelanggan</td>
                    <td>: {{ $trx->pelanggan->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td>: Lunas</td>
                    <td class="label">No. Meja</td>
                    <td>: {{ $trx->no_table ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Kuantitas</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trx->details as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->produk->nama }}</div>
                            @if($item->catatan)
                                <div class="item-details">Catatan: {{ $item->catatan }}</div>
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($item->harga_saat_transaksi, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $item->kuantitas }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp {{ number_format($trx->total_harga - $trx->pajak, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pajak (11%)</td>
                    <td class="text-right">Rp {{ number_format($trx->pajak, 0, ',', '.') }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Total</td>
                    <td class="text-right">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <div class="footer">
            Dicetak pada {{ now()->format('d F Y, H:i') }}
        </div>
    </div>
</body>
</html>
