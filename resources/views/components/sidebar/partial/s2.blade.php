        <ul class="space-y-4">
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                class="flex items-center gap-2 w-[216px] h-[40px] rounded-[10px] px-3 text-sm
                {{ request()->is('dashboard-auth*') ? 'bg-black text-white' : 'bg-slate-200 hover:bg-black text-gray-800 hover:text-white' }}">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}" 
                class="flex items-center gap-2 w-[216px] h-[40px] rounded-[10px] px-3 text-sm
                {{ request()->is('categories*') ? 'bg-black text-white' : 'bg-slate-200 hover:bg-black text-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-cubes"></i>
                    Category
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}" 
                class="flex items-center gap-2 w-[216px] h-[40px] rounded-[10px] px-3 text-sm
                {{ request()->is('products*') ? 'bg-black text-white' : 'bg-slate-200 hover:bg-black text-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-cube"></i>
                    Product
                </a>
            </li>
            <li>
                <a href="{{ route('admin.report') }}" 
                class="flex items-center gap-2 w-[216px] h-[40px] rounded-[10px] px-3 text-sm
                {{ request()->is('report-auth*') ? 'bg-black text-white' : 'bg-slate-200 hover:bg-black text-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-dollar-sign"></i>
                    Report
                </a>
            </li>
            <li>
                <a href="{{ route('account') }}" 
                class="flex items-center gap-2 w-[216px] h-[40px] rounded-[10px] px-3 text-sm
                {{ request()->is('account*') ? 'bg-black text-white' : 'bg-slate-200 hover:bg-black text-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-user"></i>
                    Account
                </a>
            </li>
        </ul>