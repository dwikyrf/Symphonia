<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 dark:bg-gray-900"> {{-- Tambah dark mode class --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>{{ $title ?? 'Login' }}</title> {{-- Fallback title jika $title tidak ada --}}
</head>
<body class="h-full font-sans"> {{-- Menambahkan font-sans dasar --}}
<div class="min-h-full">
    {{-- Asumsi x-header adalah komponen Blade untuk header Anda --}}
    {{-- Jika $title dimaksudkan untuk header, pastikan komponen x-header menerimanya --}}
    <x-header>{{ $title ?? 'Login Page' }}</x-header> 

    <main>
        {{-- <x-slot:title> {{ $title }}</x-slot:title> --}} {{-- Baris ini sepertinya tidak tepat di sini jika 'main' bukan komponen Blade, akan saya hapus --}}
        
        <section class="bg-gray-50 ">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto min-h-screen lg:py-0"> {{-- Menggunakan min-h-screen untuk fleksibilitas tinggi --}}
                
                {{-- Pesan Sukses --}}
                @if(session()->has('success'))
                    <div class="w-full max-w-md mx-auto p-4 mb-4 text-sm text-green-800 bg-green-100 dark:bg-green-200 dark:text-green-900 rounded-lg shadow-md flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button type="button" class="ml-2 text-green-700 dark:text-green-800 hover:text-green-900 dark:hover:text-green-900" onclick="this.parentElement.style.display='none';">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                @endif
    
                {{-- Pesan Error --}}
                @if(session()->has('loginError'))
                    <div class="w-full max-w-md mx-auto p-4 mb-4 text-sm text-red-800 bg-red-100 dark:bg-red-200 dark:text-red-900 rounded-lg shadow-md flex justify-between items-center">
                        <span>{{ session('loginError') }}</span>
                        <button type="button" class="ml-2 text-red-700 dark:text-red-800 hover:text-red-900 dark:hover:text-red-900" onclick="this.parentElement.style.display='none';">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                @endif
    
                                {{-- Kartu Login --}}
                <div class="w-full bg-white rounded-xl shadow-2xl sm:max-w-md p-6 sm:p-8">
                    <div class="space-y-4 md:space-y-6">
                        {{-- Button Back (jika diperlukan, sesuaikan href) --}}
                        <div class="mb-2">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </a>
                        </div>

                        <div class="flex justify-center pt-2 pb-4">
                            <img src="{{ asset('img/Tampilan.png') }}" alt="Logo" class="w-28 h-28 object-contain">
                        </div>

                        <h1 class="text-2xl font-bold leading-tight tracking-tight text-gray-900 text-center md:text-3xl">
                            Sign in to your account
                        </h1>

                        <form class="space-y-5 md:space-y-6" action="/login" method="POST">
                            @csrf
                            {{-- Input Email --}}
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your email</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    class="bg-gray-50 border @error('email') border-red-500 @else border-gray-300 @enderror text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-3" 
                                    placeholder="name@company.com" 
                                    autofocus 
                                    required 
                                    value="{{ old('email') }}">
                                @error('email')
                                    <p class="mt-2 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            {{-- Input Password --}}
                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" 
                                        placeholder="••••••••" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-3 pr-10"
                                        required>
                                    <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700">
                                        <svg id="eye-icon-svg" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            {{-- Path akan diisi oleh JavaScript dari kode lengkap halaman --}}
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Ingat Saya & Lupa Password --}}
                            <div class="flex items-center justify-between">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-600 hover:underline">Lupa password?</a>
                                @endif
                            </div>

                            {{-- Tombol Login --}}
                            <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition duration-150 ease-in-out">Sign in</button>
                            <p class="text-sm font-light text-gray-500 text-center">
                                Don’t have an account yet? <a href="/register" class="font-medium text-primary-600 hover:underline">Sign up</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    
        {{-- Script untuk Toggle Password --}}
        <script>
            const togglePasswordButton = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const eyeIconSvg = document.getElementById('eye-icon-svg');
            
            // SVG Path untuk ikon mata (terlihat) dan mata coret (tersembunyi)
            const eyePath = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178a1.012 1.012 0 010 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
            const eyeSlashPath = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.574M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />`;

            // Inisialisasi ikon
            eyeIconSvg.innerHTML = eyeSlashPath; // Mulai dengan password tersembunyi (ikon mata coret)

            togglePasswordButton.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
            
                if (type === 'password') {
                    eyeIconSvg.innerHTML = eyeSlashPath;
                } else {
                    eyeIconSvg.innerHTML = eyePath;
                }
            });
        </script>
    </main>
</div>
</body>
</html>