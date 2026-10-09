<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Menu Restoran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Anti-slop solid color theme: no gradients */
        :root {
            --bg-canvas: #09090b;
            --surface-card: #18181b;
            --border-line: #27272a;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans pb-28">

    <!-- Top Navigation Header -->
    <header class="border-b border-zinc-800 bg-zinc-900/90 backdrop-blur sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center font-bold text-white text-sm">PM</span>
                <div>
                    <h1 class="text-sm font-semibold tracking-tight text-zinc-100">PesanMakan Express</h1>
                    <p class="text-xs text-zinc-400">Sistem Pemesanan Restoran LSP</p>
                </div>
            </div>
            <a href="{{ route('login') }}" class="text-xs text-zinc-400 hover:text-white border border-zinc-700 px-3 py-1.5 rounded-md hover:bg-zinc-800 transition">
                Portal Admin
            </a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-emerald-950/60 border border-emerald-800 text-emerald-200 text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <span class="text-xs bg-emerald-900 px-2 py-0.5 rounded text-emerald-100">Berhasil</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-rose-950/60 border border-rose-800 text-rose-200 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-rose-950/60 border border-rose-800 text-rose-200 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="orderForm" action="{{ route('customer.checkout') }}" method="POST">
            @csrf

            <!-- 1. Customer Information Section -->
            <section class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 mb-8">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-3 mb-4">
                    <h2 class="text-sm font-semibold text-zinc-200 uppercase tracking-wider">1. Data Pelanggan</h2>
                    <span class="text-xs text-zinc-500">Wajib diisi sebelum konfirmasi</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_name" class="block text-xs font-medium text-zinc-400 mb-1">Nama Lengkap Pemesan</label>
                        <input type="text" id="customer_name" name="customer_name" required placeholder="Contoh: Bari Achmad" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2.5 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-zinc-500 transition">
                    </div>
                    <div>
                        <label for="table_number" class="block text-xs font-medium text-zinc-400 mb-1">Nomor Meja</label>
                        <input type="text" id="table_number" name="table_number" required placeholder="Contoh: 07" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2.5 text-sm text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-zinc-500 transition">
                    </div>
                </div>
            </section>

            <!-- 2. Menu Catalog Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-zinc-100">2. Pilih Menu Pesanan</h2>
                    <p class="text-xs text-zinc-400">Gunakan tombol tambah dan kurang untuk mengatur porsi</p>
                </div>

                <!-- Category Filters -->
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="filterCat('all', this)" class="cat-pill active-pill px-3.5 py-1.5 rounded-lg text-xs font-medium border border-zinc-700 bg-zinc-100 text-zinc-900 transition">Semua</button>
                    <button type="button" onclick="filterCat('Makanan', this)" class="cat-pill px-3.5 py-1.5 rounded-lg text-xs font-medium border border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-zinc-200 transition">Makanan</button>
                    <button type="button" onclick="filterCat('Minuman', this)" class="cat-pill px-3.5 py-1.5 rounded-lg text-xs font-medium border border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-zinc-200 transition">Minuman</button>
                    <button type="button" onclick="filterCat('Cemilan', this)" class="cat-pill px-3.5 py-1.5 rounded-lg text-xs font-medium border border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-zinc-200 transition">Cemilan</button>
                </div>
            </div>

            <!-- Menu Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($foods as $food)
                    <article class="food-item bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden flex flex-col justify-between" data-category="{{ $food->category }}">
                        <div>
                            @if($food->image)
                                <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-full h-44 object-cover">
                            @else
                                <div class="w-full h-44 bg-zinc-950 border-b border-zinc-800 flex items-center justify-center text-zinc-600 text-xs">
                                    Foto belum diunggah
                                </div>
                            @endif

                            <div class="p-4">
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <span class="text-xs px-2 py-0.5 rounded bg-zinc-800 border border-zinc-700 text-zinc-300">{{ $food->category }}</span>
                                    <span class="text-sm font-bold text-emerald-400">Rp {{ number_format($food->price, 0, ',', '.') }}</span>
                                </div>
                                <h3 class="text-sm font-semibold text-zinc-100 item-title">{{ $food->name }}</h3>
                                <p class="text-xs text-zinc-400 mt-1 line-clamp-2 leading-relaxed">{{ $food->description ?? 'Pilihan lezat khas restoran.' }}</p>
                            </div>
                        </div>

                        <!-- Stepper Controls -->
                        <div class="p-4 border-t border-zinc-800/80 bg-zinc-950/40 flex items-center justify-between">
                            <span class="text-xs text-zinc-400 font-medium">Jumlah Porsi</span>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="changeQty('{{ $food->id }}', -1)" class="w-8 h-8 rounded-lg bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 text-zinc-200 flex items-center justify-center text-sm font-bold transition">−</button>
                                <input type="number" id="qty_{{ $food->id }}" name="items[{{ $food->id }}]" min="0" value="0" readonly
                                       data-name="{{ $food->name }}" data-price="{{ $food->price }}"
                                       class="item-quantity w-12 text-center bg-transparent text-sm font-semibold text-zinc-100 focus:outline-none">
                                <button type="button" onclick="changeQty('{{ $food->id }}', 1)" class="w-8 h-8 rounded-lg bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 text-zinc-200 flex items-center justify-center text-sm font-bold transition">+</button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-16 text-center border border-dashed border-zinc-800 rounded-xl">
                        <p class="text-sm text-zinc-400">Belum ada menu makanan tersedia.</p>
                        <p class="text-xs text-zinc-600 mt-1">Jalankan seeder atau tambah menu melalui portal admin.</p>
                    </div>
                @endforelse
            </div>

            <!-- Floating Bottom Summary Bar -->
            <div id="floatingBar" class="fixed bottom-0 inset-x-0 bg-zinc-900 border-t border-zinc-800 py-3.5 px-4 z-40">
                <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-zinc-400 block">Total Estimasi (<span id="totalItemCount">0</span> item)</span>
                        <span id="grandTotalText" class="text-base sm:text-lg font-bold text-emerald-400">Rp 0</span>
                    </div>
                    <button type="button" onclick="openConfirmationModal()" class="bg-zinc-100 hover:bg-white text-zinc-950 font-semibold px-5 py-2.5 rounded-lg text-xs sm:text-sm tracking-wide transition shadow">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </div>

            <!-- Confirmation Modal (Inside Form for Native Submit) -->
            <div id="confirmModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4">
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl max-w-md w-full p-6 shadow-2xl">
                    <div class="flex items-center justify-between border-b border-zinc-800 pb-3 mb-4">
                        <h3 class="text-sm font-semibold text-zinc-100">Ringkasan Konfirmasi Pesanan</h3>
                        <button type="button" onclick="closeConfirmationModal()" class="text-zinc-500 hover:text-zinc-300 text-lg">✕</button>
                    </div>

                    <div class="space-y-2 text-xs text-zinc-400 mb-4 bg-zinc-950 p-3 rounded-lg border border-zinc-800">
                        <div class="flex justify-between">
                            <span>Nama Pemesan:</span>
                            <span id="modalCustomerName" class="font-semibold text-zinc-200"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Nomor Meja:</span>
                            <span id="modalTableNumber" class="font-semibold text-zinc-200"></span>
                        </div>
                    </div>

                    <div class="border-t border-b border-zinc-800 py-3 mb-4 max-h-48 overflow-y-auto">
                        <span class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider block mb-2">Item yang Dipilih</span>
                        <ul id="modalItemList" class="space-y-2 text-xs"></ul>
                    </div>

                    <div class="flex items-center justify-between py-2 mb-6">
                        <span class="text-xs text-zinc-400">Total Tagihan:</span>
                        <span id="modalGrandTotal" class="text-base font-bold text-emerald-400">Rp 0</span>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeConfirmationModal()" class="w-1/2 py-2.5 rounded-lg border border-zinc-700 bg-zinc-800 text-zinc-300 text-xs font-medium hover:bg-zinc-700 transition">
                            Periksa Kembali
                        </button>
                        <button type="submit" class="w-1/2 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold tracking-wide transition">
                            Ya, Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
        function filterCat(cat, btn) {
            document.querySelectorAll('.cat-pill').forEach(p => {
                p.className = 'cat-pill px-3.5 py-1.5 rounded-lg text-xs font-medium border border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-zinc-200 transition';
            });
            btn.className = 'cat-pill px-3.5 py-1.5 rounded-lg text-xs font-medium border border-zinc-700 bg-zinc-100 text-zinc-900 transition';

            document.querySelectorAll('.food-item').forEach(card => {
                const itemCat = card.getAttribute('data-category');
                card.style.display = (cat === 'all' || itemCat === cat) ? 'flex' : 'none';
            });
        }

        function changeQty(id, delta) {
            const input = document.getElementById('qty_' + id);
            let current = parseInt(input.value) || 0;
            current = Math.max(0, current + delta);
            input.value = current;
            recalculateSummary();
        }

        function recalculateSummary() {
            let totalItems = 0;
            let totalPrice = 0;

            document.querySelectorAll('.item-quantity').forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    totalItems += qty;
                    const price = parseFloat(input.getAttribute('data-price')) || 0;
                    totalPrice += (qty * price);
                }
            });

            document.getElementById('totalItemCount').textContent = totalItems;
            document.getElementById('grandTotalText').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        }

        function openConfirmationModal() {
            const name = document.getElementById('customer_name').value.trim();
            const table = document.getElementById('table_number').value.trim();

            if (!name || !table) {
                alert('Silakan isi Nama Lengkap dan Nomor Meja terlebih dahulu.');
                return;
            }

            let orderItems = [];
            let grandTotal = 0;

            document.querySelectorAll('.item-quantity').forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    const itemName = input.getAttribute('data-name');
                    const price = parseFloat(input.getAttribute('data-price')) || 0;
                    const subtotal = qty * price;
                    grandTotal += subtotal;
                    orderItems.push({ name: itemName, qty, subtotal });
                }
            });

            if (orderItems.length === 0) {
                alert('Silakan pilih minimal satu menu makanan dengan porsi lebih dari nol.');
                return;
            }

            document.getElementById('modalCustomerName').textContent = name;
            document.getElementById('modalTableNumber').textContent = table;

            const listElem = document.getElementById('modalItemList');
            listElem.innerHTML = '';
            orderItems.forEach(item => {
                const li = document.createElement('li');
                li.className = 'flex items-center justify-between text-zinc-300';
                li.innerHTML = `<span>${item.name} <span class="text-zinc-500">x${item.qty}</span></span><span class="font-medium text-zinc-200">Rp ${item.subtotal.toLocaleString('id-ID')}</span>`;
                listElem.appendChild(li);
            });

            document.getElementById('modalGrandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');

            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmationModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
