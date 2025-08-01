<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
{
    return Order::with('user')->get()->map(function ($order) {
        return [
            'Order Number'    => $order->order_number,
            'User Name'       => $order->user->name ?? 'N/A',
            'Total Price' => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
            'Payment Status'  => $order->payment_status,
            'Order Status'    => $order->status,
            'Created At'      => $order->created_at->format('Y-m-d H:i'),
        ];
    });
}


    public function headings(): array
    {
        return [
            'Order Number',
            'User ID',
            'Total Price',
            'Payment Status',
            'Order Status',
            'Created At',
        ];
    }
}
