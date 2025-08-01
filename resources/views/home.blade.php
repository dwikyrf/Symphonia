<x-layout>

  <section class="bg-white py-8 antialiased md:py-16">
    <div class="mx-auto grid max-w-screen-xl px-4 pb-8 md:grid-cols-12 lg:gap-12 lg:pb-16 xl:gap-0">
      
      {{-- Kolom Kiri: Text --}}
      <div class="flex flex-col justify-center md:col-span-7 md:text-start">
        <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight md:max-w-2xl md:text-5xl xl:text-6xl">
          Workwear Berkualitas,<br />untuk Kinerja Lebih Baik!
        </h1>
        <p class="mb-6 max-w-2xl text-gray-500 md:text-lg lg:text-xl">
          Solusi Lengkap untuk Kebutuhan Workwear dan Promosi Anda!
        </p>
        <a href="{{ route('product.index') }}"
        class="inline-block rounded-lg bg-blue-600 px-6 py-3.5 text-center font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
        Shop Now
      </a>
      </div>

      {{-- Kolom Kanan: Gambar --}}
      <div class="hidden md:flex md:col-span-5 justify-center items-center">
        <img src="{{ asset('img/Tampilan.png') }}" alt="shopping illustration" class="max-w-full h-auto" />
      </div>

    </div>
  </section>
</x-layout>
