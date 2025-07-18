<x-layout>
    {{-- State Alpine.js untuk mengontrol modal (Tidak Diubah) --}}
    <div x-data="{ showDetailModal: false, selectedTransaction: null }" class="min-h-full max-h-full w-full flex flex-col">
        
        {{-- Header Halaman (Tidak Diubah) --}}
        <div class="h-[90px] w-full px-6 flex items-center flex-shrink-0">
            <form action="{{ route('reports.index') }}" method="GET" class="w-full">
                <x-search-button
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari no transaksi, pelanggan, atau no meja..."
                />
            </form>
        </div>


        {{-- Area Konten dengan Tabel --}}
        <div class="flex-1 overflow-y-auto">
            <div class="px-6"> {{-- Diberi p-6 agar ada jarak --}}
                 @if (session('success'))
                    <div class="mb-4 rounded-lg border border-green-400 bg-green-100 px-4 py-3 text-green-700" role="alert">
                        <strong class="font-bold">Sukses!</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">No. Transaksi</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Tanggal Pembayaran</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Pelanggan</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">No. Meja</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Kasir</th>
                                <th class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900">Total</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            {{-- DIUBAH: Menggunakan variabel $laporanTransaksis dari controller --}}
                            @forelse ($laporanTransaksis as $transaksi)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $transaksi->nomor_transaksi }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $transaksi->tanggal_pembayaran?->format('d M Y, H:i') }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $transaksi->pelanggan->nama ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $transaksi->no_table ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $transaksi->user->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-center">
                                        <x-button size="sm" variant="outline" 
                                            @click="selectedTransaction = {{ Js::from($transaksi) }}; showDetailModal = true">
                                            Detail
                                        </x-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">Belum ada laporan transaksi yang lunas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="my-2 flex justify-center">
            {{ $laporanTransaksis->links('vendor.pagination.custom') }}
        </div>
        
        {{-- Pastikan path ini benar sesuai struktur folder Anda --}}
        {{-- Anda bisa menggunakan modal yang sama dengan book-order jika isinya identik --}}
        @include('pages.reports.partials.detail-modal')
    </div>
</x-layout>