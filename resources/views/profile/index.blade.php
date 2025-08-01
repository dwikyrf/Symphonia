<x-layout>
    <x-slot:title>Profile</x-slot:title>

    <section class="bg-gray-50 py-8 sm:py-12"> 
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8"> 
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-8">
                My Profile
            </h2>

            <div class="bg-white shadow-xl rounded-xl p-6 md:p-8 mb-10"> 
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <img class="h-24 w-24 rounded-full object-cover border-4 border-gray-200 shadow-sm" 
                         src="{{ asset($user->profile_picture ? 'storage/' . $user->profile_picture : 'img/user.png') }}"
                         alt="Profile Picture">

                    <div class="text-center sm:text-left">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-md text-gray-600">{{ $user->email }}</p> 
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-gray-700">
                    <p><strong class="font-semibold text-gray-800">Username:</strong> {{ $user->username }}</p>
                    <p><strong class="font-semibold text-gray-800">Phone:</strong> {{ $user->phone ?: '-' }}</p> 
                </div>

                <div class="mt-8 flex flex-col sm:flex-row sm:space-x-4 space-y-3 sm:space-y-0"> 
                    <a href="{{ route('profile.edit') }}"
                       class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out text-center">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layout>