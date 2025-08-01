<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; }
        .content { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice</h2>
        <p>Order Number: <strong>{{ $order->order_number }}</strong></p>
    </div>

    <div class="content">
        <p><strong>Customer:</strong> {{ $order->user->name }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('d F Y') }}</p>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->quantity * $detail->product->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h3>
    </div>
</body>
</html>
