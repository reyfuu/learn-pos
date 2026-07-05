<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-white">Manajemen Produk</h1>
                <p class="text-sm text-slate-400 mt-0.5">Kelola daftar produk yang tersedia di POS</p>
            </div>
            <a href="{{ route('products.create') }}" class="btn-primary text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if(session('success'))
            <div class="alert-success flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            <div class="card overflow-hidden p-0">
                <div class="overflow-x-auto">
                    <table class="table-container">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th px-6 py-4">Icon</th>
                                <th class="table-th px-6 py-4">Nama Produk</th>
                                <th class="table-th px-6 py-4">Harga</th>
                                <th class="table-th px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @forelse($products as $product)
                            <tr class="table-row">
                                <td class="table-td px-6 py-4 text-2xl">{{ $product->icon ?? '📦' }}</td>
                                <td class="table-td px-6 py-4 font-medium text-slate-100">{{ $product->name }}</td>
                                <td class="table-td px-6 py-4">
                                    <span class="badge-success">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </td>
                                <td class="table-td px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-4">
                                        <a href="{{ route('products.edit', $product) }}" class="btn-edit text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger text-sm" onclick="return confirm('Hapus produk ini?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="table-td px-6 py-16 text-center text-slate-500">
                                    <div class="text-4xl mb-3">📦</div>
                                    Belum ada produk.<br>
                                    <a href="{{ route('products.create') }}" class="text-brand-400 hover:text-brand-300 font-medium mt-1 inline-block">Tambah produk pertama →</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
