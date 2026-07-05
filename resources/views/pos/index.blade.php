<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-white">Point of Sale</h1>
        <p class="text-sm text-slate-400 mt-0.5">Pilih produk untuk ditambahkan ke keranjang</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Left: Product Grid -->
                <div class="w-full lg:w-2/3 card">
                    <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-5">Daftar Produk</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($products as $product)
                        <button type="button"
                            onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                            class="group bg-[#0b1120] hover:bg-brand-600/10 border border-[#334155] hover:border-brand-500/50
                                   rounded-2xl p-4 flex flex-col items-center text-center
                                   transition-all duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <div class="h-16 w-16 bg-[#273548] rounded-2xl mb-3 flex items-center justify-center text-3xl
                                        group-hover:scale-110 transition-transform duration-200 shadow">
                                {{ $product->icon ?? '📦' }}
                            </div>
                            <h4 class="font-semibold text-slate-200 text-sm leading-tight">{{ $product->name }}</h4>
                            <p class="text-brand-400 font-bold text-sm mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Right: Cart -->
                <div class="w-full lg:w-1/3 card flex flex-col min-h-[500px]">
                    <div class="flex items-center justify-between border-b border-[#334155] pb-4 mb-4">
                        <h2 class="text-base font-semibold text-white">Pesanan</h2>
                        <button onclick="clearCart()" class="text-xs text-slate-500 hover:text-red-400 transition-colors">Kosongkan</button>
                    </div>

                    <!-- Cart Items -->
                    <div id="cart-items" class="flex-grow overflow-y-auto space-y-2 mb-4">
                        <p id="empty-cart-msg" class="text-slate-500 text-center mt-14 text-sm">
                            Keranjang masih kosong.<br>
                            <span class="text-xs">Klik produk untuk menambahkan.</span>
                        </p>
                    </div>

                    <!-- Totals -->
                    <div class="border-t border-[#334155] pt-4 space-y-2.5">
                        <div class="flex justify-between text-sm text-slate-400">
                            <span>Subtotal</span>
                            <span id="subtotal-display" class="font-medium text-slate-200">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-400">
                            <span>Pajak (11%)</span>
                            <span id="tax-display" class="font-medium text-slate-200">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-white mt-1 pt-2 border-t border-[#334155]">
                            <span>Total</span>
                            <span id="total-display" class="text-emerald-400">Rp 0</span>
                        </div>

                        <button onclick="checkout()" id="checkout-btn"
                            class="w-full btn-primary justify-center mt-3 py-3 opacity-50 cursor-not-allowed" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Bayar Sekarang
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        let cart = {};
        const taxRate = 0.11;

        function addToCart(id, name, price) {
            if (cart[id]) {
                cart[id].quantity += 1;
            } else {
                cart[id] = { id, name, price, quantity: 1 };
            }
            updateCartUI();
        }

        function clearCart() {
            cart = {};
            updateCartUI();
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }

        function updateCartUI() {
            const cartContainer = document.getElementById('cart-items');
            const emptyMsg = document.getElementById('empty-cart-msg');
            const checkoutBtn = document.getElementById('checkout-btn');

            cartContainer.innerHTML = '';
            let subtotal = 0;

            const cartKeys = Object.keys(cart);
            if (cartKeys.length === 0) {
                cartContainer.appendChild(emptyMsg);
                emptyMsg.style.display = 'block';
                checkoutBtn.disabled = true;
                checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                emptyMsg.style.display = 'none';
                checkoutBtn.disabled = false;
                checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                cartKeys.forEach(key => {
                    const item = cart[key];
                    const itemSubtotal = item.price * item.quantity;
                    subtotal += itemSubtotal;

                    const itemEl = document.createElement('div');
                    itemEl.className = 'flex justify-between items-center bg-[#0b1120] px-3 py-2.5 rounded-xl border border-[#334155]';
                    itemEl.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1 bg-[#273548] rounded-lg px-2 py-1">
                                <button onclick="changeQty(${item.id}, -1)" class="text-slate-400 hover:text-white w-5 text-center leading-none text-lg font-bold transition-colors">−</button>
                                <span class="text-sm font-bold text-white w-5 text-center">${item.quantity}</span>
                                <button onclick="changeQty(${item.id}, 1)" class="text-slate-400 hover:text-white w-5 text-center leading-none text-lg font-bold transition-colors">+</button>
                            </div>
                            <div>
                                <h5 class="font-semibold text-sm text-slate-200 leading-tight">${item.name}</h5>
                                <p class="text-xs text-slate-500">${formatRupiah(item.price)} / item</p>
                            </div>
                        </div>
                        <span class="font-bold text-sm text-emerald-400 shrink-0">${formatRupiah(itemSubtotal)}</span>
                    `;
                    cartContainer.appendChild(itemEl);
                });
            }

            const tax = subtotal * taxRate;
            const grandTotal = subtotal + tax;

            document.getElementById('subtotal-display').innerText = formatRupiah(subtotal);
            document.getElementById('tax-display').innerText = formatRupiah(tax);
            document.getElementById('total-display').innerText = formatRupiah(grandTotal);

            window.cartTotals = { subtotal, tax, grandTotal };
        }

        function changeQty(id, delta) {
            if (!cart[id]) return;
            cart[id].quantity += delta;
            if (cart[id].quantity <= 0) delete cart[id];
            updateCartUI();
        }

        async function checkout() {
            if (Object.keys(cart).length === 0) return;

            const payload = {
                cart: Object.values(cart),
                subtotal: window.cartTotals.subtotal,
                tax: window.cartTotals.tax,
                grand_total: window.cartTotals.grandTotal,
                _token: '{{ csrf_token() }}'
            };

            try {
                const response = await fetch('{{ route("pos.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                if (data.success) {
                    alert('✅ Pembayaran berhasil!\nInvoice: ' + data.invoice_number);
                    cart = {};
                    updateCartUI();
                } else {
                    alert('Checkout gagal, coba lagi.');
                }
            } catch (error) {
                alert('Terjadi kesalahan saat checkout.');
            }
        }
    </script>
</x-app-layout>
