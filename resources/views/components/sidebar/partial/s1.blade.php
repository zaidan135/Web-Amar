        <ul class="space-y-4">
            <li>
                <a href="{{ route('dashboard') }}" 
                class="flex items-center gap-2 w-[216px] h-[40px] rounded-[10px] px-3 text-sm
                {{ request()->is('dashboard*') ? 'bg-black text-white' : 'bg-slate-200 hover:bg-black text-gray-800 hover:text-white' }}">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('orders.index') }}" 
                class="flex items-center gap-2 w-[216px] h-[40px] rounded-[10px] px-3 text-sm
                {{ request()->is('orders*') ? 'bg-black text-white' : 'bg-slate-200 hover:bg-black text-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Orders
                </a>
            </li>
            <li>
                <a href="{{ route('book-order.index') }}" 
                class="flex items-center gap-2 w-[216px] h-[40px] rounded-[10px] px-3 text-sm
                {{ request()->is('book-order*') ? 'bg-black text-white' : 'bg-slate-200 hover:bg-black text-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-book"></i>
                    Book Order
                </a>
            </li>
            <li>
                <a href="{{ route('reports.index') }}" 
                class="flex items-center gap-2 w-[216px] h-[40px] rounded-[10px] px-3 text-sm
                {{ request()->is('reports*') ? 'bg-black text-white' : 'bg-slate-200 hover:bg-black text-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-dollar-sign"></i>
                    Report
                </a>
            </li>
        </ul>