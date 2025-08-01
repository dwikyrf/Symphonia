<x-layout>
    <x-slot:title> {{ $title }}</x-slot:title>
    <section class="bg-gray-50 py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
          <div class="mb-4 flex items-center justify-between gap-4 md:mb-8">
            <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">Shop by category</h2>
          </div>
            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($categories as $category)
                <a href="/products?category={{ $category->slug }}" 
                class="flex items-center space-x-3 rounded-xl border border-gray-300 bg-white p-4 shadow-md transition-transform transform hover:scale-105 hover:shadow-lg">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-500 text-white">
                        <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M9 5h-.16667c-.86548 0-1.70761.28071-2.4.8L3.5 8l2 3.5L8 10v9h8v-9l2.5 1.5 2-3.5-2.9333-2.2c-.6924-.51929-1.5346-.8-2.4-.8H15M9 5c0 1.5 1.5 3 3 3s3-1.5 3-3M9 5h6"/>
                        </svg>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $category->name }}</span>
                </a>
                @endforeach
            </div>        
        </div>
      </section>
  </x-layout>

