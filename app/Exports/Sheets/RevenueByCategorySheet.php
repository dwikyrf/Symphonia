<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueByCategorySheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        $data = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category', DB::raw('SUM(order_details.quantity * products.price) as total_revenue'))
            ->groupBy('categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Format revenue as Rupiah
        return $data->map(function ($item) {
            return [
                'category' => $item->category,
                'total_revenue' => 'Rp ' . number_format($item->total_revenue, 0, ',', '.'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Category', 'Total Revenue (Rp)'];
    }

    public function title(): string
    {
        return 'Revenue by Category';
    }
}
