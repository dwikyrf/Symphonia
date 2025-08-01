<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenuePerMonthSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        $data = DB::table('orders')
            ->selectRaw("strftime('%Y-%m', created_at) as month, SUM(total_price) as total_revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $data->map(function ($item) {
            return [
                'month' => $item->month,
                'total_revenue' => 'Rp ' . number_format($item->total_revenue, 0, ',', '.'),
            ];
        });
    }

    public function title(): string
    {
        return 'Revenue Per Month';
    }

    public function headings(): array
    {
        return ['Month', 'Total Revenue (Rp)'];
    }
}
