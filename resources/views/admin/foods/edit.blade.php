<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg text-zinc-100 tracking-tight">Edit Menu: {{ $food->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 shadow-sm">
            <form action="{{ route('foods.update', $food->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1">Nama Menu</label>
                    <input type="text" name="name" value="{{ old('name', $food->name) }}" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-sm text-zinc-100 focus:outline-none focus:border-zinc-500">
                    @error('name') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1">Kategori</label>
                    <select name="category" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-sm text-zinc-100 focus:outline-none focus:border-zinc-500">
                        <option value="Makanan" {{ old('category', $food->category) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="Minuman" {{ old('category', $food->category) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                        <option value="Cemilan" {{ old('category', $food->category) == 'Cemilan' ? 'selected' : '' }}>Cemilan</option>
                    </select>
                    @error('category') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1">Harga Satuan (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $food->price) }}" required min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-sm text-zinc-100 focus:outline-none focus:border-zinc-500">
                    @error('price') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-sm text-zinc-100 focus:outline-none focus:border-zinc-500">{{ old('description', $food->description) }}</textarea>
                    @error('description') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1">Foto Saat Ini</label>
                    @if($food->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-20 h-20 rounded-lg object-cover border border-zinc-700">
                        </div>
                    @else
                        <p class="text-xs text-zinc-500 mb-2">Belum ada foto.</p>
                    @endif
                    <label class="block text-xs font-medium text-zinc-400 mb-1">Ganti Foto Baru (Opsional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700">
                    @error('image') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-800">
                    <a href="{{ route('foods.index') }}" class="px-4 py-2 rounded-lg border border-zinc-700 bg-zinc-800 text-zinc-300 text-xs font-medium hover:bg-zinc-700 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition">
                        Perbarui Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
