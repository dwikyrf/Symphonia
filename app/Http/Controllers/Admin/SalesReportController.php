<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil status dari request, default 'all' jika tidak ada
        $status = $request->input('status', 'all'); 

        // Query untuk mengambil order dengan status tertentu atau semua status
        $query = Order::query();

        if ($status !== 'all') {
            $query->where('status', $status); // Filter berdasarkan status jika tidak 'all'
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('dashboard.sales.index', compact('orders'));
    }

    public function exportPdf(Request $request)
    {
        $status = $request->input('status', 'all');
        $query = Order::query();

        if ($status !== 'all') {
            $query->where('status', $status); // Filter berdasarkan status jika tidak 'all'
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $orders = $query->get();

        $pdf = Pdf::loadView('dashboard.sales.export-pdf', compact('orders'));
        return $pdf->download('sales-report.pdf');
    }

   public function exportExcel(Request $request)
    {
        return Excel::download(
            new SalesExport(
                $request->start_date,
                $request->end_date,
                $request->status
            ),
            'sales-report.xlsx'
        );
    }


}
