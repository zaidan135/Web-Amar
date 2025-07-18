<x-layout>
    <div class="h-full w-full grid grid-rows-[1fr_1fr] bg-gray-50">

        
        {{-- BAGIAN ATAS: DIAGRAM PERFORMA --}}
        <div class="flex flex-col px-6 pt-4 overflow-hidden h-full">

            <div class="flex-1 grid grid-cols-3 gap-6">
                {{-- Kolom Kiri & Tengah: Chart --}}
                <div class="col-span-2 bg-black p-4 rounded-lg">
                    <canvas id="performanceChart"></canvas>
                </div>
                {{-- Kolom Kanan: Angka Statistik --}}
                <div class="col-span-1 flex flex-col gap-4 justify-center">
                    <div class="bg-black p-4 rounded-lg">
                        <p class="text-sm text-gray-400">Rata-rata Transaksi Harian</p>
                        <p class="text-2xl font-bold text-gray-500">{{ number_format($rataRataPenjualanHarian, 0, ',', '.') }} Transaksi</p>
                    </div>
                    <div class="bg-black p-4 rounded-lg">
                        <p class="text-sm text-gray-400">Total Transaksi Hari Ini</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($totalPenjualanHariIni, 0, ',', '.') }} Transaksi</p>
                    </div>
                    <div class="bg-black p-4 rounded-lg">
                        <p class="text-sm text-gray-400">Pesanan Saat Ini</p>
                        <p class="text-2xl font-bold text-gray-300">{{ $jumlahPesananSaatIni }} Pesanan</p>
                    </div>

                </div>
            </div>
        </div>
        
        {{-- BAGIAN BAWAH: DAFTAR ORDER BERJALAN --}}
        <div class="flex flex-col p-6 overflow-hidden">
            {{-- Kontainer dengan horizontal scroll --}}
            <div class="flex-1 flex items-center overflow-x-auto pb-4">

                <div class="flex flex-row gap-6">
                    @forelse ($orderBerjalan as $transaksi)
                        <div class="w-[290px] h-[250px] rounded-[10px] border bg-white border-[#E2E8F0] flex flex-col flex-shrink-0 shadow-sm">
                            <div class="w-full h-[60px] flex-shrink-0 border-b border-[#E2E8F0] flex items-center px-4 font-bold text-[14px]">
                                {{ $transaksi->nomor_transaksi }}
                            </div>
                            <div class="w-full flex-1 overflow-y-auto p-4 space-y-2">
                                @foreach($transaksi->details as $detail)
                                    <div class="w-full flex flex-row text-[12px]">
                                        <div class="flex-1 font-semibold">{{ $detail->produk->nama }}</div>
                                        <div class="flex-shrink-0 font-bold">x{{ $detail->kuantitas }}</div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="w-full h-[65px] flex-shrink-0 p-3">
                                {{-- Link ke book-order dengan query parameter 'highlight' --}}
                                <a href="{{ route('book-order.index', ['highlight' => $transaksi->id]) }}" class="w-full h-full bg-black rounded-[8px] text-white flex justify-center items-center text-[14px] hover:bg-gray-800">
                                    Lihat Detail
                                    <svg :class="open ? 'transform rotate-90' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="w-full text-center text-gray-500">Tidak ada order yang sedang berjalan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk menginisialisasi Chart.js --}}
    <script>
        const ctx = document.getElementById('performanceChart');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line', // Tipe diagram: garis
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: chartData.data,
                    fill: true,
                    borderColor: 'rgb(255, 255, 255)',
                    backgroundColor: 'rgba(255, 255, 255, 0.1)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: 'rgb(156, 163, 175)' }, // Warna angka di sumbu Y
                        grid: { color: 'rgba(255, 255, 255, 0.1)' } // Warna garis grid
                    },
                    x: {
                        ticks: { color: 'rgb(156, 163, 175)' }, // Warna label di sumbu X
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false } // Sembunyikan legenda
                }
            }
        });
    </script>
</x-layout>