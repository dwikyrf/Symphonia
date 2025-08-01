<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Verifikasi Email</title>
</head>
<body class="h-full">
<div class="min-h-screen flex items-center justify-center">
    <main class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
        <div class="text-center mb-6">
            <img src="{{ asset('img/Tampilan.png') }}" class="mx-auto w-24 h-24 object-contain" alt="Logo">
            <h1 class="text-xl font-bold text-gray-900 mt-4">Verifikasi Email</h1>
        </div>

        <p class="text-sm text-gray-600 mb-4">
            Terima kasih telah mendaftar! Sebelum melanjutkan, mohon verifikasi alamat email Anda melalui tautan yang telah kami kirimkan.
            Jika Anda belum menerima email tersebut, kami akan mengirimkannya kembali untuk Anda.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg">
                Tautan verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif

        <div class="flex items-center justify-between mt-6">
            <!-- Kirim Ulang -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-sm text-gray-600 underline hover:text-gray-900">
                    Keluar
                </button>
            </form>
        </div>
    </main>
</div>
</body>
</html>
