<?php

namespace App\Exports\Sheets;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrdersPerMonthSheet implements FromArray, WithTitle
{
    public function array(): array
    {
        $data = Order::all()->groupBy(fn($o) => Carbon::parse($o->created_at)->format('F Y'))
            ->map(fn($group) => $group->count());

        $rows = [["Month", "Total Orders"]];
        foreach ($data as $month => $count) {
            $rows[] = [$month, $count];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Orders Per Month';
    }
}
