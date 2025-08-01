<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Judul halaman ini mungkin lebih tepat "Edit Profile" jika ada halaman "View Profile" terpisah --}}
            {{ __('Profile') }} 
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Tombol Kembali ke Profil --}}
            <div class="mb-6">
                <a href="{{ route('profile.index') }}" 
                   class="inline-flex items-center px-4 py-2 dark:bg-gray-800 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to Profile') }}
                </a>
            </div>

            {{-- Kontainer untuk kartu-kartu form, dipisahkan oleh space-y-6 --}}
            <div class="space-y-6">

                {{-- Kartu untuk Update Profile Information --}}
                <div class="p-6 sm:p-8 bg-white shadow-md sm:rounded-xl">
                    {{-- Pembungkus konten agar berada di tengah kartu dan memiliki lebar maksimum --}}
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Kartu untuk Update Password --}}
                <div class="p-6 sm:p-8 bg-white  shadow-md sm:rounded-xl">
                    {{-- Pembungkus konten agar berada di tengah kartu dan memiliki lebar maksimum --}}
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Kartu untuk Delete User --}}
                <div class="p-6 sm:p-8 bg-white  shadow-md sm:rounded-xl">
                    {{-- Pembungkus konten agar berada di tengah kartu dan memiliki lebar maksimum --}}
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>