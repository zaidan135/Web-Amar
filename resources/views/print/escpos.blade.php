<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Struk – {{ $trx->nomor_transaksi }}</title>
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 10pt;
            width: 280px;
            margin: 0;
            padding: 5px;
            line-height: 1.3;
        }
        pre {
            margin: 0;
            white-space: pre;
            text-align: left;
        }
        .center-text { 
            text-align: center; 
        }
    </style>
</head>
<body>
<pre>
<div class="center-text">
Kedai Amar
Jl. Rancabolang No.248
Bandung
</div>
{{ str_repeat('-', 38) }}
No. Struk   : {{ $trx->nomor_transaksi }}
Tanggal     : {{ $trx->tanggal_pembayaran->format('d/m/y H:i') }}
Kasir       : {{ $trx->user->name }}
@if($trx->pelanggan)
Pelanggan   : {{ $trx->pelanggan->nama }}
@endif
@if($trx->no_table)
No. Meja    : {{ $trx->no_table }}
@endif
{{ str_repeat('-', 38) }}
@foreach($trx->details as $item)
{{ 
    str_pad(substr($item->produk->nama, 0, 20), 20) . 
    str_pad($item->kuantitas . 'x', 5, ' ', STR_PAD_LEFT) . 
    str_pad('Rp' . number_format($item->subtotal, 0, ',', '.'), 13, ' ', STR_PAD_LEFT) 
}}
@endforeach
{{ str_repeat('-', 38) }}
{{ str_pad('Subtotal', 25) . str_pad('Rp' . number_format($trx->total_harga - $trx->pajak, 0, ',', '.'), 13, ' ', STR_PAD_LEFT) }}
{{ str_pad('Pajak (11%)', 25) . str_pad('Rp' . number_format($trx->pajak, 0, ',', '.'), 13, ' ', STR_PAD_LEFT) }}
{{ str_repeat('=', 38) }}
{{ str_pad('TOTAL', 25) . str_pad('Rp' . number_format($trx->total_harga, 0, ',', '.'), 13, ' ', STR_PAD_LEFT) }}
{{ str_repeat('=', 38) }}
<div class="center-text">
Terima kasih!
Simpan struk ini
sebagai bukti pembayaran.
</div>
</pre>
</body>
</html>
