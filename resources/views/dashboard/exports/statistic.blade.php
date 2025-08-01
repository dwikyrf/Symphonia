<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Statistik Dashboard</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 30px;
        }
        h1, h2, h3 {
            margin: 0 0 10px;
        }
        h1 {
            font-size: 18px;
            text-transform: uppercase;
        }
        h2 {
            font-size: 16px;
            margin-top: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 5px;
        }
        p {
            margin: 0;
        }
        .small-text {
            font-size: 11px;
            color: #777;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .section {
            margin-top: 30px;
        }
    </style>
</head>
<body>
        <div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ public_path('img/Tampilan.png') }}" alt="Logo" style="height: 60px;">
    <h1 style="margin: 10px 0 5px;">CV SYMPHONIA HAKSA KREASINDO</h1>
    <p style="font-size: 12px; color: #666;">Laporan Statistik Penjualan</p>
    <p style="font-size: 11px; color: #888;">Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
        </div>
    <hr>

    <h2>Ringkasan</h2>
    <table>
        <tr>
            <th>Total Produk Terjual</th>
            <td>{{ number_format($totalProducts ?? 0) }}</td>
        </tr>
        <tr>
            <th>Total Customer</th>
            <td>{{ number_format($totalCustomers ?? 0) }}</td>
        </tr>
        <tr>
            <th>Total Order</th>
            <td>{{ number_format($totalOrders ?? 0) }}</td>
        </tr>
        <tr>
            <th>Total Revenue</th>
            <td>Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h2>Orders Per Month</h2>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah Order</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ordersPerMonth as $month => $total)
                <tr>
                    <td>{{ $month }}</td>
                    <td>{{ $total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Revenue Per Month</h2>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Revenue (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($revenuePerMonth as $month => $total)
                <tr>
                    <td>{{ $month }}</td>
                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Distribusi Status Order</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orderStatusDistribution as $status => $count)
                <tr>
                    <td>{{ ucfirst($status) }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Distribusi Order Berdasarkan Provinsi</h2>
    <table>
        <thead>
            <tr>
                <th>Provinsi</th>
                <th>Jumlah Order</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProvinces as $province => $count)
                <tr>
                    <td>{{ $province }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Produk Paling Banyak Dipesan</h2>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProducts as $product => $count)
                <tr>
                    <td>{{ $product }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Pendapatan Berdasarkan Kategori Produk</h2>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Total Revenue (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($revenueByCategory as $category => $total)
                <tr>
                    <td>{{ $category }}</td>
                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
