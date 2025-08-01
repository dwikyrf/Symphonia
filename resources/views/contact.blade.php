{{-- resources/views/contact.blade.php --}}
<x-layout>
    <x-slot name="title">Contact Us</x-slot>

    {{-- ███████████  HERO SECTION  ███████████ --}}
    <section class="bg-slate-50">
        <div class="mx-auto max-w-5xl px-6 py-16 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                Hubungi Kami
            </h1>
            <p class="mt-4 text-lg leading-relaxed text-gray-600">
                Punya pertanyaan atau ingin memulai Pesanan? Kami siap membantu Anda.
            </p>
        </div>
    </section>

    {{-- ███████████  MAIN CONTENT  ███████████ --}}
    <section class="bg-white">
        <div class="mx-auto grid max-w-5xl grid-cols-1 gap-12 px-6 py-20 lg:grid-cols-2 lg:gap-16">

            {{-- Kolom Kiri: Informasi Kontak --}}
            <div class="space-y-10">
                
                {{-- 1. Alamat --}}
                <div class="flex items-start gap-4">
                    {{-- Ikon --}}
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    {{-- Detail --}}
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Alamat Kantor</h3>
                        <a href="https://maps.app.goo.gl/tNq6GzTqBUnw5s6t5" target="_blank" rel="noopener noreferrer" class="mt-1 block text-gray-600 hover:text-primary-600 hover:underline">
                            Jl. Gempol Asri Raya No. 31, Perumahan Gempol Asri<br>Bandung, Jawa Barat – 40215
                        </a>
                    </div>
                </div>

                {{-- 2. Email & Telepon --}}
                <div class="flex items-start gap-4">
                     {{-- Ikon --}}
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                    </div>
                    {{-- Detail --}}
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Email & Telepon Kantor</h3>
                        <a href="mailto:marketingseragam@yahoo.com" class="mt-1 block text-gray-600 hover:text-primary-600 hover:underline">marketingseragam@yahoo.com</a>
                        <a href="tel:+622220572652" class="mt-1 block text-gray-600 hover:text-primary-600 hover:underline">022 – 2057 2652</a>
                    </div>
                </div>

                {{-- 3. WhatsApp --}}
                <div class="flex items-start gap-4">
                     {{-- Ikon --}}
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.76 9.76 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                        </svg>
                    </div>
                    {{-- Detail --}}
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">WhatsApp</h3>
                        <ul class="mt-1 space-y-1">
                            <li><a href="https://wa.me/6281223809292" target="_blank" class="text-gray-600 hover:text-primary-600 hover:underline">0812 2380 9292</a></li>
                            <li><a href="https://wa.me/6281288546955" target="_blank" class="text-gray-600 hover:text-primary-600 hover:underline">0812 8854 6955</a></li>
                            <li><a href="https://wa.me/6281395285415" target="_blank" class="text-gray-600 hover:text-primary-600 hover:underline">0813 9528 5415</a></li>
                        </ul>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Google Maps --}}
            <div class="h-80 w-full overflow-hidden rounded-lg shadow-lg lg:h-full">
                {{-- PENTING: Ganti 'src' di bawah dengan kode embed dari Google Maps untuk alamat Anda --}}
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.5925827329596!2d107.5501198748383!3d-6.939110493059501!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e5554605e555%3A0x69634e0a581e285d!2sJl.%20Gempol%20Asri%20Raya%20No.31%2C%20Cigondewah%20Rahayu%2C%20Kec.%20Bandung%20Kulon%2C%20Kota%20Bandung%2C%20Jawa%20Barat%2040215!5e0!3m2!1sen!2sid!4v1722486144578!5m2!1sen!2sid"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

        </div>
    </section>
</x-layout>