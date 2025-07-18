<x-layout>
  <div x-data="adminReportPage()" class="min-h-full max-h-full w-full flex flex-col">

    {{-- HEADER --}}
    <div class="h-[90px] w-full px-6 flex items-center gap-3 flex-shrink-0">

        <form id="filterForm" method="GET" action="{{ route('admin.report') }}"
              class="flex w-full items-center gap-3">

           {{-- search box --}}
           <x-search-button class="flex-1"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari transaksi, pelanggan, meja (meja xx/mejaxx)..."/>

           {{-- hidden input untuk range --}}
           <input type="hidden" name="range" id="rangeField" value="{{ request('range','today') }}">

           {{-- tombol range --}}
           @php($active = request('range','today'))
           @foreach(['today'=>'Harian','month'=>'Bulanan','year'=>'Tahunan'] as $key=>$label)
              <button
                type="button"
                @click="
                   document.getElementById('rangeField').value='{{ $key }}';
                   document.getElementById('filterForm').submit();
                "
                class="px-4 py-1.5 text-sm rounded-md transition
                       {{ $active===$key ? 'bg-black text-white'
                                         : 'bg-white text-gray-700 hover:bg-gray-200'}}">
                {{ $label }}
              </button>
           @endforeach

           {{-- tombol cetak --}}
           <a href="{{ route('admin.report.print', request()->query()) }}"
              target="_blank">
              <x-button intent="primary" class="w-[120px]">Cetak</x-button>
           </a>
        </form>

    </div>

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-3 gap-6 px-6 pb-4">
      @foreach([
            ['label' => 'Total Hari Ini',   'value' => $totalToday],
            ['label' => 'Total Bulan Ini',  'value' => $totalMonth],
            ['label' => 'Total Tahun Ini',  'value' => $totalYear],
          ] as $c)
        <div class="bg-black p-5 rounded-2xl text-white shadow">
          <p class="text-sm text-gray-400 mb-1">{{ $c['label'] }}</p>
          <p class="text-2xl font-semibold">
            Rp {{ number_format($c['value'], 0, ',', '.') }}
          </p>
        </div>
      @endforeach
    </div>


    {{-- TABLE --}}
    <div class="flex-1 overflow-y-auto px-6 pb-6">
      <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left font-medium text-gray-900">No. Transaksi</th>
              <th class="px-4 py-2 text-left font-medium text-gray-900">Tanggal Bayar</th>
              <th class="px-4 py-2 text-left font-medium text-gray-900">Pelanggan</th>
              <th class="px-4 py-2 text-left font-medium text-gray-900">No. Meja</th>
              <th class="px-4 py-2 text-left font-medium text-gray-900">Kasir</th>
              <th class="px-4 py-2 text-left font-medium text-gray-900">Total</th>
              <th class="px-4 py-2 text-right"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @forelse($reports as $trx)
              <tr>
                <td class="px-4 py-2 font-medium text-gray-900">{{ $trx->nomor_transaksi }}</td>
                <td class="px-4 py-2 text-gray-700">{{ $trx->tanggal_pembayaran?->format('d M Y, H:i') }}</td>
                <td class="px-4 py-2 text-gray-700">{{ $trx->pelanggan->nama ?? '-' }}</td>
                <td class="px-4 py-2 text-gray-700">{{ $trx->no_table ?? '-' }}</td>
                <td class="px-4 py-2 text-gray-700">{{ $trx->user->name }}</td>
                <td class="px-4 py-2 text-gray-700">Rp {{ number_format($trx->total_harga,0,',','.') }}</td>
                <td class="px-4 py-2 text-right">
                  <x-button size="sm" variant="outline" @click="selected= {{ Js::from($trx) }};showDetail=true">Detail</x-button>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="py-4 text-center text-gray-500">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-4">{{ $reports->withQueryString()->links('vendor.pagination.custom') }}</div>
    </div>

    @include('pages-admin.report.partial.detail-modal')
  </div>
  <script>
    function adminReportPage(){return{showDetail:false,selected:null}}
  </script>
</x-layout>