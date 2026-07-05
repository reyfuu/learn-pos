<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-white">Dashboard</h1>
                <p class="text-sm text-slate-400 mt-0.5">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            </div>
            <a href="{{ route('reports.pdf') }}" class="btn-primary text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <!-- Revenue Card -->
                <div class="stat-card group">
                    <div class="stat-card-accent bg-gradient-to-b from-emerald-400 to-emerald-600"></div>
                    <div class="flex justify-between items-start pl-3">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Revenue Hari Ini</p>
                            <p class="text-3xl font-bold text-white">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-2.5 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Transactions Card -->
                <div class="stat-card group">
                    <div class="stat-card-accent bg-gradient-to-b from-brand-400 to-brand-600"></div>
                    <div class="flex justify-between items-start pl-3">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Total Transaksi</p>
                            <p class="text-3xl font-bold text-white">{{ $totalTransactions }}</p>
                        </div>
                        <div class="p-2.5 bg-brand-500/10 rounded-xl border border-brand-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="stat-card group">
                    <div class="stat-card-accent bg-gradient-to-b from-violet-400 to-violet-600"></div>
                    <div class="flex justify-between items-start pl-3">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Total Produk</p>
                            <p class="text-3xl font-bold text-white">{{ $totalProducts }}</p>
                        </div>
                        <div class="p-2.5 bg-violet-500/10 rounded-xl border border-violet-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base font-semibold text-white">Transaksi Terbaru</h2>
                    <span class="badge-brand">5 terakhir</span>
                </div>
                <div class="overflow-x-auto -mx-6 -mb-6">
                    <table class="table-container">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th pl-6">Invoice</th>
                                <th class="table-th">Tanggal</th>
                                <th class="table-th">Item</th>
                                <th class="table-th pr-6 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @forelse($recentTransactions as $transaction)
                            <tr class="table-row">
                                <td class="table-td pl-6 font-mono font-medium text-brand-300">{{ $transaction->invoice_number }}</td>
                                <td class="table-td text-slate-400">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                <td class="table-td">
                                    <span class="badge-brand">{{ $transaction->details->sum('quantity') }} item</span>
                                </td>
                                <td class="table-td pr-6 text-right font-bold text-emerald-400">
                                    Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="table-td pl-6 text-center text-slate-500 py-10">
                                    Belum ada transaksi.
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
