{{-- resources/views/about.blade.php --}}
<x-layout>
    <x-slot name="title">About Us</x-slot>

    {{-- HERO ---------------------------------------------------------------- --}}
    <section class="relative overflow-hidden bg-slate-900 text-white">
        <div
            class="mx-auto flex max-w-5xl flex-col items-center gap-6 px-6 py-24 text-center
                   lg:items-start lg:py-32 lg:text-left">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl">
                CV. Symphonia Haksa Kreasindo
            </h1>
            <p class="max-w-xl text-lg/relaxed text-slate-300">
                One-stop <span class="font-semibold text-white">workwear&nbsp;&amp; promotion solution</span> sejak 2006.
            </p>
        </div>

        <div
            class="pointer-events-none absolute inset-y-0 right-0 w-1/2 bg-gradient-to-br
                   from-primary-600 to-primary-800
                   [clip-path:polygon(30%_0,100%_0,100%_100%,0_100%)]
                   lg:w-1/3">
        </div>
    </section>

    <div class="bg-slate-50">
        <div class="mx-auto max-w-5xl space-y-16 px-4 py-20">

            {{-- PROFIL -------------------------------------------------------- --}}
            <x-about.card title="Profil Singkat">
                <div class="space-y-4 leading-relaxed text-gray-600">
                    <p>
                        <strong>CV. Symphonia Haksa Kreasindo (SHK)</strong> didirikan di Bandung pada 2006
                        oleh <span class="font-medium text-gray-800">Bp. Zulfahmi</span>. Kini
                        <strong>SYMPHONIA</strong> mengelola dua unit—<em>Promo</em> &amp; <em>Safety</em>—
                        beroperasi di Jakarta Selatan dan Timur, berfokus pada
                        <em>e-commerce</em> apparel &amp; pemenuhan kebutuhan bisnis (B2B).
                    </p>
                    <p>
                        Berpengalaman lebih dari satu dekade sebagai pemasok seragam kerja dan souvenir promosi
                        untuk berbagai sektor industri&nbsp;— minyak, gas, batubara, hingga manufaktur.
                        Didukung SDM kompeten, kami menjaga <strong>kualitas</strong>, <strong>layanan prima</strong>,
                        dan <strong>ketepatan waktu pengiriman</strong>.
                    </p>
                </div>
            </x-about.card>

            {{-- VISI & MISI ---------------------------------------------------- --}}
            <x-about.card title="Visi &amp; Misi">
                <div class="grid gap-8 md:grid-cols-2 md:gap-12">
                    <div>
                        <h3 class="mb-3 text-2xl font-bold text-primary-700">Visi</h3>
                        <p class="leading-relaxed text-gray-600">
                            Menjadi perusahaan <strong>terbaik &amp; terpercaya</strong> dalam industri kreatif,
                            memenuhi kebutuhan pasar global, serta berkontribusi pada kesejahteraan masyarakat.
                        </p>
                    </div>

                    <div>
                        <h3 class="mb-3 text-2xl font-bold text-primary-700">Misi</h3>
                        <ul class="space-y-3">
                            @foreach ([
                                'Menghasilkan produk berkualitas tinggi yang inovatif dan berdaya saing.',
                                'Menciptakan lingkungan kerja yang sehat, aman, nyaman, dan produktif.',
                                'Membangun kemitraan strategis yang saling menguntungkan dengan seluruh stakeholder.',
                            ] as $misi)
                                <li class="flex items-start gap-3">
                                    <svg
                                        class="mt-1 h-5 w-5 flex-shrink-0 text-primary-600"
                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    <span class="text-gray-600">{{ $misi }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </x-about.card>

            {{-- ███████  COMMITMENT  ███████ --}}
        {{-- PERBAIKAN: Ikon untuk 'Pelayanan Terbaik' dan 'Harga Kompetitif' disesuaikan agar sama persis dengan gambar. --}}
{{-- KOMITMEN -------------------------------------------------------- --}}
            <x-about.card title="Komitmen Kami">
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @php
                        $commitments = [
                            ['Pelayanan Terbaik',  'Respon cepat, komunikasi mudah, solusi membantu.'],
                            ['Aman & Terpercaya',  'Menjaga kepercayaan dan kerahasiaan data pelanggan.'],
                            ['Standar Kualitas',   'Proses produksi dengan quality control ketat.'],
                            ['Tepat Waktu',        'Produksi &amp; pengiriman sesuai jadwal.'],
                            ['Harga Kompetitif',   'Harga terbaik sesuai kualitas produk.'],
                            ['Garansi Produk',     'Jaminan penggantian atas kesalahan produksi.'],
                        ];
                    @endphp

                    @foreach ($commitments as [$title, $desc])
                        <div class="flex items-start gap-4 rounded-lg bg-slate-100 p-4">
                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center
                                       rounded-full bg-primary-100 text-primary-600">
                                {{-- ikon centang --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"
                                     class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $title }}</h4>
                                <p class="text-sm text-gray-600">{{ $desc }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-about.card>

            {{-- PRODUK ---------------------------------------------------------- --}}
            <x-about.card title="Produk Kami">
                <div class="grid gap-x-8 gap-y-6 md:grid-cols-2">
                    @php
                        $produk = [
                            ['Seragam (Uniforms)',    'Kantor, Lapangan, Promosi, Komunitas'],
                            ['Safety Workwear',       'Rompi, Wearpack, Helm, Sepatu Safety'],
                            ['Kaos (T-Shirt)',        'O-Neck, Polo Shirt, Jersey, Raglan'],
                            ['Souvenir Promosi',      'Plakat, Tumbler, Seminar Kit, Payung'],
                            ['Topi (Cap)',            'Promosi, Outdoor, Komunitas'],
                            ['Tas (Bag)',             'Ransel, Tas Selempang, Goodie Bag'],
                        ];
                    @endphp

                    @foreach ($produk as $i => [$nama, $detail])
                        <div class="flex items-start gap-3">
                            <span
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center
                                       rounded-md bg-sky-100 text-sky-700 text-xs font-bold">
                                {{ $i + 1 }}
                            </span>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $nama }}</h4>
                                <p class="text-sm text-gray-600">{{ $detail }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-about.card>

            {{-- CLIENTS --------------------------------------------------------- --}}
            <x-about.card title="Dipercaya Oleh">
                <p class="mx-auto mb-8 max-w-2xl text-center text-gray-600">
                    Kami bangga menjadi mitra berbagai perusahaan dan institusi terkemuka di Indonesia.
                </p>

                @php
                    $clients = [
                        ['PT Adaro Indonesia',      'img/Adaro.jpeg'],
                        ['PT Kaltim Prima Coal',    'img/Kaltim.png'],
                        ['PT Pertamina',            'img/Pertamina.png'],
                        ['PT Cipta Kridatama',      'img/Cipta.jpeg'],
                        ['PT Bukit Asam',           'img/Bukit.png'],
                        ['PT PP (Persero)',         'img/PP.png'],
                        ['Orica',                   'img/Orica.jpeg'],
                        ['Bayer',                   'img/Bayer.png'],
                    ];
                @endphp

                <div
                    class="grid grid-cols-2 gap-x-8 gap-y-10 items-center justify-center
                           sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($clients as [$nama, $logo])
                        <div class="flex justify-center">
                            <img
                                src="{{ $logo }}"
                                alt="Logo {{ $nama }}"
                                class="max-h-12 w-auto object-contain filter grayscale transition
                                       duration-300 hover:grayscale-0" />
                        </div>
                    @endforeach
                </div>
            </x-about.card>

        </div>
    </div>
</x-layout>
