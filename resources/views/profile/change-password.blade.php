<x-layout>
    <x-slot:title>Change Password</x-slot:title>

    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
                <img class="w-64 h-32 mr-2" src="{{ asset('img/tampilan.png') }}" alt="logo">
                
            </a>

            <div class="w-full p-6 bg-white rounded-lg shadow sm:max-w-md sm:p-8">
                <h2 class="mb-1 text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Change Password
                </h2>

                <!-- Menampilkan error jika ada -->
                @if ($errors->any())
                    <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Menampilkan pesan sukses jika ada -->
                @if (session('success'))
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form class="mt-4 space-y-4 lg:mt-5 md:space-y-5" action="{{ route('profile.update-password') }}" method="POST">
                    @csrf

                    <!-- Password Lama -->
                    <div>
                        <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900">New Password</label>
                        <input type="password" name="new_password" id="new_password" required minlength="8"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label for="new_password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    <!-- Tombol Reset -->
                    <button type="submit"
                        class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-layout>
