<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Mail\OrderInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    // 📄 Menampilkan preview invoice di browser
    public function preview(Order $order)
    {
        return view('dashboard.invoices.preview', compact('order'));
    }

    // 📥 Download invoice sebagai PDF
    public function download(Order $order)
    {
        $pdf = Pdf::loadView('dashboard.invoices.pdf', compact('order'));
        return $pdf->download('Invoice-' . $order->order_number . '.pdf');
    }
    public function send(Order $order)
{
    if (!$order->user || !$order->user->email) {
        return back()->with('error', 'User does not have a valid email address.');
    }
        $folderPath = storage_path('app/invoices');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

    // Generate PDF
    $pdf = Pdf::loadView('dashboard.invoices.pdf', compact('order'));
    $pdfPath = storage_path('app/invoices/Invoice-' . $order->order_number . '.pdf');
    $pdf->save($pdfPath);

    // Kirim email dengan PDF terlampir
    Mail::to($order->user->email)->send(new OrderInvoiceMail($order, $pdfPath));

    return back()->with('success', 'Invoice email has been sent successfully!');
}

}
