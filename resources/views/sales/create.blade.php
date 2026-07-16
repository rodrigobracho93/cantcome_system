<x-app-layout>
    <x-slot name="header">Nueva Venta</x-slot>

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST" id="saleForm" class="lg:grid lg:grid-cols-5 lg:gap-6">
        @csrf

        {{-- Left: Product Browser --}}
        <div class="lg:col-span-3 space-y-4 mb-6 lg:mb-0">

            {{-- Customer Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 shrink-0">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Cliente</h3>
                    <span id="customerSelectedBadge" class="hidden ml-auto inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-full text-xs font-medium">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Seleccionado
                    </span>
                </div>

                <input type="hidden" name="customer_id" id="customer_id" value="">

                {{-- Search --}}
                <div class="relative mb-3">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="customerSearch" autocomplete="off"
                        class="w-full pl-9 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Buscar cliente por nombre, cédula, teléfono o empresa..."
                        oninput="searchCustomers(this.value)"
                        @if(!empty($customerId)) value="{{ $customers->find($customerId)?->name ?? '' }}" @endif>
                </div>

                {{-- Search results dropdown --}}
                <div id="customerResults" class="hidden mb-3 border border-gray-200 dark:border-gray-600 rounded-xl divide-y divide-gray-100 dark:divide-gray-700 max-h-48 overflow-y-auto shadow-sm"></div>

                {{-- Selected customer --}}
                <div id="selectedCustomer" class="hidden mb-3">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-200 dark:border-indigo-800">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-800 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-xs shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <p id="selectedCustomerName" class="text-sm font-semibold text-gray-900 dark:text-white"></p>
                            </div>
                            <button type="button" onclick="clearSelectedCustomer()" class="p-1 text-gray-400 hover:text-red-500 transition-colors shrink-0" title="Quitar cliente">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div id="selectedCustomerDetails" class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs text-gray-600 dark:text-gray-400"></div>
                    </div>
                </div>

                {{-- Create new customer toggle --}}
                <div class="flex items-center gap-2 mb-3">
                    <input type="checkbox" id="createNewCustomer" onchange="toggleNewCustomer()"
                        class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="createNewCustomer" class="text-sm text-gray-600 dark:text-gray-400 cursor-pointer">Crear cliente nuevo</label>
                </div>

                {{-- New customer fields --}}
                <div id="newCustomerFields" class="hidden grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-3">
                        <input type="text" name="customer_name" id="customer_name" placeholder="Nombre y Apellido *"
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <input type="text" name="customer_document" placeholder="Cédula o RUC (opcional)"
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <input type="text" name="customer_phone" placeholder="Teléfono (opcional)"
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <input type="text" name="customer_company" placeholder="Empresa (opcional)"
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            {{-- Product Browser --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 shrink-0">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Productos</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $products->count() }} disponibles</span>
                    <div class="ml-auto flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-0.5">
                        <button type="button" onclick="toggleProductView('cards')" id="viewCardsBtn" class="view-toggle-btn p-1.5 rounded-md transition-colors" title="Vista tarjetas">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </button>
                        <button type="button" onclick="toggleProductView('list')" id="viewListBtn" class="view-toggle-btn p-1.5 rounded-md transition-colors" title="Vista lista">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Search --}}
                <div class="relative mb-4">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="productSearch" placeholder="Buscar producto..." oninput="filterProducts(this.value)"
                        class="w-full pl-9 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Cards Grid --}}
                <div id="productGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-[420px] overflow-y-auto pr-1">
                    @foreach($products as $product)
                    <button type="button" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})"
                        class="product-card text-left p-3 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-150 group hover:shadow-sm"
                        data-name="{{ strtolower($product->name) }}" data-category="{{ strtolower($product->category?->name ?? '') }}">
                        <div class="flex items-center justify-center w-full h-14 rounded-lg bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/30 dark:to-indigo-800/30 mb-2 group-hover:from-indigo-100 group-hover:to-indigo-200 dark:group-hover:from-indigo-900/50 dark:group-hover:to-indigo-800/50 transition-colors">
                            <svg class="w-7 h-7 text-indigo-400 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate leading-tight">{{ $product->name }}</p>
                        <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mt-0.5">₲ {{ number_format($product->price, 0, ',', '.') }}</p>
                        <span class="inline-block mt-1 text-[10px] font-medium px-1.5 py-0.5 rounded {{ $product->stock > 10 ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : ($product->stock > 0 ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400') }}">
                            Stock: {{ $product->stock }}
                        </span>
                    </button>
                    @endforeach
                </div>

                {{-- List View --}}
                <div id="productList" class="hidden max-h-[420px] overflow-y-auto pr-1 space-y-1.5">
                    @foreach($products as $product)
                    <button type="button" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})"
                        class="product-card-list product-card w-full flex items-center gap-3 p-3 text-left rounded-xl border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-150 group"
                        data-name="{{ strtolower($product->name) }}" data-category="{{ strtolower($product->category?->name ?? '') }}">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/30 dark:to-indigo-800/30 group-hover:from-indigo-100 group-hover:to-indigo-200 dark:group-hover:from-indigo-900/50 dark:group-hover:to-indigo-800/50 transition-colors shrink-0">
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $product->name }}</p>
                            @if($product->category)
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">{{ $product->category->name }}</p>
                            @endif
                        </div>
                        <span class="text-xs font-medium px-1.5 py-0.5 rounded shrink-0 {{ $product->stock > 10 ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : ($product->stock > 0 ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400') }}">
                            {{ $product->stock }}
                        </span>
                        <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap shrink-0">₲ {{ number_format($product->price, 0, ',', '.') }}</p>
                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-indigo-500 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                    @endforeach
                </div>

                @if($products->isEmpty())
                <div class="text-center py-8 text-gray-400 dark:text-gray-500 text-sm">
                    No hay productos disponibles para la venta
                </div>
                @endif
            </div>

            {{-- Notes --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-700 shrink-0">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                    <input type="text" name="notes" placeholder="Notas adicionales (opcional)..."
                        class="flex-1 border-0 bg-transparent text-sm text-gray-700 dark:text-gray-300 placeholder-gray-400 focus:ring-0 p-0">
                </div>
            </div>
        </div>

        {{-- Right: Cart & Checkout --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sticky top-28">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 shrink-0">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Carrito</h3>
                    <span id="cartCount" class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2 py-0.5 rounded-full font-medium">0</span>
                </div>

                {{-- Payment Type --}}
                <div class="flex gap-2 mb-4">
                    <label class="flex-1 flex items-center justify-center gap-1.5 p-2.5 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/30 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500">
                        <input type="radio" name="payment_type" value="contado" checked class="sr-only">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Contado</span>
                    </label>
                    <label class="flex-1 flex items-center justify-center gap-1.5 p-2.5 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/30 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500">
                        <input type="radio" name="payment_type" value="credito" class="sr-only">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Crédito</span>
                    </label>
                </div>

                {{-- Cart Items --}}
                <div id="cartItems" class="space-y-2 min-h-[120px] max-h-[280px] overflow-y-auto mb-4">
                    <div id="emptyCart" class="text-center py-8 text-gray-400 dark:text-gray-500 text-sm">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        Seleccioná productos para vender
                    </div>
                </div>

                {{-- Hidden inputs for cart --}}
                <div id="hiddenInputs"></div>

                {{-- Totals --}}
                <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">₲ <span id="subtotalAmount">0</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">IVA (10%)</span>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">₲ <span id="taxAmount">0</span></span>
                    </div>
                    <div class="flex justify-between text-base pt-2 border-t border-gray-100 dark:border-gray-700">
                        <span class="font-bold text-gray-900 dark:text-white">Total</span>
                        <span class="font-bold text-indigo-600 dark:text-indigo-400 text-lg">₲ <span id="totalAmount">0</span></span>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" onclick="return prepareSubmit()" id="submitBtn" disabled
                    class="mt-5 w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors text-sm font-bold shadow-sm shadow-indigo-200 dark:shadow-none disabled:opacity-40 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Cobrar (₲ <span id="submitTotal">0</span>)
                </button>
            </div>
        </div>
    </form>

    <script>
        let cart = [];
        let productIndex = 0;

        function toggleProductView(mode) {
            const grid = document.getElementById('productGrid');
            const list = document.getElementById('productList');
            if (mode === 'list') {
                grid.classList.add('hidden');
                list.classList.remove('hidden');
            } else {
                list.classList.add('hidden');
                grid.classList.remove('hidden');
            }
            localStorage.setItem('productView', mode);
            updateToggleButtons(mode);
        }

        function updateToggleButtons(mode) {
            const cardsBtn = document.getElementById('viewCardsBtn');
            const listBtn = document.getElementById('viewListBtn');
            const activeClass = 'bg-white dark:bg-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm';
            const inactiveClass = 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300';
            cardsBtn.className = 'view-toggle-btn p-1.5 rounded-md transition-colors ' + (mode === 'cards' ? activeClass : inactiveClass);
            listBtn.className = 'view-toggle-btn p-1.5 rounded-md transition-colors ' + (mode === 'list' ? activeClass : inactiveClass);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const saved = localStorage.getItem('productView') || 'cards';
            toggleProductView(saved);
        });

        let searchTimeout;
        function searchCustomers(query) {
            clearTimeout(searchTimeout);
            const results = document.getElementById('customerResults');
            if (query.length < 2) { results.classList.add('hidden'); results.innerHTML = ''; return; }
            searchTimeout = setTimeout(() => {
                fetch(`/customers/search?q=${encodeURIComponent(query)}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.length === 0) {
                            results.innerHTML = '<div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">Sin resultados</div>';
                            results.classList.remove('hidden');
                            return;
                        }
                        results.innerHTML = '';
                        data.forEach(c => {
                            const doc = c.document ? `<span class="text-gray-400">${c.document}</span>` : '';
                            const phone = c.phone ? `<span class="text-gray-400 ml-2">📞 ${c.phone}</span>` : '';
                            const div = document.createElement('div');
                            div.className = 'px-3 py-2.5 text-sm hover:bg-indigo-50 dark:hover:bg-indigo-900/20 cursor-pointer transition-colors flex items-center justify-between';
                            div.innerHTML = `<div><span class="font-medium text-gray-900 dark:text-white">${c.name}</span> ${doc}</div><div class="text-xs text-gray-400">${phone}</div>`;
                            div.onclick = () => selectCustomer(c);
                            results.appendChild(div);
                        });
                        results.classList.remove('hidden');
                    });
            }, 300);
        }

        function selectCustomer(c) {
            document.getElementById('customer_id').value = c.id;
            document.getElementById('selectedCustomerName').textContent = c.name;
            const details = document.getElementById('selectedCustomerDetails');
            details.innerHTML = '';
            const fields = [
                { label: 'Cédula/RUC', value: c.document },
                { label: 'Teléfono', value: c.phone },
                { label: 'Empresa', value: c.company },
                { label: 'Email', value: c.email },
            ];
            fields.forEach(f => {
                if (f.value) {
                    const div = document.createElement('div');
                    div.className = 'flex flex-col';
                    div.innerHTML = `<span class="text-[10px] uppercase tracking-wider text-gray-400 dark:text-gray-500">${f.label}</span><span class="font-medium text-gray-700 dark:text-gray-300">${f.value}</span>`;
                    details.appendChild(div);
                }
            });
            document.getElementById('selectedCustomer').classList.remove('hidden');
            document.getElementById('customerSelectedBadge').classList.remove('hidden');
            document.getElementById('customerResults').classList.add('hidden');
            document.getElementById('customerResults').innerHTML = '';
            document.getElementById('customerSearch').value = c.name;
            document.getElementById('createNewCustomer').disabled = true;
            document.getElementById('createNewCustomer').checked = false;
            document.getElementById('newCustomerFields').classList.add('hidden');
            document.getElementById('customer_name').required = false;
            document.getElementById('customer_name').value = '';
            document.querySelectorAll('#newCustomerFields input').forEach(inp => { if (inp.name.startsWith('customer_')) inp.value = '' });
        }

        function clearSelectedCustomer() {
            document.getElementById('customer_id').value = '';
            document.getElementById('selectedCustomer').classList.add('hidden');
            document.getElementById('customerSelectedBadge').classList.add('hidden');
            document.getElementById('customerSearch').value = '';
            document.getElementById('createNewCustomer').disabled = false;
        }

        function toggleNewCustomer() {
            const checked = document.getElementById('createNewCustomer').checked;
            document.getElementById('newCustomerFields').classList.toggle('hidden', !checked);
            document.getElementById('customer_name').required = checked;
            if (checked) clearSelectedCustomer();
        }

        function filterProducts(query) {
            const q = query.toLowerCase().trim();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name || '';
                const cat = card.dataset.category || '';
                card.style.display = (!q || name.includes(q) || cat.includes(q)) ? '' : 'none';
            });
        }

        function addToCart(id, name, price, stock) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                if (existing.qty >= stock) {
                    alert(`Stock máximo alcanzado para ${name}: ${stock} unidades`);
                    return;
                }
                existing.qty++;
            } else {
                cart.push({ id, name, price, stock, qty: 1 });
            }
            renderCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function updateQty(index, delta) {
            const item = cart[index];
            const newQty = item.qty + delta;
            if (newQty < 1) { removeFromCart(index); return; }
            if (newQty > item.stock) {
                alert(`Stock máximo: ${item.stock} unidades`);
                return;
            }
            item.qty = newQty;
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cartItems');
            const empty = document.getElementById('emptyCart');
            const hidden = document.getElementById('hiddenInputs');
            const count = document.getElementById('cartCount');
            const submitBtn = document.getElementById('submitBtn');

            if (cart.length === 0) {
                empty.style.display = '';
                container.querySelectorAll('.cart-item').forEach(el => el.remove());
                count.textContent = '0';
                submitBtn.disabled = true;
                updateTotals();
                hidden.innerHTML = '';
                return;
            }

            empty.style.display = 'none';
            count.textContent = cart.reduce((sum, item) => sum + item.qty, 0);
            submitBtn.disabled = false;

            let html = '';
            let hiddenHtml = '';
            let idx = 0;

            cart.forEach((item, i) => {
                const subtotal = item.price * item.qty;
                html += `
                    <div class="cart-item flex items-center gap-2 p-2.5 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">${item.name}</p>
                            <p class="text-[10px] text-gray-400">₲ ${formatNumber(item.price)} c/u</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="updateQty(${i}, -1)" class="w-6 h-6 flex items-center justify-center rounded-md bg-white dark:bg-gray-600 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors text-sm font-bold border border-gray-200 dark:border-gray-600">-</button>
                            <span class="w-7 text-center text-xs font-bold text-gray-900 dark:text-white">${item.qty}</span>
                            <button type="button" onclick="updateQty(${i}, 1)" class="w-6 h-6 flex items-center justify-center rounded-md bg-white dark:bg-gray-600 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors text-sm font-bold border border-gray-200 dark:border-gray-600">+</button>
                        </div>
                        <div class="text-right min-w-[70px]">
                            <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400">₲ ${formatNumber(subtotal)}</p>
                        </div>
                        <button type="button" onclick="removeFromCart(${i})" class="p-1 text-gray-300 hover:text-red-500 transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;
                hiddenHtml += `<input type="hidden" name="items[${idx}][product_id]" value="${item.id}">`;
                hiddenHtml += `<input type="hidden" name="items[${idx}][quantity]" value="${item.qty}">`;
                idx++;
            });

            container.querySelectorAll('.cart-item').forEach(el => el.remove());
            container.insertAdjacentHTML('beforeend', html);
            hidden.innerHTML = hiddenHtml;
            productIndex = idx;
            updateTotals();
        }

        function updateTotals() {
            let total = 0;
            cart.forEach(item => { total += item.price * item.qty; });
            const tax = Math.round(total * 0.10);
            const grandTotal = total + tax;
            document.getElementById('subtotalAmount').textContent = formatNumber(total);
            document.getElementById('taxAmount').textContent = formatNumber(tax);
            document.getElementById('totalAmount').textContent = formatNumber(grandTotal);
            document.getElementById('submitTotal').textContent = formatNumber(grandTotal);
        }

        function prepareSubmit() {
            if (cart.length === 0) {
                alert('Agregá al menos un producto al carrito');
                return false;
            }
            for (const item of cart) {
                if (item.qty > item.stock) {
                    alert(`Stock insuficiente para ${item.name}. Disponible: ${item.stock}`);
                    return false;
                }
            }
            return true;
        }

        function formatNumber(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    </script>
</x-app-layout>