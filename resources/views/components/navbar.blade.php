<header class="bg-white px-6 flex justify-between items-center border-b border-gray-200 h-[74px]">
    <div class="text-sm font-bold text-gray-800">
        {{ $pageTitle }}
    </div>
    <div class="flex items-center space-x-3">
        @auth
        <div class="flex flex-col items-end">
            {{-- Menampilkan nama user yang sedang login --}}
            <div class="text-center justify-center text-black text-base font-bold leading-normal">{{ auth()->user()->name }}</div>
            {{-- Menampilkan role user yang sedang login --}}
            <div class="text-center justify-center text-black text-xs font-normal leading-tight">{{ ucfirst(auth()->user()->role) }}</div>
        </div>
        <div x-data="{ isOpen: false }" class="relative">
            <!-- 2. Jadikan avatar sebagai tombol untuk membuka/menutup dropdown -->
            <button @click="isOpen = !isOpen" 
                    class="block rounded-full transition-transform duration-200 ease-in-out hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <img class="w-10 h-10 rounded-full" 
                     src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E8E8E8&color=000" 
                     alt="Avatar"/>
            </button>

            <!-- 3. Konten Dropdown Menu -->
            <div x-show="isOpen"
                 @click.outside="isOpen = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-gray-200 ring-opacity-5 focus:outline-none z-50"
                 style="display: none;">
                
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <!-- Link Pengaturan Printer -->
                    <a href="{{ route('print.setting') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                        <i class="fa-solid fa-print w-4 text-center"></i>
                        <span>Setting Printer</span>
                    </a>
                    
                    <!-- Tambahan: Link Logout (Sangat Direkomendasikan) -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                           <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                           <span>Logout</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </div>
</header>