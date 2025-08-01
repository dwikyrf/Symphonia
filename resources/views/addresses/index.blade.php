<x-layout>
    <x-slot:title>Daftar Alamat Saya</x-slot:title>

    <section class="bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Alamat Saya</h2>

            <div class="mt-4">
                <a href="{{ route('addresses.create') }}"
                   class="bg-primary-700 text-white px-4 py-2 rounded-lg hover:bg-primary-800">
                    + Tambah Alamat Baru
                </a>
            </div>

            <div class="mt-6 space-y-4">
                @forelse ($addresses as $address)
                    <div class="bg-white shadow-lg rounded-lg p-6 relative">
                        @if($address->is_default)
                            <span class="absolute top-4 right-4 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded-full">Default</span>
                        @endif

                        <p class="font-semibold text-gray-900">{{ $address->recipient_name }}</p>
                        <p class="text-gray-600">Telp: {{ $address->phone }}</p>
                        <p class="text-gray-600">{{ $address->fullAddress() }}</p> {{-- Menggunakan fullAddress() --}}
                        <p class="text-gray-600">Kode Pos: {{ $address->postal_code }}</p>

                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('addresses.edit', $address->id) }}"
                               class="px-3 py-1 text-sm text-blue-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                                Edit
                            </a>

                            @if (!$address->is_default)
                                <form action="{{ route('addresses.destroy', $address->id) }}" method="POST"
                                      onsubmit="return confirm('Anda yakin ingin menghapus alamat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 text-sm text-red-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                                        Hapus
                                    </button>
                                </form>

                                <form action="{{ route('addresses.set-default', $address->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1 text-sm text-green-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                                        Jadikan Default
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-lg rounded-lg p-6 text-center text-gray-500">
                        Belum ada alamat tersimpan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layout>