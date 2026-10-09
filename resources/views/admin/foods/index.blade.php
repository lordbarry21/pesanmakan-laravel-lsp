<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-lg text-zinc-100 tracking-tight">Master Data Makanan</h2>
                <p class="text-xs text-zinc-400 mt-0.5">Kelola menu makanan, minuman, harga, dan foto</p>
            </div>
            <a href="{{ route('foods.create') }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold transition">
                + Tambah Menu Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-5 p-4 bg-emerald-950/60 border border-emerald-800 text-emerald-200 text-xs sm:text-sm rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-zinc-950/70 text-zinc-400 uppercase text-[11px] tracking-wider border-b border-zinc-800">
                            <tr>
                                <th class="p-3.5">Foto</th>
                                <th class="p-3.5">Nama Menu</th>
                                <th class="p-3.5">Kategori</th>
                                <th class="p-3.5">Harga</th>
                                <th class="p-3.5">Deskripsi</th>
                                <th class="p-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/80 text-zinc-300">
                            @forelse($foods as $food)
                                <tr class="hover:bg-zinc-800/40 transition">
                                    <td class="p-3.5">
                                        @if($food->image)
                                            <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-12 h-12 rounded-lg object-cover border border-zinc-700">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-zinc-950 border border-zinc-800 flex items-center justify-center text-[10px] text-zinc-600">
                                                No img
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3.5 font-medium text-zinc-100">{{ $food->name }}</td>
                                    <td class="p-3.5">
                                        <span class="px-2 py-0.5 rounded text-[11px] bg-zinc-800 text-zinc-300 border border-zinc-700">
                                            {{ $food->category }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 font-semibold text-emerald-400">Rp {{ number_format($food->price, 0, ',', '.') }}</td>
                                    <td class="p-3.5 text-zinc-400 max-w-xs truncate">{{ $food->description ?? '-' }}</td>
                                    <td class="p-3.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('foods.edit', $food->id) }}" class="px-2.5 py-1 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-medium border border-zinc-700 transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('foods.destroy', $food->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 rounded bg-rose-950/80 hover:bg-rose-900 text-rose-300 text-xs font-medium border border-rose-800 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-zinc-500">Belum ada data menu makanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($foods->hasPages())
                    <div class="p-4 border-t border-zinc-800">
                        {{ $foods->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
