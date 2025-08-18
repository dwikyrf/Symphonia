<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithMapping, WithHeadings
{
    protected $start, $end, $status;

    public function __construct($start = null, $end = null, $status = 'all')
{
    $this->start = $start;
    $this->end = $end;
    $this->status = $status;
}
    public function collection()
    {
        return Order::query()
            ->when($this->start, fn ($q) => $q->whereDate('created_at', '>=', $this->start))
            ->when($this->end,   fn ($q) => $q->whereDate('created_at', '<=', $this->end))
            ->when(
                filled($this->status) && $this->status !== 'all',   // ⬅️   kunci
                fn ($q) => $q->where('status', $this->status)
            )
            ->select('order_number', 'created_at', 'total_price', 'status')
            ->get();
    }
    public function map($order): array
    {
        return [
            $order->order_number,
            Carbon::parse($order->created_at)->format('d M Y'),
            'Rp ' . number_format($order->total_price, 0, ',', '.'),
            ucfirst($order->status)
        ];
    }

    public function headings(): array
    {
        return ['Order Number', 'Tanggal', 'Total Harga', 'Status'];
    }
}
