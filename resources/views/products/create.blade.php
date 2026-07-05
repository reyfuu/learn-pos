<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}" class="text-slate-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-white">Tambah Produk Baru</h1>
                <p class="text-sm text-slate-400 mt-0.5">Isi form di bawah untuk mendaftarkan produk baru</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card">
                <form action="{{ route('products.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" name="name" id="name" required
                               class="form-input" placeholder="Contoh: Burger Paket"
                               value="{{ old('name') }}">
                        @error('name') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="price" class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" id="price" required min="0"
                               class="form-input" placeholder="Contoh: 45000"
                               value="{{ old('price') }}">
                        @error('price') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="icon" class="form-label">Icon (Emoji)</label>
                        <input type="text" name="icon" id="icon"
                               class="form-input" placeholder="Contoh: 🍔"
                               value="{{ old('icon', '📦') }}">
                        <p class="form-hint">Salin emoji dari keyboard atau internet: 🍔 🍟 🥤 🍦 🍗 ☕</p>
                        @error('icon') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Produk
                        </button>
                        <a href="{{ route('products.index') }}" class="btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
