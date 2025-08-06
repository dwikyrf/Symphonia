<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DashboardExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function index(Request $request )
{
    $orders = Order::query()
        ->when($request->filled('start_date'), fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
        ->when($request->filled('end_date'), fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
        ->get();
    // $orders = Order::all();

    // Orders Per Month
    $ordersPerMonth = $orders->groupBy(function ($order) {
        return Carbon::parse($order->created_at)->locale('id')->translatedFormat('F');
    })->map->count()->toArray();

    // Revenue Per Month
    $revenuePerMonth = $orders->groupBy(function ($order) {
        return Carbon::parse($order->created_at)->locale('id')->translatedFormat('F');
    })->map(function ($group) {
        return $group->sum('total_price');
    })->toArray();

    // Order Status Distribution
    $orderStatusDistribution = $orders->groupBy('status')->map->count()->toArray();

    // Statistik Umum
    $totalProducts = OrderDetail::whereHas('order', function ($query) use ($request) {
    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }
    })->sum('quantity');

    $totalCustomers = User::where('role', 'user')
    ->when($request->filled('start_date'), fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
    ->when($request->filled('end_date'), fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
    ->count();
    $totalOrders = $orders->count();
    $totalRevenue = Transaction::where('payment_stage', 'full')
    ->where('is_verified_full', true)
    ->when($request->filled('start_date'), fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
    ->when($request->filled('end_date'), fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
    ->sum('total_payment');


//     // Province Distribution
//    $provinceOrders = DB::table('orders')
//     ->join('addresses', 'orders.address_id', '=', 'addresses.id')
//     ->when($request->filled('start_date'), function ($query) use ($request) {
//         $query->whereDate('orders.created_at', '>=', $request->start_date);
//     })
//     ->when($request->filled('end_date'), function ($query) use ($request) {
//         $query->whereDate('orders.created_at', '<=', $request->end_date);
//     })
//     ->select('addresses.province', DB::raw('count(orders.id) as total_orders'))
//     ->groupBy('addresses.province')
//     ->orderByDesc('total_orders')
//     ->pluck('total_orders', 'province');
// ===== Province Distribution by SHIPPING destination =====
$provinceOrders = DB::table('orders as o')
    ->join('addresses as a', 'o.address_id', '=', 'a.id')
    ->where('o.status', 'completed')
    ->when($request->filled('start_date'), fn ($q) =>
        $q->whereDate('o.created_at', '>=', $request->start_date)
    )
    ->when($request->filled('end_date'), fn ($q) =>
        $q->whereDate('o.created_at', '<=', $request->end_date)
    )
    ->select('a.province as province',                  // ⬅️ ganti ke kolom yang ada
             DB::raw('COUNT(o.id) as total_orders'))
    ->groupBy('a.province')
    ->orderByDesc('total_orders')
    ->pluck('total_orders', 'province');

    $topProvinces = $provinceOrders->take(5);
    $othersTotal = $provinceOrders->skip(5)->sum();

    if ($othersTotal > 0) {
        $topProvinces = $topProvinces->put('Etc', $othersTotal);
    }

    $topProvinces = $topProvinces->toArray();

    // Most Ordered Products
    $topProducts = OrderDetail::with('product', 'order')
    ->whereHas('order', function ($query) use ($request) {
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
    })
    ->select('product_id', DB::raw('SUM(quantity) as total'))
    ->groupBy('product_id')
    ->orderByDesc('total')
    ->get()
    ->take(5)
    ->mapWithKeys(fn ($item) => [$item->product->name => $item->total])
    ->toArray();


    // Revenue by Product Category
    $revenueByCategory = DB::table('order_details')
    ->join('orders', 'order_details.order_id', '=', 'orders.id')
    ->join('products', 'order_details.product_id', '=', 'products.id')
    ->join('categories', 'products.category_id', '=', 'categories.id')
    ->when($request->filled('start_date'), function ($query) use ($request) {
        $query->whereDate('orders.created_at', '>=', $request->start_date);
    })
    ->when($request->filled('end_date'), function ($query) use ($request) {
        $query->whereDate('orders.created_at', '<=', $request->end_date);
    })
    ->select('categories.name as category', DB::raw('SUM(order_details.quantity * products.price) as total_revenue'))
    ->groupBy('categories.name')
    ->orderByDesc('total_revenue')
    ->pluck('total_revenue', 'category')
    ->toArray();


    return view('dashboard.index', compact(
        'ordersPerMonth',
        'revenuePerMonth',
        'orderStatusDistribution',
        'totalProducts',
        'totalCustomers',
        'totalOrders',
        'totalRevenue',
        'topProvinces',
        'topProducts',
        'revenueByCategory'
    ));
}


    public function exportPdf()
{
    $totalProducts = OrderDetail::sum('quantity'); // produk terjual
    $totalOrders = Order::count();                 // semua order
    $totalRevenue = Transaction::where('payment_stage', 'full')
        ->where('is_verified_full', true)
        ->sum('total_payment');                    // total pembayaran
    $totalCustomers = User::where('role', 'user')->count(); // user pelanggan
    // Orders Per Month
    $ordersPerMonth = Order::selectRaw("strftime('%Y-%m', created_at) as month, COUNT(*) as total_orders")
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total_orders', 'month');

    // Revenue Per Month
    $revenuePerMonth = Order::selectRaw("strftime('%Y-%m', created_at) as month, SUM(total_price) as total_revenue")
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total_revenue', 'month');

    // Order Status Distribution
    $orderStatusDistribution = Order::selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    // Geographical Distribution (by Province)
    $topProvinces = DB::table('orders')
        ->join('addresses', 'orders.address_id', '=', 'addresses.id')
        ->select('addresses.province', DB::raw('count(orders.id) as total_orders'))
        ->groupBy('addresses.province')
        ->orderByDesc('total_orders')
        ->get()
        ->take(5)
        ->pluck('total_orders', 'province');

    // Most Ordered Products
    $topProducts = OrderDetail::select('product_id', DB::raw('SUM(quantity) as total'))
        ->groupBy('product_id')
        ->orderByDesc('total')
        ->with('product') // make sure 'product' relation is eager loaded
        ->get()
        ->take(5)
        ->mapWithKeys(fn ($item) => [$item->product->name => $item->total]); // ✅ ini benar

    // Revenue by Product Category
    $revenueByCategory = DB::table('order_details')
    ->join('products', 'order_details.product_id', '=', 'products.id')
    ->join('categories', 'products.category_id', '=', 'categories.id')
    ->select('categories.name as category', DB::raw('SUM(order_details.quantity * products.price) as total_revenue'))
    ->groupBy('categories.name')
    ->orderByDesc('total_revenue')
    ->pluck('total_revenue', 'category');


    // Load view with all variables
    $pdf = Pdf::loadView('dashboard.exports.statistic', compact(
        'ordersPerMonth',
        'revenuePerMonth',
        'orderStatusDistribution',
        'topProvinces',
        'topProducts',
        'revenueByCategory',
        'totalProducts',
        'totalCustomers',
        'totalOrders',
        'totalRevenue'
    ));

    return $pdf->download('dashboard-statistics.pdf');
}

    public function exportExcel()
    {
        return Excel::download(new DashboardExport, 'dashboard-statistics.xlsx');
    }
}
