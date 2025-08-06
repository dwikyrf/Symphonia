<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Order #{{ $order->order_number }}</title>
<style>
  @page  { margin: 28px; }
  body   { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#222; }

  header, footer { position: fixed; left: 0; right: 0; }
  header { top: -20px; height: 70px; }
  footer { bottom: -25px; height: 30px; font-size: 10px; text-align:center; color:#777; }

  table  { width:100%; border-collapse:collapse; margin-top:6px; }
  th,td  { padding:6px 8px; border:1px solid #ddd; }
  th     { background:#f2f2f2; font-weight:bold; }

  .text-r { text-align:right; }
  .w-40  { width:40%; } .w-12 { width:12%; }
  .thumb { width:48px; height:48px; object-fit:cover; border:1px solid #999; }
  .h-logo{ height:50px; }
</style>
</head>
<body>

  <table style="border:none">
    <tr style="border:none">
      <td style="border:none">
        <img src="{{ public_path('img/Tampilan.png') }}" class="h-logo">
      </td>
      <td style="text-align:right; border:none; font-size:11px; line-height:1.4">
        <strong>CV. Symphonia Haksa Kreasindo</strong><br>
        Jl. Gempol Asri Raya No. 31, Perum Gempol Asri<br>
        Bandung, Jawa Barat 40215 – Telp 0812-2380-9292
      </td>
    </tr>
  </table>
  <hr>


<footer>Halaman <span class="page"></span> / <span class="topage"></span></footer>

{{-- UBAH BAGIAN INI: Ganti margin-top dari 78px menjadi 110px --}}
<main style="margin-top:10px;">
  <h2 style="margin-bottom:8px;">Detail Order</h2>

  <div style="line-height:1.6; margin-bottom:12px;">
    <strong>Tanggal :</strong> {{ $order->created_at->format('d F Y') }}<br>
    <strong>Pelanggan :</strong> {{ $order->user->name }}<br>
    <strong>Alamat Kirim :</strong> {{ $order->address?->fullAddress() ?? '-' }}
  </div>

  {{-- ---------- Tabel Produk ---------- --}}
  <table>
    <thead>
      <tr>
        <th style="width:5%;">#</th>
        <th class="w-12">Foto</th>
        <th class="w-40">Produk</th>
        <th class="w-12">Ukuran</th>
        <th class="w-12 text-r">Qty</th>
      </tr>
    </thead>
    <tbody>
    @foreach($items as $i => $it)
      <tr>
        <td class="text-r">{{ $i+1 }}</td>
        <td>
          @if($it['img64'])
            <img class="thumb" src="{{ $it['img64'] }}">
          @endif
        </td>
        <td>{{ $it['name'] }}</td>
        <td>{{ $it['size'] }}</td>
        <td class="text-r">{{ $it['qty'] }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{-- ---------- Desain / Logo / Deskripsi (opsional) ---------- --}}
  @if($design64 || $logo64 || $desc)
    <h3 style="margin-top:14px;">Desain & Catatan Pemesan</h3>
    @if($design64)
      <p style="font-size:11px;"><strong>Design:</strong></p>
      <img src="{{ $design64 }}" style="max-width:220px; border:1px solid #ddd; margin-bottom:8px;">
    @endif

    @if($logo64)
      <p style="font-size:11px;"><strong>Logo:</strong></p>
      <img src="{{ $logo64 }}" style="max-width:220px; border:1px solid #ddd; margin-bottom:8px;">
    @endif

    @if($desc)
      <p style="font-size:11px;"><strong>Description:</strong></p>
      <p style="border:1px solid #ddd; padding:6px;">{{ $desc }}</p>
    @endif
  @endif

</main>
</body>
</html>