<?php

namespace App\Exports\Sheets;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderStatusSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return DB::table('orders')
            ->select('status', DB::raw('COUNT(*) as total_orders'))
            ->groupBy('status')
            ->get();
    }

    public function title(): string
    {
        return 'Order Status';
    }

    public function headings(): array
    {
        return ['Status', 'Total Orders'];
    }
}
