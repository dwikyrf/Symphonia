<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 0; padding: 20px; color: #333; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header img { height: 60px; }
        .company-details { text-align: right; }
        h1 { font-size: 24px; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f8f8; }
        .total { font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #777; }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-logo">
            <img src="{{ public_path('img/Tampilan.png') }}" alt="Company Logo">
        </div>
        <div class="company-details">
            <strong>CV. Symphonia Haksa Kreasindo</strong><br>
            Jl. Contoh Alamat No.123<br>
            Bandung, Indonesia<br>
            Telepon: 0812-3456-7890
        </div>
    </div>

    <h1>Invoice</h1>

    <p><strong>Invoice Number:</strong> {{ $order->order_number }}</p>
    <p><strong>Invoice Date:</strong> {{ $order->created_at->format('d F Y') }}</p>
    <p><strong>Customer:</strong> {{ $order->user->name }}</p>
    <p><strong>Delivery Address:</strong> {{ $order->address?->fullAddress() ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->quantity * $detail->product->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="total">Total</td>
                <td class="total">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Thank you for your purchase! ❤️</p>
    </div>

</body>
</html>
