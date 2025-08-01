<section class="bg-white shadow-xl rounded-xl p-6 md:p-8"> 
    <header class="pb-6 border-b border-gray-200"> 
        <h2 class="text-2xl font-bold text-gray-900"> 
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }} 
        </p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6"> 
        @csrf
        {{-- @method('patch') --}}  

        <div>
            <x-input-label for="name" :value="__('Name')" class="font-semibold" /> 
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" class="font-semibold" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone')" class="font-semibold" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone)" required autocomplete="tel" /> 
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" class="font-semibold" />

            @if ($user->profile_picture)
                <div class="mt-2 mb-2">
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Current Profile Picture" class="h-20 w-20 rounded-full object-cover">
                </div>
                <p class="text-xs text-gray-500 mb-1"></p>
            @endif
            
            <input id="profile_picture" name="profile_picture" type="file" accept="image/png, image/jpeg, image/jpg, image/gif"
                   class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-l-lg file:border-0
                          file:text-sm file:font-semibold
                          file:bg-gray-100 file:text-gray-800
                          hover:file:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB.</p> 
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button> 

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 font-semibold">
                    {{ __('Successfully Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

