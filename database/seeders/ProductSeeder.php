<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->error('Tidak ada kategori. Jalankan CategorySeeder terlebih dahulu.');
            return;
        }

        // Data 10 produk (silakan sesuaikan)
        $items = [
            [
                'name'        => 'Jaket Harrington Kerja – Hitam',
                'description' => 'Deskripsi singkat: Jaket ringan model Harrington dengan lining motif kotak, tampilan rapi untuk seragam kerja maupun casual.

                                    Fitur utama

                                    Bahan luar halus, nyaman dipakai harian

                                    Resleting depan penuh, kerah stand-up

                                    2 saku ritsleting di sisi, 1 saku dalam

                                    Lining bermotif kotak untuk tampilan premium

                                    Potongan regular fit, mudah dipadukan

                                    Kustomisasi (opsional): bordir/sablon logo perusahaan, pilihan bahan & ukuran S–XXL.
                                    Cocok untuk: seragam tim, event, merchandise premium.',
                'price'       => 350000,
                'image'       => 'img/Item-2.jpg',
            ],
            [
                'name'        => 'Celana Kerja Safety Reflektif – Navy',
                'description' => 'Deskripsi singkat: Celana kerja lapangan dengan strip reflektif di area lutut untuk visibilitas tambahan.

                                Fitur utama

                                Banyak kantong (cargo) untuk alat kecil/HP

                                Strip reflektif meningkatkan keterlihatan saat minim cahaya

                                Jahitan kuat untuk aktivitas industri/lapangan

                                Potongan nyaman, pinggang berpenahan

                                Kustomisasi (opsional): bahan drill/twill sesuai kebutuhan, bordir/sablon identitas, ukuran S–XXL.
                                Cocok untuk: teknisi, logistik, proyek konstruksi.',
                'price'       => 220000,
                'image'       => 'img/Item-3.jpg',
            ],
            [
                'name'        => 'Helm Safety Proyek – Putih',
                'description' => 'Deskripsi singkat: Helm keselamatan kerja dengan pilihan warna merah, putih, oranye, biru, kuning, dan hijau.

                                Fitur utama

                                Cangkang kokoh, ringan dipakai lama

                                Sistem pengencang belakang (adjustable) untuk berbagai ukuran kepala

                                Tersedia slot aksesori (earmuff/face shield)*

                                Pilihan warna untuk kode divisi/area

                                Kustomisasi (opsional): cetak logo perusahaan, stiker identitas.
                                *Ketersediaan fitur aksesori menyesuaikan tipe/stok.',
                'price'       => 65000,
                'image'       => 'img/Item-4.jpg',
            ],
            [
                'name'        => 'Tumbler Insert Paper Custom 450ml',
                'description' => 'Deskripsi singkat: Tumbler plastik double wall dengan lembar desain custom (insert paper) untuk promosi brand.

                                Fitur utama

                                Dinding ganda membantu menjaga suhu lebih lama

                                Tutup flip anti-tumpah, mudah dibawa

                                Desain dapat dicetak full color (logo/slogan)

                                Kapasitas sedang, cocok untuk kopi/teh harian

                                Kustomisasi (opsional): cetak desain 360°, penomoran/event, kemasan gift box.
                                Cocok untuk: suvenir kantor, seminar kit, hadiah pelanggan.',
                'price'       => 65000,
                'image'       => 'img/Item-5.jpg',
            ],
            [
                'name'        => 'Jaket Denim Trucker – Navy',
                'description' => 'Deskripsi singkat: Jaket denim model trucker klasik dengan kancing logam dan saku dada. Tampilan timeless untuk pria/wanita.

                                Fitur utama

                                Bahan denim kokoh, jahitan kontras

                                Kancing depan penuh, kerah klasik

                                2 saku dada flap + 2 saku tangan

                                Nyaman untuk harian atau layering

                                Kustomisasi (opsional): bordir logo, patch, wash/stonewash.
                                Cocok untuk: streetwear, seragam komunitas/event.',
                'price'       => 365000,
                'image'       => 'img/Item-6.png',
            ],
            [
                'name'        => 'Celana Pendek Cargo – Hitam',
                'description' => 'Deskripsi singkat: Celana pendek cargo fungsional dengan banyak saku. Tahan aktivitas, tetap ringan dipakai.

                                Fitur utama

                                Kain tebal yang kuat namun nyaman

                                Resleting & kancing depan, belt loop

                                Multi-pocket: saku samping & saku cargo berkancing

                                Potongan santai (relaxed fit)

                                Kustomisasi (opsional): bordir nama/nomor, pilihan bahan (twill/drill).
                                Cocok untuk: outdoor, kerja lapangan, casual harian.',
                'price'       => 165000,
                'image'       => 'img/Item-7.png',

            ],
            [
                'name'        => 'Celana Jeans Straight – Indigo',
                'description' => 'Deskripsi singkat: Celana jeans potongan straight/regular dengan 5 saku. Gaya rapi untuk kerja maupun santai.

                                    Fitur utama

                                    Denim nyaman dengan durabilitas baik

                                    5-pocket styling, belt loop lengkap

                                    Resleting/kancing depan, jahitan kontras

                                    Mudah dipadukan dengan kemeja atau kaos

                                    Kustomisasi (opsional): ukuran inseam, patch label.
                                    Cocok untuk: workwear casual, daily wear.',
                'price'       => 275000,
                'image'       => 'img/Item-8.png',

            ],
            [
                'name'        => 'Jaket Denim Trucker – Indigo Tua (Varian B)',
                'description' => 'Deskripsi singkat: Trucker denim klasik dengan detail kantong dan kancing logam. Nuansa vintage yang tetap modern.

                                Fitur utama

                                Denim kokoh, kerah dan kancing depan penuh

                                2 saku dada flap + saku tangan tersembunyi

                                Jahitan rapi, hem bawah stabil

                                Unisex, cocok untuk berbagai gaya

                                Kustomisasi (opsional): patch/bordir, penambahan label merek.
                                Cocok untuk: seragam komunitas, merchandise premium.',
                'price'       => 365000,
                'image'       => 'img/Item-9.png',

            ],
            [
                'name'        => 'Rompi Safety Mesh High-Vis – Kuning Stabilo',
                'description' => 'Deskripsi singkat: Rompi safety berbahan mesh (jala) ringan dan sejuk dengan warna high-visibility untuk meningkatkan keterlihatan pekerja.

                                    Fitur utama

                                    Bahan jala bernapas, cepat kering

                                    Warna kuning stabilo mudah terlihat

                                    Piping tegas di tepi rompi untuk daya tahan

                                    Nyaman dipakai di area proyek/lapangan

                                    Kustomisasi (opsional): bordir/sablon logo, penambahan strip reflektif sesuai permintaan.
                                    Cocok untuk: proyek konstruksi, logistik, event lapangan, keamanan.',
                'price'       => 132000,
                'image'       => 'img/Item-10.jpg',

            ],
            [
                'name'        => 'Kemeja Denim Lengan Panjang – Biru',
                'description' => 'Deskripsi singkat: Kemeja denim kasual dengan dua saku dada berkancing. Nyaman dipakai harian atau sebagai outer tipis.

                                Fitur utama

                                Bahan denim medium-weight, lembut dan tidak kaku

                                Kancing penuh di bagian depan, manset berkancing

                                2 saku dada flap dengan kancing

                                Potongan regular, mudah dipadukan

                                Kustomisasi (opsional): bordir/sablon logo, label dalam, ukuran S–XXL.
                                Cocok untuk: seragam casual, komunitas, merchandise brand.',
                'price'       => 350000,
                'image'       => 'img/Item-1.png',
            ],
        ];

        // Resolver category_id (pakai 'category' by name/slug atau fallback)
        $resolveCategoryId = function (array $item) use ($categories): int {
            if (isset($item['category_id']) && $categories->contains('id', $item['category_id'])) {
                return (int) $item['category_id'];
            }
            if (isset($item['category'])) {
                $cat = Category::where('name', $item['category'])
                               ->orWhere('slug', $item['category'])
                               ->first();
                if ($cat) return $cat->id;
            }
            return (int) $categories->first()->id;
        };

        // Generator slug unik
        $makeSlug = function (string $name): string {
            $base = Str::slug($name);
            $slug = $base;
            $i = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$base}-{$i}";
                $i++;
            }
            return $slug;
        };

        foreach ($items as $item) 
        {
            Product::updateOrCreate(
                ['name' => $item['name']],
                [
                    'name'        => $item['name'],
                    'slug'        => $makeSlug($item['name']),
                    'description' => $item['description'] ?? '',
                    'price'       => (int) ($item['price'] ?? 0),
                    'image'       => $item['image'] ?? null,
                    'category_id' => $categories->random()->id, // <- selalu acak
                ]
            );
        }


        $this->command->info('ProductSeeder: 10 produk dibuat/diupdate dengan slug.');
    }
}
