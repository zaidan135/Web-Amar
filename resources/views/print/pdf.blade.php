<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Struk Pembayaran – {{ $trx->nomor_transaksi }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ====== STYLE ===================================================== -->
    <style>
        /* Variabel CSS untuk kemudahan kustomisasi */
        :root{
            --font-body: "Helvetica Neue", "Helvetica", Arial, sans-serif;
            --size-body: 12px;
            --size-small: 10px;
            --size-heading: 20px;
            --clr-text: #333;
            --clr-light: #777;
            --clr-border: #ddd;
            --clr-border-dark: #333;
        }
        * { 
            box-sizing: border-box; 
        }
        body { 
            font: var(--size-body)/1.45 var(--font-body); 
            color: var(--clr-text); 
            margin: 0; 
            padding: 20px 0; /* Memberi sedikit padding atas-bawah pada halaman A4 */
        }
        
        /* KUNCI UTAMA: Wrapper yang membatasi lebar struk dan menempatkannya di tengah */
        .wrap { 
            width: 95%; 
            max-width: 420px; /* Lebar maksimum struk, bisa disesuaikan */
            margin: 0 auto; /* Trik klasik untuk centering */
        }

        /* header */
        .hd { 
            text-align: center; 
            margin: 0 0 16px; 
        }
        .hd__ttl { 
            font-size: var(--size-heading); 
            margin: 0; 
            font-weight: 700; 
        }
        .hd__meta { 
            font-size: var(--size-small); 
            margin: 2px 0; 
        }

        /* table umum */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 14px; 
        }
        td, th { 
            padding: 6px 0; 
        }
        tr:not(:last-child) td { 
            border-bottom: 1px dashed var(--clr-border); 
        }

        /* info transaksi */
        .info td:first-child { 
            width: 40%; 
        }

        /* daftar item */
        .items thead th { 
            font-size: 11px; 
            text-transform: uppercase; 
            text-align: left;
            border-bottom: 2px solid var(--clr-border-dark); 
        }
        .items td { 
            vertical-align: top; 
        }
        .items .name { 
            font-weight: 600; 
        }
        .items .details { 
            font-size: var(--size-small); 
            color: var(--clr-light); 
        }

        /* total */
        .totals td { 
            font-weight: 600; 
        }
        .totals tr.grand td { 
            font-size: 14px; 
            border-top: 2px solid var(--clr-border-dark); 
            padding-top: 8px; 
        }

        /* utilitas */
        .txt-r { 
            text-align: right; 
        }

        /* footer */
        .ft { 
            text-align: center; 
            font-size: var(--size-small); 
            margin-top: 22px; 
        }
    </style>
</head>

<body>
    <div class="wrap">

        <!-- ---------- HEADER ---------- -->
        <header class="hd">
            <h1 class="hd__ttl">Kedai Amar</h1>
            <p class="hd__meta">Jl. Rancabolang No.248</p>
            <p class="hd__meta">Terima kasih atas kunjungan Anda!</p>
        </header>

        <!-- ---------- INFO ---------- -->
        <table class="info">
            <tr><td>No. Struk</td> <td class="txt-r">{{ $trx->nomor_transaksi }}</td></tr>
            <tr><td>Tanggal</td>   <td class="txt-r">{{ $trx->tanggal_pembayaran->format('d M Y, H:i') }}</td></tr>
            <tr><td>Kasir</td>     <td class="txt-r">{{ $trx->user->name }}</td></tr>

            @if($trx->pelanggan)
                <tr><td>Pelanggan</td><td class="txt-r">{{ $trx->pelanggan->nama }}</td></tr>
            @endif
            @if($trx->no_table)
                <tr><td>No. Meja</td> <td class="txt-r">{{ $trx->no_table }}</td></tr>
            @endif
        </table>

        <!-- ---------- ITEMS ---------- -->
        <table class="items">
            <thead>
                <tr><th>Item</th><th class="txt-r">Total</th></tr>
            </thead>
            <tbody>
                @foreach ($trx->details as $it)
                    <tr>
                        <td>
                            <div class="name">{{ $it->produk->nama }}</div>
                            <div class="details">{{ $it->kuantitas }} × Rp {{ number_format($it->harga_saat_transaksi,0,',','.') }}</div>
                        </td>
                        <td class="txt-r">Rp {{ number_format($it->subtotal,0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- ---------- TOTALS ---------- -->
        <table class="totals">
            <tr><td>Subtotal</td>     <td class="txt-r">Rp {{ number_format($trx->total_harga - $trx->pajak,0,',','.') }}</td></tr>
            <tr><td>Pajak (11%)</td>  <td class="txt-r">Rp {{ number_format($trx->pajak,0,',','.') }}</td></tr>
            <tr class="grand"><td>Total</td><td class="txt-r">Rp {{ number_format($trx->total_harga,0,',','.') }}</td></tr>
        </table>

        <!-- ---------- FOOTER ---------- -->
        <footer class="ft">
            <p>Simpan struk ini sebagai bukti pembayaran.</p>
        </footer>

    </div>
</body>
</html>
