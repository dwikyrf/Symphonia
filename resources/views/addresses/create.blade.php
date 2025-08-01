<x-layout>
    <x-slot:title>Tambah Alamat</x-slot:title>

    <section class="bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-6 bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Tambah Alamat Baru</h2>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Validasi Gagal!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="recipient_name" class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                        <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name') }}"
                               placeholder="Nama Lengkap"
                               class="w-full p-3 border rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('recipient_name') border-red-500 @enderror" required>
                        @error('recipient_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full p-3 border rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('phone') border-red-500 @enderror" required>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap (Jalan, Rumah, dll)</label>
                    <textarea name="address" id="address" rows="3" placeholder="Jl. Contoh No. 123, Perumahan ABC"
                              class="w-full p-3 border rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('address') border-red-500 @enderror" required>{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dropdown Berantai -->
                <div class="mt-4">
                    <label for="province" class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <select id="province" name="province" class="w-full p-3 border rounded-lg @error('province') border-red-500 @enderror" required>
                        <option value="" selected disabled>Pilih Provinsi</option>
                    </select>
                    @error('province')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="city" class="block text-sm font-medium text-gray-700">Kota/Kabupaten</label>
                    <select id="city" name="city" class="w-full p-3 border rounded-lg @error('city') border-red-500 @enderror" required disabled>
                        <option value="" selected disabled>Pilih Kota/Kabupaten</option>
                    </select>
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="district" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                    <select id="district" name="district" class="w-full p-3 border rounded-lg @error('district') border-red-500 @enderror" required disabled>
                        <option value="" selected disabled>Pilih Kecamatan</option>
                    </select>
                    @error('district')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Kelurahan -->
                <div class="mt-4">
                    <label for="village" class="block text-sm font-medium text-gray-700">Kelurahan/Desa</label>
                    <select id="village" name="village" class="w-full p-3 border rounded-lg" required disabled>
                        <option value="" selected disabled>Pilih Kelurahan</option>
                    </select>
                    @error('village')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Pos -->
                <div class="mt-4">
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                    <input type="text" name="postal_code" id="postal_code"
                        class="w-full p-3 border rounded-lg bg-gray-100" readonly required>
                </div>

                <!-- Dropdown kode pos (jika diperlukan) -->
                <div class="mt-2 hidden" id="postal_dropdown_wrapper">
                    <label for="postal_dropdown" class="block text-sm font-medium text-gray-700">Pilih Kode Pos</label>
                    <select id="postal_dropdown" class="w-full p-2 border rounded-lg mt-1"></select>
                </div>

                <!-- Hidden field untuk ID wilayah -->
                <input type="hidden" name="province_code" id="province_code" value="{{ old('province_code') }}">
                <input type="hidden" name="city_code" id="city_code" value="{{ old('city_code') }}">
                <input type="hidden" name="district_code" id="district_code" value="{{ old('district_code') }}">
                <input type="hidden" name="village_code" id="village_code" value="{{ old('village_code') }}">
                <input type="hidden" name="destination_id" id="destination_id" value="{{ old('destination_id') }}">

                <div class="mt-4 flex items-center">
                    <input type="checkbox" name="is_default" id="is_default" value="1"
                           class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                           {{ old('is_default') ? 'checked' : '' }}>
                    <label for="is_default" class="ml-2 text-gray-700 text-sm">Jadikan alamat default</label>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('addresses.index') }}"
                       class="px-5 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-primary-700 text-white font-semibold rounded-lg hover:bg-primary-800">
                        Simpan Alamat
                    </button>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
    <script src="{{ asset('js/address-form.js') }}"></script>
    @endpush
</x-layout>
