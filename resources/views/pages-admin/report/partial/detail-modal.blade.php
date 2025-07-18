{{-- resources/views/pages-admin/report/partial/detail-modal.blade.php --}}

{{-- overlay --}}
<div x-show="showDetail" x-transition.opacity
     class="fixed inset-0 bg-black/30 z-10"></div>

<div x-show="showDetail" x-transition
     class="fixed inset-0 z-20 flex items-center justify-center">
  <div @click.away="showDetail=false"
       class="bg-white w-full max-w-2xl rounded-2xl p-6 shadow-xl space-y-4">

    <!-- HEADER -->
    <div class="flex justify-between items-start">
      <div>
        <h2 class="text-xl font-bold">Detail Transaksi</h2>
        <p class="text-sm text-gray-500" x-text="selected?.nomor_transaksi"></p>
      </div>
      <button @click="showDetail=false" class="text-gray-400 text-xl">&times;</button>
    </div>

    <!-- INFO -->
    <div class="grid grid-cols-3 gap-4 text-sm border-t pt-4">
      <div><span class="font-medium">Pelanggan</span><br>
           <span x-text="selected?.pelanggan?.nama || '-'"></span></div>
      <div><span class="font-medium">No. Meja</span><br>
           <span x-text="selected?.no_table || '-'"></span></div>
      <div><span class="font-medium">Kasir</span><br>
           <span x-text="selected?.user.name"></span></div>
    </div>

    <!-- ITEM LIST -->
    <div class="border-t pt-4">
      <h3 class="font-medium mb-2">Rincian Item</h3>
      <ul class="space-y-2 max-h-60 overflow-y-auto border rounded-lg p-3">
        <template x-for="d in selected?.details || []" :key="d.id">
          <li class="flex justify-between items-start">
            <div>
              <p class="font-medium" x-text="d.produk.nama"></p>
              <p class="text-xs text-gray-500"
                 x-text="`${d.kuantitas} x Rp ${Number(d.harga_saat_transaksi).toLocaleString('id-ID')}`"></p>

              <template x-if="d.catatan">
                <p class="text-xs italic text-gray-400"
                   x-text="`Catatan: ${d.catatan}`"></p>
              </template>
            </div>
            <p class="font-semibold"
               x-text="`Rp ${Number(d.subtotal).toLocaleString('id-ID')}`"></p>
          </li>
        </template>
      </ul>
    </div>

    <!-- TOTAL -->
    <div class="border-t pt-4 text-sm text-right space-y-1">
      <p class="flex justify-between">
         <span>Pajak (11%)</span>
         <span x-text="`Rp ${Number(selected?.pajak || 0).toLocaleString('id-ID')}`"></span>
      </p>
      <p class="flex justify-between font-bold text-base">
         <span>Total</span>
         <span x-text="`Rp ${Number(selected?.total_harga || 0).toLocaleString('id-ID')}`"></span>
      </p>
    </div>

    <!-- ACTION BAR -->
    <div class="flex justify-end gap-2 pt-2">
        <x-button size="sm" variant="outline" @click="showDetail=false">Tutup</x-button>

        {{-- 
            =================================================
            PERUBAHAN LOGIKA CETAK DIMULAI DI SINI
            =================================================
            - Action form dibuat statis.
            - ID transaksi dikirim melalui hidden input.
            - Ini memastikan hanya parameter 'single' yang dikirim.
        --}}
        <form action="{{ route('admin.report.print') }}"
              target="_blank" 
              method="GET">
            
            <!-- Input tersembunyi yang nilainya diambil dari Alpine.js -->
            <input type="hidden" name="single" x-bind:value="selected?.id">

            <x-button size="sm" intent="primary" type="submit">Cetak</x-button>
        </form>
        {{-- ================================================= --}}
    </div>
  </div>
</div>
