<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'monthly');

        // Chart data & summary based on period
        switch ($period) {
            case 'daily':
                // Last 30 days
                $chartData = Transaction::selectRaw("TO_CHAR(created_at, 'DD Mon') as label, SUM(grand_total) as total, COUNT(*) as count")
                    ->where('created_at', '>=', now()->subDays(29)->startOfDay())
                    ->groupByRaw("TO_CHAR(created_at, 'DD Mon'), DATE_TRUNC('day', created_at)")
                    ->orderByRaw("DATE_TRUNC('day', created_at)")
                    ->get();
                $periodLabel = '30 Hari Terakhir';
                break;

            case 'yearly':
                // Last 5 years
                $chartData = Transaction::selectRaw("TO_CHAR(created_at, 'YYYY') as label, SUM(grand_total) as total, COUNT(*) as count")
                    ->where('created_at', '>=', now()->subYears(5)->startOfYear())
                    ->groupByRaw("TO_CHAR(created_at, 'YYYY')")
                    ->orderByRaw("TO_CHAR(created_at, 'YYYY')")
                    ->get();
                $periodLabel = '5 Tahun Terakhir';
                break;

            default: // monthly
                // Last 12 months
                $chartData = Transaction::selectRaw("TO_CHAR(created_at, 'Mon YYYY') as label, SUM(grand_total) as total, COUNT(*) as count")
                    ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
                    ->groupByRaw("TO_CHAR(created_at, 'Mon YYYY'), DATE_TRUNC('month', created_at)")
                    ->orderByRaw("DATE_TRUNC('month', created_at)")
                    ->get();
                $periodLabel = '12 Bulan Terakhir';
                break;
        }

        $totalRevenue   = $chartData->sum('total');
        $totalTrx       = $chartData->sum('count');
        $avgRevenue     = $chartData->count() > 0 ? $totalRevenue / $chartData->count() : 0;

        return view('reports.index', compact('chartData', 'period', 'periodLabel', 'totalRevenue', 'totalTrx', 'avgRevenue'));
    }

    public function exportPdf(Request $request)
    {
        $period = $request->input('period', 'monthly');

        switch ($period) {
            case 'daily':
                $transactions = Transaction::with('details.product')
                    ->whereDate('created_at', today())
                    ->latest()->get();
                $periodLabel = 'Hari Ini (' . today()->format('d M Y') . ')';
                break;

            case 'yearly':
                $transactions = Transaction::with('details.product')
                    ->whereYear('created_at', now()->year)
                    ->latest()->get();
                $periodLabel = 'Tahun ' . now()->year;
                break;

            default: // monthly
                $transactions = Transaction::with('details.product')
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->latest()->get();
                $periodLabel = now()->translatedFormat('F Y');
                break;
        }

        $pdf = Pdf::loadView('reports.pdf', compact('transactions', 'periodLabel', 'period'));
        $filename = 'laporan-penjualan-' . $period . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
