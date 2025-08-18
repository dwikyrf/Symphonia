<nav class="bg-white antialiased" x-data="{ dropdownOpen: false, mobileOpen: false }">
  <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0 py-4">
    <div class="flex items-center justify-between">

      {{-- Kiri: Logo + Menu --}}
      <div class="flex items-center space-x-8">
        <div class="shrink-0">
          <a href="/" title="">
            <img class="block w-auto h-12" src="{{ asset('img/Logo_web.png') }}" alt="Logo">
          </a>
        </div>

        <ul class="hidden lg:flex items-center gap-6 md:gap-8 py-3">
          <li><a href="/" class="text-sm font-medium text-gray-900 hover:text-blue-700">Home</a></li>
          <li><a href="{{ route('product.index') }}" class="text-sm font-medium text-gray-900 hover:text-blue-700">Product</a></li>
          <li><a href="{{ route('category.index') }}" class="text-sm font-medium text-gray-900 hover:text-blue-700">Category</a></li>
          <li><a href="{{ route('about') }}" class="text-sm font-medium text-gray-900 hover:text-blue-700">About</a></li>
          <li><a href="{{ route('contact') }}" class="text-sm font-medium text-gray-900 hover:text-blue-700">Contact</a></li>
        </ul>
      </div>

      {{-- Kanan: Cart + Account --}}
      <div class="flex items-center space-x-2">

        {{-- Cart --}}
        <a href="{{ route('cart.index') }}" class="inline-flex items-center p-2 hover:bg-gray-100 text-sm font-medium text-gray-900">
          <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 100 4 2 2 0 000-4Zm8 0a2 2 0 100 4 2 2 0 000-4Zm-8.5-3h9.25L19 7H7.312" />
          </svg>
          <span class="hidden sm:flex">My Cart</span>
        </a>

        {{-- Account Dropdown --}}
        <div class="relative">
          <button @click="dropdownOpen = !dropdownOpen" class="inline-flex items-center p-2 hover:bg-gray-100 text-sm font-medium text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-width="2" d="M7 17v1a1 1 0 001 1h8a1 1 0 001-1v-1a3 3 0 00-3-3h-4a3 3 0 00-3 3Zm8-9a3 3 0 11-6 0 3 3 0 016 0Z"/>
            </svg>
            Account
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute right-0 z-20 mt-2 w-56 bg-white rounded-lg shadow divide-y divide-gray-100 p-2">
            @auth
              <ul class="text-sm text-gray-900">
                <li><a href="{{ route('profile.index') }}" class="block px-4 py-2 hover:bg-gray-100">My Account</a></li>
                <li><a href="{{ route('order.index') }}" class="block px-4 py-2 hover:bg-gray-100">My Orders</a></li>
                <li><a href="{{ route('addresses.index') }}" class="block px-4 py-2 hover:bg-gray-100">Delivery Addresses</a></li>
              </ul>
              <div class="px-4 py-2">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block text-sm text-gray-900 hover:bg-gray-100 rounded px-3 py-2">Sign Out</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
              </div>
            @else
              <ul class="text-sm text-gray-900">
                <li><a href="/login" class="block px-4 py-2 hover:bg-gray-100">Sign In</a></li>
                <li><a href="/register" class="block px-4 py-2 hover:bg-gray-100">Register</a></li>
              </ul>
            @endauth
          </div>
        </div>

        {{-- Mobile Menu Toggle --}}
        <button @click="mobileOpen = !mobileOpen" class="inline-flex lg:hidden items-center justify-center p-2 rounded-md hover:bg-gray-100 text-gray-900">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
          </svg>
        </button>
      </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen" class="lg:hidden bg-gray-50 border border-gray-200 rounded-lg p-4 mt-4">
      <ul class="space-y-3 text-sm text-gray-900">
        <li><a href="/" class="hover:text-blue-700">Home</a></li>
        <li><a href="/product" class="hover:text-blue-700">Produk</a></li>
        <li><a href="/category" class="hover:text-blue-700">Kategori</a></li>
      </ul>
    </div>
  </div>
</nav>
