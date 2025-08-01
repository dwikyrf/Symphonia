<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
<div class="min-h-full">
    <main>
        <section class="bg-gray-50">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">

                {{-- Pesan Status --}}
                @if (session('status'))
                    <div class="w-full max-w-md mx-auto p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                        {{ session('status') }}
                        <button type="button" class="float-right" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                @endif

                {{-- Error Validasi --}}
                @if ($errors->any())
                    <div class="w-full max-w-md mx-auto p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="float-right" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                @endif

                <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        <a href="/login" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Kembali ke login
                        </a>

                        <div class="flex justify-center mb-6 mt-8">
                            <img src="{{ asset('img/Tampilan.png') }}" alt="Logo" class="w-32 h-32 object-contain">
                        </div>

                        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                            Lupa Password
                        </h1>
                        <p class="text-sm text-gray-500">
                            Masukkan alamat email Anda. Kami akan mengirimkan tautan untuk mengatur ulang password Anda.
                        </p>

                        <form class="space-y-4 md:space-y-6" action="{{ route('password.email') }}" method="POST">
                            @csrf

                            {{-- Input Email --}}
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Alamat Email</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    required 
                                    autofocus
                                    placeholder="you@example.com"
                                    value="{{ old('email') }}"
                                    class="bg-gray-50 border @error('email') border-red-500 @enderror border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            </div>

                            {{-- Tombol Submit --}}
                            <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Kirim Link Reset Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
