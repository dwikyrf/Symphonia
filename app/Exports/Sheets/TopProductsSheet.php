<?php

namespace App\Exports\Sheets;

use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TopProductsSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return OrderDetail::select('product_id', \DB::raw('SUM(quantity) as total_ordered'))
            ->groupBy('product_id')
            ->with('product:id,name')
            ->get()
            ->map(function ($item) {
                return [
                    'product' => $item->product->name ?? 'Unknown',
                    'total_ordered' => $item->total_ordered
                ];
            });
    }

    public function title(): string
    {
        return 'Top Products';
    }

    public function headings(): array
    {
        return ['Product', 'Total Ordered'];
    }
}
