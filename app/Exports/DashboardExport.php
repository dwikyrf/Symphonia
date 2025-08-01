<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DashboardExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new Sheets\OrdersPerMonthSheet(),
            new Sheets\RevenuePerMonthSheet(),
            new Sheets\OrderStatusSheet(),
            new Sheets\ProvinceDistributionSheet(),
            new Sheets\TopProductsSheet(),
            new Sheets\RevenueByCategorySheet(),
        ];
    }
}
