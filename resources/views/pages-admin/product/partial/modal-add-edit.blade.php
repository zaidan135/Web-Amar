        <div x-show="showAddEditModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-25"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-25"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-10 flex items-center justify-center bg-black opacity-35"
             style="display: none;">
        </div>


<div x-show="showAddEditModal" x-transition class="fixed inset-0 z-10 flex items-center justify-center" style="display: none;">
    <div @click.away="showAddEditModal = false" class="relative w-full max-w-lg rounded-lg bg-white p-6 shadow-xl flex flex-col gap-4">
        <h2 class="text-xl font-bold text-gray-900" x-text="isEditMode ? 'Edit Produk' : 'Tambah Produk Baru'"></h2>
        <form :action="formAction" method="POST" class="space-y-4">
            @csrf
            <template x-if="isEditMode">@method('PUT')</template>
            <input type="hidden" name="id" :value="product.id">

            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <x-text-input x-model="product.nama" id="nama" name="nama" class="mt-1" required />
                @error('nama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select x-model="product.kategori_id" id="kategori_id" name="kategori_id" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                    <x-text-input x-model="product.harga" type="number" id="harga" name="harga" class="mt-1" required />
                    @error('harga')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select x-model="product.status" id="status" name="status" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
                @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end gap-3 border-t pt-4 mt-4">
                <x-button @click.prevent="showAddEditModal = false" type="button" intent="secondary">Batal</x-button>
                <x-button type="submit"><span x-text="isEditMode ? 'Simpan Perubahan' : 'Tambah Produk'"></span></x-button>
            </div>
        </form>
    </div>
</div>