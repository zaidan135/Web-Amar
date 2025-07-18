<x-layout>
    <div x-data="orderPage()" class="min-h-full max-h-full w-full flex flex-col">
        <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="cart" :value="JSON.stringify(cart)">
            <input type="hidden" name="customer_details" :value="JSON.stringify(customer)">
        </form>

        <div class="h-[90px] w-full px-6 flex items-center flex-shrink-0">
            <x-search-button placeholder="Cari kategori atau produk..." x-model="search" />
        </div>

        @if (session('success'))
            <div x-data="{ showAlert: true }" x-show="showAlert"
                    x-init="setTimeout(() => showAlert = false, 2000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed top-5 inset-x-0 mx-6 max-w-full sm:max-w-md sm:mx-auto z-50">
                <div class="relative w-full rounded-lg border border-green-400 bg-green-100 px-4 pr-12 py-3 text-green-700 shadow-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <strong class="font-bold">Sukses! </strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                    <button @click="showAlert = false" type="button" class="absolute top-2 right-2 text-xl leading-none text-green-700 hover:text-green-900">&times;</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ showAlert: true }" x-show="showAlert"
                x-init="setTimeout(() => showAlert = false, 2000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed top-5 inset-x-0 mx-6 max-w-full sm:max-w-md sm:mx-auto z-50">
                <div class="relative w-full rounded-lg border border-red-400 bg-red-100 px-4 pr-12 py-3 text-red-700 shadow-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <div>
                            <strong class="font-bold">Gagal! </strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    </div>
                    <button @click="showAlert = false" type="button"
                            class="absolute top-2 right-2 text-xl leading-none text-red-700 hover:text-red-900">&times;
                    </button>
                </div>
            </div>
        @endif

        
        <div class="flex-1 overflow-y-auto px-6 pb-4">
            <div class="w-full flex flex-col gap-5">
                @foreach ($kategoris as $kategori)
                    <div x-data="{ open: true }" x-show="shouldShowCategory('{{ strtolower($kategori->nama) }}', {{ Js::from($kategori->produks->pluck('nama')->map(fn($n) => strtolower($n))->toArray()) }})">
                        <button @click="open = !open" type="button" class="w-full h-[60px] rounded-lg bg-[#E8E8E8] flex items-center p-3 text-[14px] font-bold text-left">
                            <div class="flex-1">{{ $kategori->nama }}</div>
                            <svg :class="open ? 'transform rotate-90' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="mt-4 space-y-4">
                            @forelse ($kategori->produks as $produk)
                                <div x-show="shouldShowProduct('{{ strtolower($produk->nama) }}', '{{ strtolower($kategori->nama) }}')" class="w-full h-[60px] rounded-lg border-2 border-[#E8E8E8] flex items-center p-3 text-[14px]">
                                    <div class="flex-1">
                                        <p class="font-bold">{{ $produk->nama }}</p>
                                        <p class="text-xs text-gray-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex-shrink-0 flex items-center">
                                        <button :disabled="getCartItemQuantity({{ $produk->id }}) === 0"
                                                @click="removeFromCart({{ $produk->id }})"
                                                :class="getCartItemQuantity({{ $produk->id }}) === 0 ? 'cursor-not-allowed' : 'bg-black text-white hover:bg-gray-800 cursor-pointer'"
                                                class="w-[30px] h-[30px] border border-[#E8E8E8] flex items-center justify-center rounded-lg transition" type="button">-
                                        </button>
                                        <div class="w-[30px] h-[30px] flex items-center justify-center rounded-lg font-bold" x-text="getCartItemQuantity({{ $produk->id }})">0</div>
                                        <button @click="addToCart({{ Js::from($produk) }})" class="w-[30px] h-[30px] border border-black bg-black text-white flex items-center justify-center rounded-lg cursor-pointer" type="button">+</button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 px-3">Tidak ada produk di kategori ini.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="h-[74px] w-full px-10 flex items-center flex-shrink-0 justify-end border-t border-[#E8E8E8]">
            <div>
                <x-button @click.prevent="showModal = true" type="button" intent="primary" class="w-full" x-bind:disabled="cart.length === 0">Next</x-button>
            </div>
        </div>

        @include('pages.orders.partials.poporder')
    </div>

    <script>
        function orderPage() {
            return {
                showModal: false,
                cart: [],
                customer: { nama_pelanggan: '', telepon: '', email: '', no_table: '' },
                search: '',

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cart.push({ id: product.id, nama: product.nama, harga: product.harga, quantity: 1, catatan: '' });
                    }
                },
                removeFromCart(productId) {
                    const existingItem = this.cart.find(item => item.id === productId);
                    if (existingItem) {
                        existingItem.quantity--;
                        if (existingItem.quantity === 0) {
                            this.cart = this.cart.filter(item => item.id !== productId);
                        }
                    }
                },
                getCartItemQuantity(productId) {
                    const item = this.cart.find(i => i.id === productId);
                    return item ? item.quantity : 0;
                },
                shouldShowProduct(productName, categoryName) {
                    if (!this.search) return true;
                    const keyword = this.search.toLowerCase();
                    return productName.includes(keyword) || categoryName.includes(keyword);
                },
                shouldShowCategory(categoryName, productNames) {
                    if (!this.search) return true;
                    const keyword = this.search.toLowerCase();
                    return categoryName.includes(keyword) || productNames.some(n => n.includes(keyword));
                },
                get totalItems() { return this.cart.reduce((acc, item) => acc + item.quantity, 0); },
                get subtotal() { return this.cart.reduce((acc, item) => acc + (item.harga * item.quantity), 0); },
                get pajak() { return this.subtotal * 0.11; },
                get total() { return this.subtotal + this.pajak; },
                formatUang(nominal) { return new Intl.NumberFormat('id-ID').format(nominal); }
            }
        }
    </script>
</x-layout>