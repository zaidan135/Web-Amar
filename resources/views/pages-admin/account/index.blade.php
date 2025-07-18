<x-layout>
    {{-- STATE ALPINE --}}
    <div x-data="adminAccountPage()"
         class="max-h-full w-full flex flex-col bg-white">

        {{-- HEADER ---------------------------------------------------------- --}}
        <div class="h-[90px] w-full px-6 flex items-center justify-end gap-3 flex-shrink-0">
            <x-button intent="primary" class="w-[160px]" @click="openAdd">
                Tambah Akun
            </x-button>
        </div>

        {{-- NOTIFIKASI --}}
        <div class="px-6 -mt-2">
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-green-400 bg-green-100 px-4 py-3 text-green-700"
                    x-init="setTimeout(() => showAlert = false, 2000)">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                    <strong>Periksa kembali data!</strong>
                    <ul class="mt-1 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- LIST USER ------------------------------------------------------- --}}
        <div class="flex-1 overflow-y-auto px-6 pb-6">
            <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-900">Nama</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-900">Username</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-900">Email</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-900">Role</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-900"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $u)
                            <tr>
                                <td class="px-4 py-2">{{ $u->name }}</td>
                                <td class="px-4 py-2">{{ $u->username }}</td>
                                <td class="px-4 py-2">{{ $u->email }}</td>
                                <td class="px-4 py-2 capitalize">{{ $u->role }}</td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <x-button size="sm" variant="outline"
                                              @click="openEditModal({{ Js::from($u) }})">Edit</x-button>
                                    <x-button size="sm" intent="danger" variant="outline"
                                              @click="openDeleteModal({{ Js::from($u) }})">Hapus</x-button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-center text-gray-500">Belum ada akun.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $users->links('vendor.pagination.custom') }}</div>
        </div>

        {{-- ================= MODAL TAMBAH / EDIT =========================== --}}
        <template x-teleport="body">
            {{-- backdrop --}}
            <div x-show="showModal" x-transition.opacity
                 class="fixed inset-0 bg-black/30 z-10">
                <div x-show="showModal" x-transition
                     class="fixed inset-0 z-50 flex items-center justify-center">
                    <div @click.away="showModal=false"
                        class="bg-white w-full max-w-lg rounded-2xl p-6 shadow-xl space-y-6">
                        <h2 class="text-lg font-bold" x-text="isEdit ? 'Edit Akun' : 'Tambah Akun'"></h2>

                        <form :action="isEdit
                                        ? '{{ url('/account') }}/'+user.id
                                        : '{{ route('account') }}'"
                              method="POST" class="space-y-5">
                            @csrf
                            <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>

                            <div>
                                <label class="block text-sm font-medium mb-1">Nama</label>
                                <x-input-element name="name" x-model="user.name" placeholder="Nama" required/>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Username</label>
                                <x-input-element name="username" x-model="user.username" placeholder="Username" required/>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <x-input-element type="email" name="email" x-model="user.email" placeholder="Email" required/>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Role</label>
                                <select name="role" x-model="user.role"
                                        class="w-full border rounded-md px-3 py-2">
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Password
                                        <span x-show="isEdit" class="text-xs text-gray-400">(opsional)</span></label>
                                            <x-input-element type="password" name="password"
                                                x-model="user.password"
                                                placeholder="Password"
                                                x-bind:required="!isEdit"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Konfirmasi</label>
                                        <x-input-element type="password" name="password_confirmation"
                                                        x-model="user.password_confirmation"
                                                        placeholder="Konfirmasi Password"
                                                        x-bind:required="!isEdit"/>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 pt-2">
                                <x-button variant="outline" type="button"
                                          @click="showModal=false">Batal</x-button>
                                <x-button intent="primary" type="submit">Simpan</x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        {{-- ================= MODAL HAPUS ================================== --}}
        <template x-teleport="body">
            <div x-show="showDelete" x-transition.opacity
                 class="fixed inset-0 bg-black/30 z-40">
                <div x-show="showDelete" x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center">
                    <div @click.away="showDelete=false"
                            class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl space-y-6">
                        <h2 class="text-lg font-bold text-red-600">Hapus Akun?</h2>
                        <p>Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.</p>

                        <form method="POST" :action="'{{ url('account') }}/'+deleteId" class="flex justify-end gap-3">
                            @csrf @method('DELETE')
                            <x-button variant="outline" type="button" @click="showDelete=false">Batal</x-button>
                            <x-button intent="danger" type="submit">Hapus</x-button>
                        </form>
                    </div>
                </div>
            </div>
        </template>

    </div>

    {{-- SCRIPT ALPINE ====================================================== --}}
    <script>
function adminAccountPage(){
    return {
        showModal:false, showDelete:false, isEdit:false,
        user:{id:null,name:'',username:'',email:'',role:'kasir',password:'',password_confirmation:''},
        deleteId:null,

        resetUser(){
            this.user = {id:null,name:'',username:'',email:'',role:'kasir',password:'',password_confirmation:''};
        },
        /* ------------------------- */
        openAdd(){
            this.isEdit   = false;
            this.resetUser();
            this.showModal= true;
        },
        openEditModal(u){
            this.isEdit   = true;
            this.user     = {...u, password:'', password_confirmation:''};
            this.showModal= true;
        },
        openDeleteModal(u){
            this.deleteId = u.id;
            this.showDelete = true;
        }
    }
}

    </script>
</x-layout>
