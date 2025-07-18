<x-layout>
    <div x-data="productPage({{ Js::from($errors->any()) }})" class="min-h-full max-h-full w-full flex flex-col">
        {{-- HEADER --}}
        <div class="h-[90px] w-full px-6 flex items-center gap-3 flex-shrink-0">
            <form method="GET" action="{{ route('products.index') }}" class="flex-1">
                <x-search-button name="search" type="text" value="{{ request('search') }}" placeholder="Cari produk atau kategori..." />
            </form>
            <x-button intent="primary" class="w-[140px]" @click="openAddModal">Tambah Produk</x-button>
        </div>

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div x-data="{show:true}" x-show="show" x-transition x-init="setTimeout(()=>show=false,2000)" class="fixed top-5 inset-x-0 mx-auto max-w-md z-50">
                <div class="relative rounded-lg border border-green-400 bg-green-100 px-4 pr-12 py-3 text-green-700 shadow-lg">
                    <strong>Sukses! </strong>{{ session('success') }}
                    <button @click="show=false" class="absolute top-2 right-2 leading-none">&times;</button>
                </div>
            </div>
        @endif

        {{-- TABEL PRODUK --}}
        <div class="flex-1 overflow-y-auto px-6 pb-6">
            <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-900">Nama Produk</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-900">Kategori</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-900">Harga</th>
                            <th class="px-4 py-2 text-center font-medium text-gray-900">Status</th>
                            <th class="px-4 py-2 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($produks as $item)
                            <tr>
                                <td class="px-4 py-2 font-medium text-gray-900">{{ $item->nama }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $item->kategori->nama ?? '-' }}</td>
                                <td class="px-4 py-2 text-gray-700">Rp {{ number_format($item->harga,0,',','.') }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if($item->status==='aktif')
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-emerald-700">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-gray-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <x-button size="sm" variant="outline" @click="openEditModal({{ Js::from($item) }})">Edit</x-button>
                                    <x-button size="sm" variant="outline" intent="danger" @click="openDeleteModal({{ Js::from($item) }})">Hapus</x-button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-center text-gray-500">Belum ada data produk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $produks->withQueryString()->links('vendor.pagination.custom') }}</div>
        </div>

        {{-- PARTIALS --}}
        @include('pages-admin.product.partial.modal-add-edit')
        @include('pages-admin.product.partial.modal-delete')
    </div>

    <script>
        function productPage(hasError){
            return {
                showAddEditModal:hasError,
                showDeleteModal:false,
                isEditMode:false,
                product:{id:null,nama:'',harga:'',status:'aktif',kategori_id:''},
                formAction:'',
                productToDelete:null,
                deleteFormAction:'',
                openAddModal(){
                    this.isEditMode=false;
                    this.product={id:null,nama:'',harga:'',status:'aktif',kategori_id:''};
                    this.formAction='{{ route('products.store') }}';
                    this.showAddEditModal=true;
                },
                openEditModal(item){
                    this.isEditMode=true;
                    this.product=item;
                    this.formAction=`{{ url('products') }}/${item.id}`;
                    this.showAddEditModal=true;
                },
                openDeleteModal(item){
                    this.productToDelete=item;
                    this.deleteFormAction=`{{ url('products') }}/${item.id}`;
                    this.showDeleteModal=true;
                }
            }
        }
    </script>
</x-layout>