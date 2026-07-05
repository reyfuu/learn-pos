<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="text-xl font-bold text-white">Laporan Penjualan</h1>

            <!-- Controls: Period + Export in one row -->
            <div class="flex items-center gap-2">
                <!-- Period Switcher -->
                <div class="flex bg-[#0b1120] border border-[#334155] rounded-xl p-1 gap-1">
                    @foreach(['daily' => 'Harian', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'] as $key => $label)
                    <a href="{{ route('reports.index', ['period' => $key]) }}"
                       class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all duration-150
                              {{ $period === $key
                                 ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/40'
                                 : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>

                <!-- Export PDF -->
                <a href="{{ route('reports.pdf', ['period' => $period]) }}" class="btn-primary text-sm py-2" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <!-- ── Horizontal Stat Bar ─────────────────────── -->
            <div class="card py-4 px-6">
                <div class="flex flex-wrap items-center divide-x divide-[#334155] gap-y-3">
                    <!-- Period label -->
                    <div class="pr-6">
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Periode</p>
                        <p class="text-sm font-bold text-brand-400 mt-0.5">{{ $periodLabel }}</p>
                    </div>

                    <!-- Total Revenue -->
                    <div class="px-6">
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Total Pendapatan</p>
                        <p class="text-lg font-bold text-emerald-400 mt-0.5">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>

                    <!-- Total Transaksi -->
                    <div class="px-6">
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Transaksi</p>
                        <p class="text-lg font-bold text-white mt-0.5">{{ $totalTrx }}</p>
                    </div>

                    <!-- Rata-rata -->
                    <div class="px-6">
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Rata-rata</p>
                        <p class="text-lg font-bold text-violet-400 mt-0.5">Rp {{ number_format($avgRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- ── Chart ──────────────────────────────────── -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-slate-300">Grafik Penjualan</h2>
                    <span class="text-xs text-slate-500">{{ $periodLabel }}</span>
                </div>

                @if($chartData->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm">Belum ada data untuk periode ini.</p>
                </div>
                @else
                <div class="relative" style="height: 340px;">
                    <canvas id="salesChart"></canvas>
                </div>
                @endif
            </div>

            <!-- ── Data Table ─────────────────────────────── -->
            @if($chartData->isNotEmpty())
            <div class="card overflow-hidden p-0">
                <div class="px-6 py-3.5 border-b border-[#334155] flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-300">Rincian Data</h2>
                    <span class="text-xs text-slate-500">{{ $chartData->count() }} baris</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-container">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th px-6 py-3">Periode</th>
                                <th class="table-th px-6 py-3 text-center">Transaksi</th>
                                <th class="table-th px-6 py-3 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @foreach($chartData as $row)
                            <tr class="table-row">
                                <td class="table-td px-6 py-3 font-medium text-slate-200">{{ $row->label }}</td>
                                <td class="table-td px-6 py-3 text-center">
                                    <span class="badge-brand">{{ $row->count }}</span>
                                </td>
                                <td class="table-td px-6 py-3 text-right font-bold text-emerald-400">
                                    Rp {{ number_format($row->total, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t border-[#334155] bg-[#273548]">
                            <tr>
                                <td class="table-td px-6 py-3 font-bold text-slate-200">Total</td>
                                <td class="table-td px-6 py-3 text-center font-bold text-brand-400">{{ $totalTrx }}</td>
                                <td class="table-td px-6 py-3 text-right font-bold text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>

    @if($chartData->isNotEmpty())
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const labels      = @json($chartData->pluck('label'));
        const revenueData = @json($chartData->pluck('total'));
        const trxData     = @json($chartData->pluck('count'));

        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Pendapatan (Rp)',
                        data: revenueData,
                        backgroundColor: 'rgba(99, 102, 241, 0.65)',
                        borderColor: '#6366f1',
                        borderWidth: 1.5,
                        borderRadius: 5,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Transaksi',
                        data: trxData,
                        type: 'line',
                        yAxisID: 'y1',
                        borderColor: '#34d399',
                        backgroundColor: 'rgba(52, 211, 153, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: '#34d399',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        labels: { color: '#94a3b8', font: { family: 'Inter', size: 12 }, boxWidth: 10, boxHeight: 10, padding: 16 }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        borderColor: '#334155',
                        borderWidth: 1,
                        titleColor: '#f1f5f9',
                        bodyColor: '#94a3b8',
                        padding: 10,
                        callbacks: {
                            label: ctx => ctx.datasetIndex === 0
                                ? ' Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y)
                                : ' ' + ctx.parsed.y + ' transaksi'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(51,65,85,0.4)' },
                        ticks: { color: '#64748b', font: { family: 'Inter', size: 11 } }
                    },
                    y: {
                        type: 'linear', position: 'left',
                        grid: { color: 'rgba(51,65,85,0.4)' },
                        ticks: {
                            color: '#94a3b8',
                            font: { family: 'Inter', size: 11 },
                            callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                        }
                    },
                    y1: {
                        type: 'linear', position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { color: '#34d399', font: { family: 'Inter', size: 11 }, stepSize: 1 }
                    }
                }
            }
        });
    </script>
    @endif
</x-app-layout>
