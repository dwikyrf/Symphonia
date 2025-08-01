<?php

namespace App\Exports\Sheets;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProvinceDistributionSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return DB::table('orders')
            ->join('addresses', 'orders.address_id', '=', 'addresses.id')
            ->select('addresses.province', DB::raw('COUNT(orders.id) as total_orders'))
            ->groupBy('addresses.province')
            ->orderByDesc('total_orders')
            ->get();
    }

    public function title(): string
    {
        return 'Top Provinces';
    }

    public function headings(): array
    {
        return ['Province', 'Total Orders'];
    }
}
