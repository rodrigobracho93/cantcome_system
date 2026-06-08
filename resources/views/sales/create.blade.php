<x-app-layout>
    <x-slot name="header">Nueva Venta</x-slot>

    <div class="max-w-4xl">
        <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
            @csrf

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Step 1: Customer --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4">
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-7 h-7 bg-indigo-600 text-white rounded-full text-xs font-bold">1</span>
                    <h3 class="text-lg font-semibold text-gray-900">Datos del Cliente</h3>
                </div>

                <label class="flex items-center gap-2 mb-4 text-sm text-gray-600">
                    <input type="checkbox" id="existingCustomer" onchange="toggleCustomerFields()" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    Cliente existente
                </label>

                <div id="existingCustomerFields" class="hidden mb-4">
                    <x-input-label for="customer_search" value="Buscar cliente" />
                    <x-text-input id="customer_search" type="text" class="mt-1 block w-full" placeholder="Nombre, cédula o empresa..." oninput="searchCustomers(this.value)" />
                    <div id="customerResults" class="mt-2"></div>
                    <select id="customer_id" name="customer_id" class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 hidden" onchange="fillCustomer(this)">
                        <option value="">Seleccionar cliente</option>
                    </select>
                </div>

                <div id="newCustomerFields" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="customer_first_name" value="Nombre *" />
                        <x-text-input id="customer_first_name" name="customer_first_name" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="customer_last_name" value="Apellido *" />
                        <x-text-input id="customer_last_name" name="customer_last_name" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="customer_document" value="Cédula" />
                        <x-text-input id="customer_document" name="customer_document" type="text" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="customer_company" value="Empresa" />
                        <x-text-input id="customer_company" name="customer_company" type="text" class="mt-1 block w-full" />
                    </div>
                </div>
            </div>

            {{-- Step 2: Payment --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4">
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-7 h-7 bg-indigo-600 text-white rounded-full text-xs font-bold">2</span>
                    <h3 class="text-lg font-semibold text-gray-900">Tipo de Pago</h3>
                </div>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition-colors has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500">
                        <input type="radio" name="payment_type" value="contado" checked class="text-indigo-600 focus:ring-indigo-500">
                        <span class="font-medium text-gray-700">Contado</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition-colors has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500">
                        <input type="radio" name="payment_type" value="credito" class="text-indigo-600 focus:ring-indigo-500">
                        <span class="font-medium text-gray-700">Crédito</span>
                    </label>
                </div>
            </div>

            {{-- Step 3: Products --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4">
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-7 h-7 bg-indigo-600 text-white rounded-full text-xs font-bold">3</span>
                    <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
                </div>

                <div id="productsContainer">
                    <div class="product-row grid grid-cols-12 gap-3 mb-3 items-end">
                        <div class="col-span-6">
                            <x-input-label value="Producto" />
                            <select name="items[0][product_id]" class="product-select mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Seleccionar...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                        {{ $product->name }} - ₲ {{ number_format($product->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <x-input-label value="Cantidad" />
                            <x-text-input type="number" name="items[0][quantity]" class="quantity-input mt-1 block w-full" min="1" value="1" required />
                        </div>
                        <div class="col-span-2">
                            <x-input-label value="Subtotal" />
                            <p class="item-subtotal mt-1 text-sm font-semibold text-indigo-600">$0.00</p>
                        </div>
                        <div class="col-span-1 flex items-end pb-1">
                            <button type="button" onclick="removeProduct(this)" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addProduct()" class="mt-2 inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Agregar otro producto
                </button>

                <div class="border-t border-gray-200 mt-4 pt-4 flex justify-end">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Subtotal: $<span id="subtotalAmount">0.00</span></p>
                        <p class="text-sm text-gray-500">IVA (16%): $<span id="taxAmount">0.00</span></p>
                        <p class="text-lg font-bold text-gray-900 mt-1">Total: $<span id="totalAmount">0.00</span></p>
                    </div>
                </div>
            </div>

            {{-- Step 4: Notes --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-7 h-7 bg-gray-400 text-white rounded-full text-xs font-bold">4</span>
                    <h3 class="text-lg font-semibold text-gray-900">Notas (opcional)</h3>
                </div>
                <textarea name="notes" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="2" placeholder="Notas adicionales..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">Cancelar</a>
                <button type="submit" onclick="return validateStock()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Registrar Venta
                </button>
            </div>
        </form>
    </div>

    <script>
        let productIndex = 1;

        function toggleCustomerFields() {
            const checked = document.getElementById('existingCustomer').checked;
            document.getElementById('existingCustomerFields').classList.toggle('hidden', !checked);
            document.getElementById('newCustomerFields').classList.toggle('hidden', checked);
            document.getElementById('customer_first_name').required = !checked;
            document.getElementById('customer_last_name').required = !checked;
            if (!checked) document.getElementById('customer_id').classList.add('hidden');
        }

        let searchTimeout;
        function searchCustomers(query) {
            clearTimeout(searchTimeout);
            if (query.length < 2) return;
            searchTimeout = setTimeout(() => {
                fetch(`/customers/search?q=${encodeURIComponent(query)}`)
                    .then(r => r.json())
                    .then(data => {
                        const container = document.getElementById('customerResults');
                        const select = document.getElementById('customer_id');
                        select.innerHTML = '<option value="">Seleccionar cliente</option>';
                        if (data.length === 0) {
                            container.innerHTML = '<p class="text-sm text-gray-500 mt-1">Sin resultados</p>';
                            return;
                        }
                        container.innerHTML = '';
                        data.forEach(c => {
                            select.innerHTML += `<option value="${c.id}">${c.first_name} ${c.last_name} - ${c.document || 'N/A'}</option>`;
                        });
                        select.classList.remove('hidden');
                    });
            }, 300);
        }

        function fillCustomer(select) {
            if (select.value) {
                document.getElementById('customer_first_name').value = select.options[select.selectedIndex].text.split(' - ')[0];
            }
        }

        function addProduct() {
            const container = document.getElementById('productsContainer');
            const template = container.querySelector('.product-row').cloneNode(true);
            template.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${productIndex}]`);
                el.value = '';
            });
            template.querySelector('.quantity-input').value = 1;
            template.querySelector('.item-subtotal').textContent = '$0.00';
            container.appendChild(template);
            productIndex++;
            updateTotals();
        }

        function removeProduct(btn) {
            if (document.querySelectorAll('.product-row').length > 1) {
                btn.closest('.product-row').remove();
                updateTotals();
            }
        }

        document.addEventListener('change', updateTotals);
        document.addEventListener('input', updateTotals);

        function updateTotals() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const select = row.querySelector('.product-select');
                const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(select.options[select.selectedIndex]?.dataset?.price || 0);
                const subtotal = price * qty;
                row.querySelector('.item-subtotal').textContent = `$${subtotal.toFixed(2)}`;
                total += subtotal;
            });
            const tax = total * 0.16;
            document.getElementById('subtotalAmount').textContent = total.toFixed(2);
            document.getElementById('taxAmount').textContent = tax.toFixed(2);
            document.getElementById('totalAmount').textContent = (total + tax).toFixed(2);
        }

        function validateStock() {
            let valid = true;
            document.querySelectorAll('.product-row').forEach(row => {
                const select = row.querySelector('.product-select');
                const qty = parseInt(row.querySelector('.quantity-input').value) || 0;
                const stock = parseInt(select.options[select.selectedIndex]?.dataset?.stock || 0);
                if (qty > stock) {
                    alert(`Stock insuficiente. Disponible: ${stock}`);
                    valid = false;
                }
            });
            return valid;
        }
    </script>
</x-app-layout>
