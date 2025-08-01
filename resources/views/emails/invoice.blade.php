<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice Order #{{ $order->order_number }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333;">Hello {{ $order->user->name }},</h2>
        <p style="color: #555;">Thank you for your purchase! Below are your order details:</p>

        <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; color: #777;">Invoice Number:</td>
                <td style="padding: 8px; color: #333;"><strong>#{{ $order->order_number }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 8px; color: #777;">Date:</td>
                <td style="padding: 8px; color: #333;">{{ $order->created_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; color: #777;">Total Payment:</td>
                <td style="padding: 8px; color: #333;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; color: #777;">Payment Status:</td>
                <td style="padding: 8px; color: #333;">{{ ucfirst($order->payment_status) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; color: #777;">Order Status:</td>
                <td style="padding: 8px; color: #333;">{{ ucfirst($order->status) }}</td>
            </tr>
        </table>

        <p style="margin-top: 30px; color: #555;">We’ve also attached a PDF invoice for your records.</p>
        <p style="color: #999;">If you have any questions, feel free to contact us.</p>
        <p style="color: #555;">Thanks,<br><strong>{{ config('app.name') }}</strong></p>
    </div>
</body>
</html>
