<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h2>Invoice #{{ $order->order_number }}</h2>

    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</p>
    <p><strong>Nama Customer:</strong> {{ $order->user->name }}</p>
    <p><strong>Status Pembayaran:</strong> {{ ucfirst($order->payment_status) }}</p>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
</body>
</html>
