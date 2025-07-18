<aside class="w-64 h-screen bg-neutral-50 border-r-[0.50px] border-slate-200 flex flex-col">
    <div class="h-[74px] text-xl font-bold border-b border-gray-200 flex justify-center items-center bagian1">
        Kedai Amar
    </div>

    {{-- Navigasi Dinamis Berdasarkan Peran (Role) --}}
    <nav class="flex justify-center py-[17px] bagian2">
        {{-- @auth directive memastikan kode ini hanya berjalan jika user sudah login --}}
        @auth
            {{-- Periksa apakah user yang login adalah admin --}}
            @if (auth()->user()->isAdmin())
                @include('components.sidebar.partial.s2')

            {{-- Jika bukan admin, periksa apakah dia kasir --}}
            @elseif (auth()->user()->isKasir())
                @include('components.sidebar.partial.s1')
                
            @endif
        @endauth
    </nav>

</aside>