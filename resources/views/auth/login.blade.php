<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full">
<div class="min-h-full">
    <main>
        <section class="bg-gray-50">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">

                {{-- Pesan Sukses --}}
                @if(session()->has('success'))
                    <div class="w-full max-w-md mx-auto p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                        {{ session('success') }}
                        <button type="button" class="float-right" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                @endif

                {{-- Pesan Error --}}
                @if(session()->has('loginError'))
                    <div class="w-full max-w-md mx-auto p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        {{ session('loginError') }}
                        <button type="button" class="float-right" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                @endif

                <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        {{-- Logo --}}
                        <div class="flex justify-center mb-6 mt-4">
                            <img src="{{ asset('img/Tampilan.png') }}" alt="Logo" class="w-28 h-28 object-contain">
                        </div>

                        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                            Masuk ke Akun Anda
                        </h1>

                        <form class="space-y-4 md:space-y-6" action="{{ route('login') }}" method="POST">
                            @csrf

                            {{-- Input Email --}}
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Alamat Email</label>
                                <input type="email" name="email" id="email" 
                                    value="{{ old('email') }}"
                                    placeholder="you@example.com"
                                    required autofocus
                                    class="bg-gray-50 border @error('email') border-red-500 @enderror border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Input Password --}}
                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password"
                                        placeholder="••••••••"
                                        required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                    <button type="button" id="toggle-password" class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500">
                                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M1 12C1 12 5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ingat Saya + Forgot --}}
                            <div class="flex items-center justify-between">
                                <label for="remember_me" class="flex items-center space-x-2 text-sm text-gray-600">
                                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span>Ingat saya</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-600 hover:underline">Lupa password?</a>
                                @endif
                            </div>

                            {{-- Tombol Login --}}
                            <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Masuk
                            </button>

                            <p class="text-sm font-light text-gray-500">
                                Belum punya akun? <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:underline">Daftar</a>
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
            const eyeIcon = document.getElementById('eye-icon');

            togglePasswordButton.addEventListener('click', () => {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;

                eyeIcon.innerHTML = type === 'password'
                    ? `<path d="M1 12C1 12 5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle>`
                    : `<path d="M17 17l4 4m-4-4L7 7m5 5L2 2"></path>`;
            });
        </script>
    </main>
</div>
</body>
</html>
