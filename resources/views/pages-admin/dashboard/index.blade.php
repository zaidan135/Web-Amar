<x-layout>
<div class="h-full w-full grid grid-rows-[auto_1fr]">
<div class="px-6 py-4 space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $cards = [
                ['label' => 'Rata-rata Penjualan Bulanan', 'value' => $rataRataPenjualanBulanan],
                ['label' => 'Total Penjualan Bulan Ini', 'value' => $penjualanBulanIni],
                ['label' => 'Rata-rata Penjualan Harian', 'value' => $rataRataPenjualanHarian],
                ['label' => 'Total Penjualan Hari Ini', 'value' => $totalPenjualanHariIni],
            ];
        @endphp
        @foreach($cards as $card)
            <div class="bg-black p-5 rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out">
                <p class="text-sm text-gray-400">{{ $card['label'] }}</p>
                <p class="text-xl font-bold text-white mt-2">Rp {{ number_format($card['value'], 0, ',', '.') }}</p>
            </div>
        @endforeach
    </div>

    {{-- Grafik dan Produk Terlaris Bulan Ini --}}  
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="col-span-2 bg-black p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Grafik Penjualan</h2>
                <select x-on:change="changeChart($event.target.value)" class="bg-gray-800 text-white border border-gray-600 rounded-md px-3 py-1.5 text-sm focus:ring focus:ring-gray-500">
                    <option value="harian">Harian</option>
                    <option value="bulanan">Bulanan</option>
                    <option value="tahunan">Tahunan</option>
                </select>
            </div>
            <div class="relative h-[350px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-black p-6 rounded-lg shadow-md text-white flex flex-col">
            <h3 class="text-xl font-bold mb-4">Top 10 Produk Bulan Ini</h3>
            <ul class="space-y-4 overflow-y-scroll max-h-[350px] pr-2" style="scrollbar-width: thin; scrollbar-color: #4B5563 transparent;">
                @forelse($topProdukBulan->take(10) as $produk)
                    <li class="flex items-center gap-4">
                        <span class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-800 text-gray-300 font-bold">{{ $loop->iteration }}</span>
                        <div>
                            <p class="font-semibold">{{ $produk->nama }}</p>
                            <p class="text-sm text-gray-400">Terjual {{ $produk->jumlah }}x</p>
                        </div>
                    </li>
                @empty
                    <li class="text-center text-gray-500 py-10">Belum ada penjualan bulan ini.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Insight Produk All Time & Tidak Laku --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-black p-6 rounded-lg shadow-md text-white flex flex-col">
            <h3 class="text-xl font-bold mb-4">Produk Terlaris (All Time)</h3>
            <ul class="space-y-4 overflow-y-scroll max-h-[350px] pr-2" style="scrollbar-width: thin; scrollbar-color: #4B5563 transparent;">
                @foreach($topProdukAllTime as $produk)
                    <li class="flex items-center gap-4">
                        <span class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-800 text-gray-300 font-bold">{{ $loop->iteration }}</span>
                        <div>
                            <p class="font-semibold">{{ $produk->nama }}</p>
                            <p class="text-sm text-gray-400">Terjual {{ $produk->jumlah }}x</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-black p-6 rounded-lg shadow-md text-white flex flex-col">
            <h3 class="text-xl font-bold mb-4">Produk Tidak Laku</h3>
            <ul class="space-y-4 max-h-[350px] pr-2 overflow-y-scroll">
                @foreach($produkTidakLaku as $produk)
                    <li class="flex items-center gap-4">
                        <span class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-800 text-gray-300 font-bold">{{ $loop->iteration }}</span>
                        <div>
                            <p class="font-semibold">{{ $produk->nama }}</p>
                            <p class="text-sm text-gray-400">Terjual {{ $produk->jumlah }}0x</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('salesChart').getContext('2d');
        let currentChart;
        const chartData = @json($chartData);
        function renderChart(type = 'harian') {
            const data = chartData[type];
            
            if (currentChart) {
                currentChart.destroy();
            }
            currentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Penjualan',
                        data: data.data,
                        fill: true,
                        borderColor: 'rgb(255, 255, 255)',
                        backgroundColor: 'rgba(255, 255, 255, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: 'rgb(156, 163, 175)' }, // text-gray-400
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        },
                        x: {
                            ticks: { color: 'rgb(156, 163, 175)' }, // text-gray-400
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
        window.changeChart = function(type) {
            renderChart(type);
        }
        renderChart();
    });
</script>
</x-layout>