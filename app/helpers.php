<?php
if (!function_exists('getStatusColor')) {
    function getStatusColor($status)
    {
        return match ($status) {
            // 'pending' => 'bg-red-500 text-white',
            // 'di-proses' => 'bg-yellow-500 text-white',
            // 'pelunasan' => 'bg-blue-500 text-white',
            // 'pendistribusian' => 'bg-purple-500 text-white',
            // 'selesai' => 'bg-green-500 text-white',
            // 'di-batalkan' => 'bg-gray-500 text-white',
            'pending'      => ['bg-yellow-100','Menunggu DP'],
            'paid_dp'      => ['bg-blue-100','DP Diverifikasi'],
            'pending_full' => ['bg-yellow-100','Menunggu Pelunasan'],
            'pending_po'   => ['bg-yellow-100','Menunggu Verif PO'],
            'approved'     => ['bg-green-100','PO Disetujui'],
            'paid'         => ['bg-green-100','Lunas'],
            'failed'       => ['bg-red-100','Gagal'],
            default => 'bg-gray-500 text-white',
        };
    }
}
