<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>{{ $title }}</title>
</head>
<body class="h-full">
<div class="min-h-full">
    <x-header>{{ $title }}</x-header>
    <main>
        <section>
            <div class="flex items-center justify-center min-h-screen bg-gray-100">
                <div class="w-full bg-white rounded-lg shadow sm:max-w-md xl:p-0">
                    <div class="p-6 space-y-4 sm:p-8">
                        <a href="/login" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </a>

                        <div class="flex justify-center mb-6 mt-8">
                            <img src="{{ asset('img/Tampilan.png') }}" alt="Logo" class="w-32 h-32 object-contain">
                        </div>

                        <h1 class="text-xl font-bold text-gray-900">Create an account</h1>

                        <form class="space-y-4" action="{{ route('register.store') }}" method="POST">
                            @csrf
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                                <input type="text" name="name" id="name" class="input-style" value="{{ old('name') }}" required autofocus>
                                @error('name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                                <input type="text" name="username" id="username" class="input-style" value="{{ old('username') }}" required>
                                @error('username')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                <input type="email" name="email" id="email" class="input-style" value="{{ old('email') }}" required>
                                @error('email')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Phone</label>
                                <input type="tel" name="phone" id="phone" class="input-style" value="{{ old('phone') }}" required>
                                @error('phone')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" class="input-style pr-10"
                                           pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}" required
                                           title="Min 8 karakter, 1 huruf besar, 1 angka, 1 simbol">
                                    <button type="button" id="togglePassword" class="absolute right-3 top-2.5 text-gray-500">
                                        👁️
                                    </button>
                                </div>
                                @error('password')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="input-style pr-10" required>
                                    <button type="button" id="toggleConfirm" class="absolute right-3 top-2.5 text-gray-500">
                                        👁️
                                    </button>
                                </div>
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                <select name="role" id="role" class="input-style">
                                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="corporate" {{ old('role') === 'corporate' ? 'selected' : '' }}>corporate</option>
                                </select>
                                @error('role')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <button type="submit" class="btn-primary">Register</button>
                            <p class="text-sm text-gray-500">
                                Already have an account?
                                <a href="/login" class="text-blue-600 hover:underline">Login here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<style>
    .input-style {
        background-color: #f9fafb;
        border: 1px solid #d1d5db;
        color: #111827;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        padding: 0.625rem;
        width: 100%;
    }

    .btn-primary {
        width: 100%;
        background-color: #2563eb;
        color: white;
        padding: 0.625rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
    }

    .checkbox-style {
        width: 1rem;
        height: 1rem;
        border-radius: 0.25rem;
        border: 1px solid #d1d5db;
    }
</style>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    });

    document.getElementById('toggleConfirm').addEventListener('click', function () {
        const input = document.getElementById('password_confirmation');
        input.type = input.type === 'password' ? 'text' : 'password';
    });
</script>
</body>
</html>
