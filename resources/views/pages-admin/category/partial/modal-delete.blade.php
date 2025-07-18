{{-- Modal untuk Konfirmasi Penghapusan --}}
        <div x-show="showDeleteModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-25"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-25"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-10 flex items-center justify-center bg-black opacity-35"
             style="display: none;">
        </div>

<div x-show="showDeleteModal"
     x-transition
     class="fixed inset-0 z-20 flex items-center justify-center"
     style="display: none;">

    <div @click.away="showDeleteModal = false"
         class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl flex flex-col gap-4 text-center">
        
        {{-- Ikon Peringatan --}}
        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        
        {{-- Konten Teks Dinamis --}}
        <div>
            <h2 class="text-lg font-bold text-gray-900">Konfirmasi Penghapusan</h2>
            <p class="mt-2 text-sm text-gray-600">
                Apakah Anda yakin ingin menghapus kategori
                {{-- Tampilkan nama kategori yang akan dihapus --}}
                <strong class="font-bold" x-text="categoryToDelete ? categoryToDelete.nama : ''"></strong>?
                <br>
                Tindakan ini tidak dapat diurungkan.
            </p>
        </div>
        
        {{-- Tombol Aksi --}}
        <div class="flex justify-center gap-4 mt-4">
            <x-button @click="showDeleteModal = false" type="button" intent="secondary">
                Batal
            </x-button>
            
            {{-- Form ini hanya akan di-submit saat tombol 'Ya, Hapus' diklik --}}
            <form :action="deleteFormAction" method="POST">
                @csrf
                @method('DELETE')
                <x-button type="submit" intent="danger">
                    Ya, Hapus
                </x-button>
            </form>
        </div>
    </div>
</div>