<x-layout>
    <div x-data="categoryPage({{ Js::from($errors->any()) }})" class="min-h-full max-h-full w-full flex flex-col">
        {{-- HEADER --}}
        <div class="h-[90px] w-full px-6 flex items-center gap-3 flex-shrink-0">
            <form method="GET" action="{{ route('categories.index') }}" class="flex-1">
                <x-search-button name="search" type="text" value="{{ request('search') }}" placeholder="Cari kategori..." />
            </form>
            <x-button intent="primary" class="w-max" @click="openAddModal">Tambah Kategori</x-button>
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

        {{-- TABLE --}}
        <div class="flex-1 overflow-y-auto px-6 pb-6">
            <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 font-medium text-gray-900 text-left">Nama Kategori</th>
                            <th class="px-4 py-2 font-medium text-gray-900 text-center">Status</th>
                            <th class="px-4 py-2 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($kategoris as $item)
                            <tr>
                                <td class="px-4 py-2 font-medium text-gray-900">{{ $item->nama }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if($item->status=='aktif')
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
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">Belum ada data kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $kategoris->withQueryString()->links('vendor.pagination.custom') }}</div>
        </div>

        {{-- Modal partials --}}
        @include('pages-admin.category.partial.modal-detail')
        @include('pages-admin.category.partial.modal-delete')
    </div>

    <script>
        function categoryPage(hasError){
            return {
                showModal: hasError,
                isEditMode:false,
                showDeleteModal:false,
                category:{id:null,nama:'',status:'aktif'},
                formAction:'',
                categoryToDelete:null,
                deleteFormAction:'',
                openAddModal(){
                    this.isEditMode=false;
                    this.category={id:null,nama:'',status:'aktif'};
                    this.formAction='{{ route('categories.store') }}';
                    this.showModal=true;
                },
                openEditModal(item){
                    this.isEditMode=true;
                    this.category=item;
                    this.formAction=`{{ url('categories') }}/${item.id}`;
                    this.showModal=true;
                },
                openDeleteModal(item){
                    this.categoryToDelete=item;
                    this.deleteFormAction=`{{ url('categories') }}/${item.id}`;
                    this.showDeleteModal=true;
                }
            }
        }
    </script>
</x-layout>