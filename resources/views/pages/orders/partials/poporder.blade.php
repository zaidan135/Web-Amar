{{--
  Saya menggabungkan dua div modal Anda menjadi satu untuk struktur yang lebih bersih.
  Ini tidak akan mengubah tampilan, hanya membuat kode lebih rapi.
--}}
<div x-show="showModal"        
    x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-25"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-25"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-10 flex items-center justify-center bg-black opacity-35"
             style="display: none;">

</div>
<div x-show="showModal"
     x-transition
     class="fixed inset-0 z-20 flex items-center justify-center"
     style="display: none;">

    <div @click.away="showModal = false"
         class="relative w-full max-w-lg rounded-lg bg-white p-6 shadow-xl flex flex-col gap-6">
        
        <div class="flex items-start justify-between">
            <h2 class="text-xl font-bold text-gray-900">Detail Pesanan & Pelanggan</h2>
            <button @click="showModal = false" type="button" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        {{-- Form Input Pelanggan & Meja (x-model mengikat ke state 'customer') --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2"><label class="block text-sm font-medium">Nama Pelanggan</label><x-input-element x-model="customer.nama_pelanggan" placeholder="cth : Andi" /></div>
            <div><label class="block text-sm font-medium">No. Telepon (Opsional)</label><x-input-element x-model="customer.telepon" placeholder="cth : 085877889966"/></div>
            <div><label class="block text-sm font-medium">Email (Opsional)</label><x-input-element x-model="customer.email" type="email" placeholder="cth : andi@gmail.com"/></div>
            <div class="col-span-2"><label class="block text-sm font-medium">Nomor Meja (Opsional)</label><x-input-element x-model="customer.no_table" placeholder="cth : 01" /></div>
        </div>

        {{-- DIUBAH: List Produk yang Dipesan dari Keranjang --}}
        <div>
            <h3 class="font-bold text-gray-800 mb-2">Produk Dipesan (<span x-text="totalItems"></span>)</h3>
            <ul class="space-y-2 max-h-40 overflow-y-auto border rounded-lg p-3">
                <template x-if="cart.length === 0"><li class="text-center text-gray-500">Keranjang kosong.</li></template>
                <template x-for="item in cart" :key="item.id">
                    <div class="space-y-1">
                        <li class="flex justify-between items-center text-sm">
                            <p class="font-semibold" x-text="`${item.nama} (x${item.quantity})`"></p>
                            <p class="font-bold" x-text="`Rp ${formatUang(item.quantity * item.harga)}`"></p>
                        </li>
                        <textarea x-model="item.catatan" placeholder="Catatan untuk item ini (opsional)" class="w-full p-2 border rounded text-xs resize-none"></textarea>
                    </div>
                </template>
            </ul>
        </div>

        {{-- Rincian Total Keseluruhan --}}
        <div class="space-y-1 border-t pt-4 text-sm">
            <p class="flex justify-between"><span>Subtotal</span> <span x-text="`Rp ${formatUang(subtotal)}`"></span></p>
            <p class="flex justify-between"><span>Pajak (11%)</span> <span x-text="`Rp ${formatUang(pajak)}`"></span></p>
            <p class="flex justify-between text-base font-bold"><span>TOTAL</span> <span x-text="`Rp ${formatUang(total)}`"></span></p>
        </div>

        {{-- Tombol Aksi Modal --}}
        <div class="flex justify-end gap-3 pt-4">
            <x-button @click="showModal = false" type="button" intent="secondary">Cancel</x-button>
            {{-- Tombol ini akan men-submit form utama 'orderForm' --}}
            <x-button 
                type="submit" 
                form="orderForm"
                intent="primary"
                x-bind:disabled="!customer.nama_pelanggan && !customer.no_table"
                x-bind:class="(!customer.nama_pelanggan && !customer.no_table) ? 'border-gray-300 text-gray-800 hover:bg-gray-100 focus:ring-gray-300' : ''">
                process
            </x-button>
        </div>
    </div>
</div>