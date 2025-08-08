<x-layout>
    <x-slot:title>Edit Alamat</x-slot:title>

    <section class="bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-6 bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Edit Alamat</h2>

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

            <form action="{{ route('addresses.update', $address) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- Nama Penerima dan Nomor HP --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="recipient_name" class="block mb-2 text-sm font-medium text-gray-700">Nama Penerima</label>
                        <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}"
                               placeholder="Nama Lengkap"
                               class="w-full p-3 border rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('recipient_name') border-red-500 @enderror" required>
                        @error('recipient_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">Nomor HP</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $address->phone) }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full p-3 border rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('phone') border-red-500 @enderror" required>
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Alamat Lengkap --}}
                <div class="mt-4">
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-700">Alamat Lengkap (Jalan, Rumah, dll)</label>
                    <textarea name="address" id="address" rows="3" placeholder="Jl. Contoh No. 123, Perumahan ABC"
                              class="w-full p-3 border rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('address') border-red-500 @enderror" required>{{ old('address', $address->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label for="province" class="block mb-2 text-sm font-medium text-gray-700">Provinsi</label>
                    <select id="province" name="province_code" class="w-full p-3 border rounded-lg @error('province_code') border-red-500 @enderror" required>
                        <option value="" selected disabled>Memuat provinsi...</option>
                    </select>
                    @error('province_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mt-4">
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-700">Kota/Kabupaten</label>
                    <select id="city" name="city_code" class="w-full p-3 border rounded-lg @error('city_code') border-red-500 @enderror" required disabled>
                        <option value="" selected disabled>Pilih Kota/Kabupaten</option>
                    </select>
                    @error('city_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mt-4">
                    <label for="district" class="block mb-2 text-sm font-medium text-gray-700">Kecamatan</label>
                    <select id="district" name="district_code" class="w-full p-3 border rounded-lg @error('district_code') border-red-500 @enderror" required disabled>
                        <option value="" selected disabled>Pilih Kecamatan</option>
                    </select>
                     @error('district_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mt-4">
                    <label for="village" class="block mb-2 text-sm font-medium text-gray-700">Kelurahan/Desa</label>
                    <select id="village" name="village_code" class="w-full p-3 border rounded-lg @error('village_code') border-red-500 @enderror" required disabled>
                        <option value="" selected disabled>Pilih Kelurahan/Desa</option>
                    </select>
                    @error('village_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mt-4">
                    <label for="postal_code" class="block mb-2 text-sm font-medium text-gray-700">Kode Pos</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address->postal_code) }}"
                           class="w-full p-3 border rounded-lg bg-gray-100 cursor-not-allowed" readonly required placeholder="Pilih kelurahan untuk kodepos">
                </div>
                
                <div class="mt-2 hidden" id="postal_dropdown_wrapper">
                    <label for="postal_dropdown" class="block text-sm font-medium text-gray-700">Pilih Kode Pos yang Benar</label>
                    <select id="postal_dropdown" class="w-full p-2 border rounded-lg mt-1"></select>
                </div>
                
                <input type="hidden" name="province" id="province_name" value="{{ old('province', $address->province) }}">
                <input type="hidden" name="city" id="city_name" value="{{ old('city', $address->city) }}">
                <input type="hidden" name="district" id="district_name" value="{{ old('district', $address->district) }}">
                <input type="hidden" name="village" id="village_name" value="{{ old('village', $address->village) }}">
                <input type="hidden" name="destination_id" id="destination_id" value="{{ old('destination_id', $address->destination_id) }}">
                
                <div class="mt-4 flex items-center">
                    <input type="checkbox" name="is_default" id="is_default" value="1"
                           class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                           {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                    <label for="is_default" class="ml-2 text-gray-700 text-sm">Jadikan alamat utama</label>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('addresses.index') }}"
                       class="px-6 py-2 text-gray-700 hover:text-gray-900 bg-gray-100 border border-gray-300 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-primary-700 text-white font-semibold rounded-lg hover:bg-primary-800 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // --- DEKLARASI ELEMEN ---
        const apiKey = "6b146801fe79866e070eb82cbfecc71a229787041b3194e020fd10efee35ec8d";

        const provEl = document.getElementById("province");
        const cityEl = document.getElementById("city");
        const districtEl = document.getElementById("district");
        const villageEl = document.getElementById("village");
        const postalEl = document.getElementById("postal_code");
        const postalDropdownWrapper = document.getElementById("postal_dropdown_wrapper");
        const postalDropdown = document.getElementById("postal_dropdown");

        const provinceNameInput = document.getElementById("province_name");
        const cityNameInput = document.getElementById("city_name");
        const districtNameInput = document.getElementById("district_name");
        const villageNameInput = document.getElementById("village_name");
        const destinationIdInput = document.getElementById("destination_id");

        const oldProvinceCode = "{{ old('province_code', $address->province_code) }}";
        const oldCityCode = "{{ old('city_code', $address->city_code) }}";
        const oldDistrictCode = "{{ old('district_code', $address->district_code) }}";
        const oldVillageCode = "{{ old('village_code', $address->village_code) }}";

        // --- FUNGSI BANTUAN ---
        const reset = (el, text) => {
            el.innerHTML = `<option value="" selected disabled>${text}</option>`;
            el.disabled = true;
        };
        const populate = (el, items, selectedId = null) => {
            el.innerHTML = `<option value="" selected disabled>Pilih salah satu</option>`;
            items.forEach(i => {
                const opt = document.createElement("option");
                opt.value = i.id;
                opt.textContent = i.name;
                el.appendChild(opt);
            });
            if (selectedId) el.value = selectedId;
            el.disabled = false;
        };

        // --- ALUR LOGIKA ---
        fetch(`https://api.binderbyte.com/wilayah/provinsi?api_key=${apiKey}`)
            .then(res => res.json()).then(data => {
                populate(provEl, data.value, oldProvinceCode);
                if (oldProvinceCode) provEl.dispatchEvent(new Event("change"));
            });

        provEl.addEventListener("change", () => {
            provinceNameInput.value = provEl.options[provEl.selectedIndex].text;
            reset(cityEl, "Pilih Kota/Kabupaten");
            reset(districtEl, "Pilih Kecamatan");
            reset(villageEl, "Pilih Kelurahan/Desa");
            postalEl.value = "";
            postalDropdownWrapper.classList.add("hidden");

            const provinceId = provEl.value;
            if (!provinceId) return;

            fetch(`https://api.binderbyte.com/wilayah/kabupaten?api_key=${apiKey}&id_provinsi=${provinceId}`)
                .then(res => res.json()).then(data => {
                    populate(cityEl, data.value, oldCityCode);
                    if (oldCityCode) cityEl.dispatchEvent(new Event("change"));
                });
        });

        cityEl.addEventListener("change", () => {
            cityNameInput.value = cityEl.options[cityEl.selectedIndex].text;
            reset(districtEl, "Pilih Kecamatan");
            reset(villageEl, "Pilih Kelurahan/Desa");
            postalEl.value = "";
            postalDropdownWrapper.classList.add("hidden");

            const cityId = cityEl.value;
            if (!cityId) return;
            
            fetch(`https://api.binderbyte.com/wilayah/kecamatan?api_key=${apiKey}&id_kabupaten=${cityId}`)
                .then(res => res.json()).then(data => {
                    populate(districtEl, data.value, oldDistrictCode);
                    if (oldDistrictCode) districtEl.dispatchEvent(new Event("change"));
                });
        });

        districtEl.addEventListener("change", () => {
            const districtName = districtEl.options[districtEl.selectedIndex].text;
            districtNameInput.value = districtEl.options[districtEl.selectedIndex].text;
            reset(villageEl, "Pilih Kelurahan/Desa");
            postalEl.value = "";
            postalDropdownWrapper.classList.add("hidden");
            
            const districtId = districtEl.value;
            if (!districtId) return;

            fetch(`https://api.binderbyte.com/wilayah/kelurahan?api_key=${apiKey}&id_kecamatan=${districtId}`)
                .then(res => res.json()).then(data => {
                    populate(villageEl, data.value, oldVillageCode);
                    if (oldVillageCode) villageEl.dispatchEvent(new Event("change"));
                });
        });
        
        // ** INI BAGIAN YANG DIPERBAIKI **
            villageEl.addEventListener("change", () => {
                 villageNameInput.value = villageEl.options[villageEl.selectedIndex]?.text;
        const kelurahan = villageEl.options[villageEl.selectedIndex]?.text;
        const kecamatan = districtEl.options[districtEl.selectedIndex]?.text;
        const kota = cityEl.options[cityEl.selectedIndex]?.text;

        if (!kelurahan || !kecamatan || !kota) return;

        const keyword = `${kelurahan}, ${kecamatan}`;
        console.log("🔍 Keyword dikirim ke Komerce:", keyword);

        fetch(`/get-komerce-postal?village=${encodeURIComponent(keyword)}`)
                console.log("🔍 Keyword dikirim ke Komerce:", keyword);

            fetch(`/get-komerce-postal?village=${encodeURIComponent(keyword)}`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(response => {
                    const komerceData = response.data ?? response;
                    
                    if (komerceData.length > 1) { // Jika ada lebih dari satu kodepos
                        postalDropdown.innerHTML = ""; // Kosongkan dulu
                        komerceData.forEach(item => {
                            const opt = document.createElement("option");
                            opt.value = item.zip_code;
                            opt.textContent = `${item.zip_code} - ${item.label}`;
                            opt.dataset.destinationId = item.id;
                            postalDropdown.appendChild(opt);
                        });
                        postalDropdownWrapper.classList.remove("hidden");
                        
                        // Set nilai awal dari pilihan pertama
                        postalEl.value = komerceData[0].zip_code;
                        destinationIdInput.value = komerceData[0].id;

                        // Tambahkan listener untuk dropdown kodepos
                        postalDropdown.onchange = () => { // Gunakan onchange agar tidak menumpuk listener
                            postalEl.value = postalDropdown.value;
                            destinationIdInput.value = postalDropdown.options[postalDropdown.selectedIndex].dataset.destinationId;
                        };

                    } else if (komerceData.length === 1) { // Jika hanya ada satu kodepos
                        postalEl.value = komerceData[0].zip_code || "Tidak ditemukan";
                        destinationIdInput.value = komerceData[0].id || "";
                    } else { // Jika tidak ada data
                        postalEl.value = "Tidak ditemukan";
                    }
                })
                .catch(err => {
                    console.error("Gagal mengambil data kodepos:", err);
                    postalEl.value = "Gagal memuat";
                });
        });
    });
    </script>
    @endpush
</x-layout>