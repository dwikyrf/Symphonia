<x-layout>
    <x-slot:title>Tambah Alamat</x-slot:title>

    <section class="bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-6 bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-bold text-center mb-6 text-gray-900">Tambah Alamat Baru</h2>

            {{-- VALIDATION ERROR --}}
            @if ($errors->any())
                <div class="mb-6 px-4 py-3 rounded border border-red-400 bg-red-100 text-red-700">
                    <strong>Validasi Gagal!</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf

                {{-- Nama & HP --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium mb-1" for="recipient_name">Nama Penerima</label>
                        <input id="recipient_name" name="recipient_name" required
                               value="{{ old('recipient_name') }}"
                               class="w-full p-3 border rounded-lg @error('recipient_name') border-red-500 @enderror"
                               placeholder="Nama Lengkap">
                        @error('recipient_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="phone">Nomor HP</label>
                        <input id="phone" name="phone" required
                               value="{{ old('phone') }}"
                               class="w-full p-3 border rounded-lg @error('phone') border-red-500 @enderror"
                               placeholder="08xxxxxxxxxx">
                        @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Alamat jalan --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1" for="address">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="3" required
                              class="w-full p-3 border rounded-lg @error('address') border-red-500 @enderror"
                              placeholder="Jl. Contoh No. 123, Perumahan ABC">{{ old('address') }}</textarea>
                    @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- PROVINSI --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1" for="province">Provinsi</label>
                    <select id="province" name="province_code" required
                            class="w-full p-3 border rounded-lg @error('province_code') border-red-500 @enderror">
                        <option value="" selected disabled>Pilih Provinsi</option>
                    </select>
                    @error('province_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- KOTA --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1" for="city">Kota/Kabupaten</label>
                    <select id="city" name="city_code" required disabled
                            class="w-full p-3 border rounded-lg @error('city_code') border-red-500 @enderror">
                        <option value="" selected disabled>Pilih Kota/Kabupaten</option>
                    </select>
                    @error('city_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- KECAMATAN --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1" for="district">Kecamatan</label>
                    <select id="district" name="district_code" required disabled
                            class="w-full p-3 border rounded-lg @error('district_code') border-red-500 @enderror">
                        <option value="" selected disabled>Pilih Kecamatan</option>
                    </select>
                    @error('district_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- KELURAHAN --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1" for="village">Kelurahan/Desa</label>
                    <select id="village" name="village_code" required disabled
                            class="w-full p-3 border rounded-lg @error('village_code') border-red-500 @enderror">
                        <option value="" selected disabled>Pilih Kelurahan</option>
                    </select>
                    @error('village_code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- KODE-POS --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1" for="postal_code">Kode Pos</label>
                    <input id="postal_code" name="postal_code" readonly required
                           class="w-full p-3 border rounded-lg bg-gray-100"
                           placeholder="Pilih kelurahan untuk kode pos">
                </div>

                {{-- dropdown kalau kodepos >1 --}}
                <div id="postal_dropdown_wrapper" class="mt-2 hidden">
                    <label for="postal_dropdown" class="block text-sm font-medium">Pilih Kode Pos</label>
                    <select id="postal_dropdown" class="w-full p-2 border rounded-lg mt-1"></select>
                </div>

                {{-- HIDDEN KODE & NAMA WILAYAH --}}
                <input type="hidden" id="province_name"  name="province"  value="{{ old('province') }}">
                <input type="hidden" id="city_name"      name="city"      value="{{ old('city') }}">
                <input type="hidden" id="district_name"  name="district"  value="{{ old('district') }}">
                <input type="hidden" id="village_name"   name="village"   value="{{ old('village') }}">
                <input type="hidden" id="destination_id" name="destination_id" value="{{ old('destination_id') }}">

                {{-- juga hidden kode (sudah ada di select) --}}
                <input type="hidden" id="province_code"  name="province_code"  value="{{ old('province_code') }}">
                <input type="hidden" id="city_code"      name="city_code"      value="{{ old('city_code') }}">
                <input type="hidden" id="district_code"  name="district_code"  value="{{ old('district_code') }}">
                <input type="hidden" id="village_code"   name="village_code"   value="{{ old('village_code') }}">

                {{-- DEFAULT CHECKBOX --}}
                <div class="mt-4 flex items-center">
                    <input id="is_default" type="checkbox" name="is_default" value="1"
                           class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                           {{ old('is_default') ? 'checked' : '' }}>
                    <label for="is_default" class="ml-2 text-sm text-gray-700">Jadikan alamat default</label>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="mt-6 flex justify-between">
                    <a href="{{ route('addresses.index') }}"
                       class="px-5 py-2 border border-gray-300 rounded-lg text-gray-600 hover:text-gray-900">
                        Batal
                    </a>
                    <button class="px-6 py-2 bg-primary-700 hover:bg-primary-800 text-white font-semibold rounded-lg">
                        Simpan Alamat
                    </button>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const API = '6b146801fe79866e070eb82cbfecc71a229787041b3194e020fd10efee35ec8d';

        /* ELEMENTS */
        const prov   = document.getElementById('province');
        const city   = document.getElementById('city');
        const dist   = document.getElementById('district');
        const vill   = document.getElementById('village');
        const postal = document.getElementById('postal_code');

        const wrapPos = document.getElementById('postal_dropdown_wrapper');
        const selPos  = document.getElementById('postal_dropdown');

        const nameInp = {
            prov : document.getElementById('province_name'),
            city : document.getElementById('city_name'),
            dist : document.getElementById('district_name'),
            vill : document.getElementById('village_name'),
        };
        const codeInp = {
            prov : document.getElementById('province_code'),
            city : document.getElementById('city_code'),
            dist : document.getElementById('district_code'),
            vill : document.getElementById('village_code'),
            dest : document.getElementById('destination_id'),
        };

        /* HELPERS */
        const reset = (el, ph) => {
            el.innerHTML = `<option value="" disabled selected>${ph}</option>`;
            el.disabled  = true;
        };
        const fill  = (el, list) => {
            reset(el,'Pilih salah satu');
            list.forEach(i => el.insertAdjacentHTML('beforeend',
                `<option value="${i.id}">${i.name}</option>`));
            el.disabled = false;
        };

        /* LOAD PROVINCE */
        fetch(`https://api.binderbyte.com/wilayah/provinsi?api_key=${API}`)
            .then(r=>r.json()).then(r=>fill(prov,r.value));

        /* CHAIN CHANGE */
        prov.addEventListener('change', () => {
            codeInp.prov.value = prov.value;
            nameInp.prov.value = prov.options[prov.selectedIndex].text;

            reset(city,'Pilih Kota'); reset(dist,'Pilih Kecamatan'); reset(vill,'Pilih Kelurahan');
            postal.value=''; wrapPos.classList.add('hidden');

            fetch(`https://api.binderbyte.com/wilayah/kabupaten?api_key=${API}&id_provinsi=${prov.value}`)
                .then(r=>r.json()).then(r=>fill(city,r.value));
        });

        city.addEventListener('change', () => {
            codeInp.city.value = city.value;
            nameInp.city.value = city.options[city.selectedIndex].text;

            reset(dist,'Pilih Kecamatan'); reset(vill,'Pilih Kelurahan');
            postal.value=''; wrapPos.classList.add('hidden');

            fetch(`https://api.binderbyte.com/wilayah/kecamatan?api_key=${API}&id_kabupaten=${city.value}`)
                .then(r=>r.json()).then(r=>fill(dist,r.value));
        });

        dist.addEventListener('change', () => {
            codeInp.dist.value = dist.value;
            nameInp.dist.value = dist.options[dist.selectedIndex].text;

            reset(vill,'Pilih Kelurahan');
            postal.value=''; wrapPos.classList.add('hidden');

            fetch(`https://api.binderbyte.com/wilayah/kelurahan?api_key=${API}&id_kecamatan=${dist.value}`)
                .then(r=>r.json()).then(r=>fill(vill,r.value));
        });

        vill.addEventListener('change', () => {
            codeInp.vill.value = vill.value;
            nameInp.vill.value = vill.options[vill.selectedIndex].text;

            const keyword = `${vill.options[vill.selectedIndex].text}, ${dist.options[dist.selectedIndex].text}`;

            fetch(`/get-komerce-postal?village=${encodeURIComponent(keyword)}`)
                .then(r=>r.json())
                .then(res=>{
                    const list = res.data ?? res;
                    if(list.length>1){
                        wrapPos.classList.remove('hidden');
                        selPos.innerHTML='';
                        list.forEach(i=> selPos.insertAdjacentHTML('beforeend',
                            `<option value="${i.zip_code}" data-id="${i.id}">${i.zip_code} – ${i.label}</option>`));
                        /* default */
                        postal.value      = list[0].zip_code;
                        codeInp.dest.value= list[0].id;
                        selPos.onchange=()=>{
                            postal.value      = selPos.value;
                            codeInp.dest.value= selPos.options[selPos.selectedIndex].dataset.id;
                        };
                    }else if(list.length===1){
                        wrapPos.classList.add('hidden');
                        postal.value      = list[0].zip_code;
                        codeInp.dest.value= list[0].id;
                    }else{
                        wrapPos.classList.add('hidden');
                        postal.value='Tidak ditemukan';
                    }
                }).catch(()=>{postal.value='Gagal memuat'; wrapPos.classList.add('hidden');});
        });
    });
    </script>
    @endpush
</x-layout>
