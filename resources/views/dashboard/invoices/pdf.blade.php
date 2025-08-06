{{--  resources/views/admin/order/pdf.blade.php  --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Order #{{ $order->order_number }}</title>
<style>
    @page  { margin: 30px 28px; }
    body   { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#222; }

    /* Header & Footer pemisah halaman */
    header { position: fixed; top: -20px; left: 0; right: 0; height: 70px; }
    footer { position: fixed; bottom: -25px; left: 0; right: 0; height: 40px; font-size: 10px;
             text-align:center; color:#777; }

    table  { width:100%; border-collapse:collapse; margin-top:6px; }
    th,td  { padding:6px 8px; border:1px solid #ddd; }
    th     { background:#f2f2f2; font-weight:bold; }

    .text-r { text-align:right; }
    .w-45  { width:45%; }
    .w-15  { width:15%; }
    .mb-1  { margin-bottom:4px; }
    .mb-2  { margin-bottom:8px; }
    .mb-3  { margin-bottom:12px; }
    .h-logo{ height:55px; }

    .total-row td { font-weight:bold; }
</style>
</head>

<body>

<header>
  <table style="border:none">
    <tr style="border:none">
      <td style="width:60%; border:none">
        <img src="{{ public_path('img/Tampilan.png') }}" class="h-logo" alt="Logo">
      </td>
      <td style="text-align:right; border:none; font-size:11px; line-height:1.4">
        <strong>CV. Symphonia Haksa Kreasindo</strong><br>
        Jl. Gempol Asri Raya No. 31, Perum Gempol Asri<br>
        Bandung, Jawa Barat 40215<br>
        Telp 0812-2380-9292
      </td>
    </tr>
  </table>
  <hr>
</header>

<footer>
  Halaman <span class="page"></span> / <span class="topage"></span>
</footer>

<main style="margin-top:80px;"> {{-- dorong isi di bawah header --}}
  <h2 class="mb-2">Invoice</h2>

  <div class="mb-3" style="line-height:1.6">
    <strong>No. Invoice :</strong> {{ $order->order_number }}<br>
    <strong>Tanggal   :</strong> {{ $order->created_at->format('d F Y') }}<br>
    <strong>Pelanggan :</strong> {{ $order->user->name }}<br>
    <strong>Status    :</strong> {{ ucfirst($order->status) }}<br>
    <strong>Alamat Kirim:</strong> {{ $order->address?->fullAddress() ?? '-' }}
  </div>

  {{-- ---------- Tabel Produk ---------- --}}
  <table>
    <thead>
      <tr>
        <th class="w-45">Produk</th>
        <th class="w-15">Ukuran</th>
        <th>Qty</th>
        <th class="text-r">Harga</th>
        <th class="text-r">Subtotal</th>
      </tr>
    </thead>
    <tbody>
    @foreach($order->details as $d)
      <tr>
        <td>{{ $d->product->name }}</td>
        <td>{{ $d->size }}</td>
        <td>{{ $d->quantity }}</td>
        <td class="text-r">Rp {{ number_format($d->product->price,0,',','.') }}</td>
        <td class="text-r">Rp {{ number_format($d->quantity*$d->product->price,0,',','.') }}</td>
      </tr>
    @endforeach
      <tr class="total-row">
        <td colspan="4" class="text-r">Subtotal</td>
        <td class="text-r">Rp {{ number_format($order->price,0,',','.') }}</td>
      </tr>
      <tr class="total-row">
        <td colspan="4" class="text-r">Ongkir</td>
        <td class="text-r">
          Rp {{ number_format(optional($order->shipping)->shipping_cost ?? 0,0,',','.') }}
        </td>
      </tr>
      <tr class="total-row">
        <td colspan="4" class="text-r">Total</td>
        <td class="text-r">Rp {{ number_format($order->total_price,0,',','.') }}</td>
      </tr>
    </tbody>
  </table>

 {{-- ---------- Footer ---------- --}}
    <div class="footer">
        Terima kasih atas kepercayaan Anda! ❤️
    </div>

</body>
</html>
   
