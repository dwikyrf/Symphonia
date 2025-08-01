<!DOCTYPE html>
<html>
<head>
    <title>Order Payment Confirmation</title>
</head>
<body>
    <h2>Thank You for Your Order!</h2>
    <p>Order Number: <strong>{{ $order->order_number }}</strong></p>
    <p>Total Amount Paid: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
    <p>Status: <strong>{{ ucfirst($order->status) }}</strong></p>
    <p>We will process your order shortly.</p>
    <p>Thank you!</p>
</body>
</html>
