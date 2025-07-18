<x-layout>
    <div x-data="{ 
            showDetailModal: false, 
            selectedTransaction: null, 
            highlightedId: new URLSearchParams(window.location.search).get('highlight'),
            
            async pay(transactionId) {
                const csrfToken = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');

                try {
                    const response = await fetch(`/book-order/${transactionId}/pay`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (!response.ok) throw new Error('Gagal memproses pembayaran.');

                    const result = await response.json();
                    
                    const redirectToIndex = () => {
                        window.location.href = `${result.redirect}?highlight=${transactionId}`;
                    };

                    if (result.mode === 'pdf' && result.pdfUrl) {
                        window.open(result.pdfUrl, '_blank');
                        setTimeout(redirectToIndex, 500); 
                    } else if (result.mode === 'direct' && result.raw) {
                        const printFrame = document.createElement('iframe');
                        printFrame.style.display = 'none';
                        document.body.appendChild(printFrame);
                        
                        const printWindow = printFrame.contentWindow;
                        printWindow.document.write(result.raw);
                        printWindow.document.close();
                        printWindow.focus();
                        
                        // --- LOGIKA BARU YANG PALING ANDAL ---
                        // 1. Panggil perintah cetak. Perintah ini tidak menunggu.
                        printWindow.print();
                        
                        // 2. Setelah jeda singkat, kita anggap proses cetak selesai
                        //    dan kita ambil alih kontrol untuk me-refresh halaman.
                        setTimeout(() => {
                            // Hapus iframe untuk kebersihan
                            document.body.removeChild(printFrame);
                            
                            // Panggil fungsi refresh
                            redirectToIndex();
                        }, 0);

                    } else {
                        alert('Pembayaran berhasil, mode cetak tidak valid.');
                        redirectToIndex();
                    }

                } catch (error) {
                    console.error('Terjadi kesalahan:', error);
                    alert('Terjadi kesalahan. Silakan periksa konsol browser.');
                }
            }
         }" 
         class="min-h-full max-h-full w-full flex flex-col">
        
        {{-- Header Halaman (Tidak Diubah) --}}
        <div class=" h-[90px] w-full px-6 flex items-center flex-shrink-0">
            <form method="GET" action="{{ route('book-order.index') }}" class="w-full">
                <x-search-button type="text" name="search" value="{{ request('search') }}" 
                                 placeholder="Cari Nama Pelanggan atau No. Meja..."/>
            </form>
        </div>

        {{-- Notifikasi Sukses (Tidak Diubah) --}}
        @if (session('success'))
            <div x-data="{ showAlert: true }" x-init="setTimeout(() => showAlert = false, 2000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed top-5 inset-x-0 mx-6 max-w-full sm:max-w-md sm:mx-auto z-50">
                <div class="relative w-full rounded-lg border border-green-400 bg-green-100 px-4 pr-12 py-3 text-green-700 shadow-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <strong class="font-bold">Sukses! </strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                    <button @click="showAlert = false" type="button" class="absolute top-2 right-2 text-xl leading-none text-green-700 hover:text-green-900">&times;</button>
                </div>
            </div>
        @endif

        {{-- Area Konten dengan Tabel (Tidak Diubah) --}}
        <div class="flex-1 overflow-y-auto">
            <div class="px-6">
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">No. Transaksi</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Tanggal</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Pelanggan</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">No. Meja</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Kasir</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Total</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Status</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($transaksis as $transaksi)
                                <tr :class="{ 'bg-blue-100 ring-2 ring-blue-300': highlightedId == {{ $transaksi->id }} }">
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $transaksi->nomor_transaksi }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $transaksi->tanggal_transaksi->format('d M Y, H:i') }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $transaksi->pelanggan->nama ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $transaksi->no_table ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $transaksi->user->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        @if ($transaksi->status == 'lunas')
                                            <span class="inline-flex items-center justify-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-emerald-700">Lunas</span>
                                        @else
                                            <span class="inline-flex items-center justify-center rounded-full bg-amber-100 px-2.5 py-0.5 text-amber-700">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <x-button size="sm" variant="outline" 
                                                @click="selectedTransaction = {{ Js::from($transaksi->loadMissing(['details.produk', 'pelanggan', 'user'])) }}; showDetailModal = true">
                                                Detail
                                            </x-button>
                                            @if ($transaksi->status == 'menunggu')
                                                <x-button type="button" size="sm" intent="success" @click="pay({{ $transaksi->id }})">
                                                    Bayar
                                                </x-button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500">Belum ada data transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Paginasi (Tidak Diubah) --}}
        <div class="flex justify-center py-4">
            {{ $transaksis->links('vendor.pagination.custom') }}
        </div>
        
        {{-- Modal Detail (Tidak Diubah) --}}
        @include('pages.books.partials.detail-modal') 
    </div>
</x-layout>
