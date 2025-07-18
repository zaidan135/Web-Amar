{{-- Modal untuk menampilkan detail transaksi --}}
<div x-show="showDetailModal"        
    x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-25"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-25"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-10 flex items-center justify-center bg-black opacity-35"
             style="display: none;">

</div>
<div x-show="showDetailModal" 
     x-transition class="fixed inset-0 z-20 flex items-center justify-center"
     style="display: none;">

    {{-- Panel Modal, gunakan x-show="selectedTransaction" agar tidak error saat data masih null --}}
    <div @click.away="showDetailModal = false" x-show="selectedTransaction"
         class="relative w-full max-w-2xl rounded-lg bg-white p-6 shadow-xl flex flex-col gap-4">
        
        {{-- Header Modal --}}
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Detail Transaksi</h2>
                {{-- Tampilkan nomor transaksi jika selectedTransaction sudah terisi --}}
                <p class="text-sm text-gray-600" x-text="selectedTransaction ? selectedTransaction.nomor_transaksi : ''"></p>
            </div>
            <button @click="showDetailModal = false" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        {{-- Detail Pelanggan & Meja (jika ada) --}}
        <div class="grid grid-cols-3 gap-4 text-sm border-t pt-4">
            <div>
                <dt class="font-medium text-gray-900">Pelanggan</dt>
                <dd class="mt-1 text-gray-600" x-text="selectedTransaction ? (selectedTransaction.pelanggan ? selectedTransaction.pelanggan.nama : '-') : ''"></dd>
            </div>
            <div>
                <dt class="font-medium text-gray-900">No. Meja</dt>
                <dd class="mt-1 text-gray-600" x-text="selectedTransaction ? (selectedTransaction.no_table || '-') : ''"></dd>
            </div>
            <div>
                <dt class="font-medium text-gray-900">Kasir</dt>
                <dd class="mt-1 text-gray-600" x-text="selectedTransaction ? selectedTransaction.user.name : ''"></dd>
            </div>
        </div>

        {{-- Daftar Item Produk --}}
        <div class="border-t pt-4">
            <h3 class="font-medium text-gray-900 mb-2">Rincian Item</h3>
            <ul class="space-y-2 max-h-60 overflow-y-auto border rounded-lg p-3">
                {{-- Template untuk looping item menggunakan Alpine.js --}}
<template x-for="detail in selectedTransaction?.details || []" :key="detail.id">
    <li class="flex justify-between items-start">
        <div>
            <p class="font-medium text-gray-800" x-text="detail.produk.nama"></p>
            <p class="text-xs text-gray-500" x-text="`${detail.kuantitas} x Rp ${Number(detail.harga_saat_transaksi).toLocaleString('id-ID')}`"></p>

            <!-- Tambahan: Catatan Produk -->
            <template x-if="detail.catatan">
                <p class="text-xs italic text-gray-400 mt-1" x-text="`Catatan: ${detail.catatan}`"></p>
            </template>
        </div>
        <p class="font-semibold text-gray-900" x-text="`Rp ${Number(detail.subtotal).toLocaleString('id-ID')}`"></p>
    </li>
</template>

            </ul>
        </div>

        {{-- Rincian Total --}}
        <div class="space-y-1 border-t pt-4 text-right text-sm">
             <p class="flex justify-between">
                <span>Pajak (11%)</span>
                <span x-text="`Rp ${Number(selectedTransaction?.pajak || 0).toLocaleString('id-ID')}`"></span>
             </p>
             <p class="flex justify-between text-base font-bold text-gray-900">
                <span>Total</span>
                <span x-text="`Rp ${Number(selectedTransaction?.total_harga || 0).toLocaleString('id-ID')}`"></span>
             </p>
        </div>

                <div class="mt-6 border-t pt-4">
             <template x-if="selectedTransaction && selectedTransaction.status == 'menunggu'">
                <x-button type="button" @click="pay(selectedTransaction.id); showDetailModal = false" intent="success" class="w-full justify-center">
                    Lanjutkan Pembayaran & Cetak
                </x-button>
             </template>
             <template x-if="selectedTransaction && selectedTransaction.status == 'lunas'">
                <p class="text-center text-sm text-gray-500">Transaksi ini sudah lunas.</p>
             </template>
        </div>
    </div>
</div>