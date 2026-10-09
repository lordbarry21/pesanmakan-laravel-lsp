<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-lg text-zinc-100 tracking-tight">
                    Rekap Pesanan Masuk
                </h2>
                <p class="text-xs text-zinc-400 mt-0.5">Pemantauan transaksi dan pembaharuan status secara langsung</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('foods.index') }}" class="inline-flex items-center px-3.5 py-1.5 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-lg text-xs font-semibold text-zinc-200 transition">
                    Kelola Master Menu
                </a>
                <a href="{{ route('customer.index') }}" target="_blank" class="inline-flex items-center px-3.5 py-1.5 bg-zinc-100 hover:bg-white text-zinc-950 rounded-lg text-xs font-semibold transition">
                    Buka Halaman Customer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-5 p-4 bg-emerald-950/60 border border-emerald-800 text-emerald-200 text-xs sm:text-sm rounded-lg flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <span class="text-xs bg-emerald-900 px-2 py-0.5 rounded text-emerald-100">Status Tersimpan</span>
                </div>
            @endif

            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-zinc-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-zinc-200">Daftar Transaksi Aktif</h3>
                    <span class="text-xs text-zinc-500">Total: {{ $orders->count() }} pesanan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-zinc-950/70 text-zinc-400 uppercase text-[11px] tracking-wider border-b border-zinc-800">
                            <tr>
                                <th class="p-3.5">ID</th>
                                <th class="p-3.5">Pelanggan</th>
                                <th class="p-3.5">Meja</th>
                                <th class="p-3.5">Rincian Item</th>
                                <th class="p-3.5">Total Harga</th>
                                <th class="p-3.5">Status Saat Ini</th>
                                <th class="p-3.5 text-center">Ubah Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/80 text-zinc-300">
                            @forelse($orders as $order)
                                <tr class="hover:bg-zinc-800/40 transition">
                                    <td class="p-3.5 font-mono text-zinc-400">#{{ $order->id }}</td>
                                    <td class="p-3.5 font-medium text-zinc-100">{{ $order->customer_name }}</td>
                                    <td class="p-3.5">
                                        <span class="bg-zinc-800 text-zinc-300 font-medium px-2 py-0.5 rounded border border-zinc-700">
                                            Meja {{ $order->table_number }}
                                        </span>
                                    </td>
                                    <td class="p-3.5">
                                        <ul class="space-y-1">
                                            @foreach($order->orderDetails as $detail)
                                                <li class="text-zinc-300 flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-600"></span>
                                                    <span>{{ $detail->food->name ?? 'Menu Dihapus' }}</span>
                                                    <span class="text-zinc-500 text-[11px]">x{{ $detail->quantity }}</span>
                                                    <span class="text-zinc-400 text-[11px]">(Rp {{ number_format($detail->subtotal, 0, ',', '.') }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="p-3.5 font-semibold text-emerald-400">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3.5">
                                        @php
                                            $st = strtolower($order->status);
                                        @endphp
                                        @if($st === 'pending')
                                            <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-amber-950/80 text-amber-300 border border-amber-800">Pending</span>
                                        @elseif($st === 'diproses')
                                            <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-sky-950/80 text-sky-300 border border-sky-800">Diproses</span>
                                        @elseif($st === 'selesai' || $st === 'completed')
                                            <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-950/80 text-emerald-300 border border-emerald-800">Selesai</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-rose-950/80 text-rose-300 border border-rose-800">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="bg-zinc-950 border border-zinc-700 text-zinc-200 text-xs rounded px-2.5 py-1 focus:outline-none focus:border-zinc-500">
                                                <option value="Pending" {{ strtolower($order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Diproses" {{ strtolower($order->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="Selesai" {{ strtolower($order->status) == 'selesai' || strtolower($order->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                                                <option value="Batal" {{ strtolower($order->status) == 'batal' || strtolower($order->status) == 'cancelled' ? 'selected' : '' }}>Batal</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-zinc-500">Belum ada pesanan masuk saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
