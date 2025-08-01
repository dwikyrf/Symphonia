<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js">
  document.addEventListener("DOMContentLoaded", function(event) {
    document.getElementById('defaultModalButton').click();
    });
</script>
  <title>Home</title>
</head>
@if(auth()->user() && auth()->user()->role == 'admin')
{{-- Code 1 --}}
<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-100" x-data="{ open: false }">
       <x-sidebar></x-sidebar>

        <div class="flex-1 flex flex-col">
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
@else
{{-- code 2 --}}
<body>
  <x-navbar></x-navbar>
  <div class="flex h-screen">
      <main class="flex-1 p-8">
        <div class="mx-auto max-w px-4 py-6 sm:px-6 lg:px-8">
          {{ $slot }}
        </div> 
      </main>
  </div>
  @stack('scripts')
</body>
@endif
</html>
{{-- FLASH MESSAGE --}}
@if (session('success') || session('error') || session('warning'))
    <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms
        x-init="setTimeout(() => show = false, 4000)" 
        class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md px-4">

        <div class="flex items-center p-4 mb-4 rounded-lg 
            @if(session('success')) bg-green-100 text-green-800
            @elseif(session('error')) bg-red-100 text-red-800
            @elseif(session('warning')) bg-yellow-100 text-yellow-800
            @endif"
            role="alert">

            {{-- ICON --}}
            <svg class="flex-shrink-0 w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                @if(session('success'))
                    <path d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 5.707 8.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"/>
                @elseif(session('error'))
                    <path d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-7-4a1 1 0 10-2 0v4a1 1 0 002 0V6zm-2 6a1 1 0 102 0 1 1 0 00-2 0z"/>
                @elseif(session('warning'))
                    <path d="M8.257 3.099c.765-1.36 2.718-1.36 3.484 0l6.518 11.61c.75 1.337-.213 3.04-1.742 3.04H3.48c-1.528 0-2.492-1.703-1.742-3.04l6.518-11.61zM11 14a1 1 0 11-2 0 1 1 0 012 0zm-2-2a1 1 0 012 0v1a1 1 0 01-2 0v-1z"/>
                @endif
            </svg>

            {{-- MESSAGE --}}
            <div class="flex-1 text-sm font-medium">
                @if(session('success'))
                    <strong>Sukses!</strong> {{ session('success') }}
                @elseif(session('error'))
                    <strong>Error!</strong> {{ session('error') }}
                @elseif(session('warning'))
                    <strong>Peringatan!</strong> {{ session('warning') }}
                @endif
            </div>

            {{-- CLOSE BUTTON --}}
            <button type="button" @click="show = false"
                class="ml-4 text-lg font-semibold focus:outline-none">
                &times;
            </button>
        </div>
    </div>
@endif


