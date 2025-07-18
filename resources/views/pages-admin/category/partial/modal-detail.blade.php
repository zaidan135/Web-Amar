{{-- Modal untuk Tambah/Edit Kategori dengan struktur yang lebih rapi --}}
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

{{-- Modal untuk Tambah/Edit Kategori --}}
<div x-show="showModal"
     x-transition
     class="fixed inset-0 z-10 flex items-center justify-center "
     style="display: none;">

    {{-- Panel Modal --}}
    <div @click.away="showModal = false"
         class="relative w-full max-w-lg rounded-lg bg-white p-6 shadow-xl flex flex-col gap-4">
        
        {{-- Judul Modal Dinamis --}}
        <h2 class="text-xl font-bold text-gray-900" x-text="isEditMode ? 'Edit Kategori' : 'Tambah Kategori Baru'"></h2>
        
        {{-- Form dinamis --}}
        <form :action="formAction" method="POST" class="space-y-4">
            @csrf
            
            {{-- Bagian ini hanya akan dirender saat mode edit --}}
            <template x-if="isEditMode">
                <div>
                    @method('PUT')
                    {{-- Input tersembunyi untuk mengirim ID saat update --}}
                    <input type="hidden" name="id" :value="category.id">
                </div>
            </template>

            {{-- Input Nama --}}
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                {{-- x-model untuk interaktivitas, name & id untuk form --}}
                <x-input-element x-model="category.nama" x-bind:value="category.nama" id="nama" name="nama" placeholder="cth : Makanan" />
                {{-- Menampilkan pesan error khusus untuk 'nama' --}}
                @error('nama')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Status --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select x-model="category.status" id="status" name="status" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
                @error('status')
                     <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi Modal --}}
            <div class="flex justify-end gap-3 border-t pt-4 mt-4">
                <x-button @click.prevent="showModal = false" type="button" intent="secondary">Batal</x-button>
                <x-button type="submit">
                    <span x-text="isEditMode ? 'Simpan Perubahan' : 'Tambah Kategori'"></span>
                </x-button>
            </div>
        </form>
    </div>
</div>