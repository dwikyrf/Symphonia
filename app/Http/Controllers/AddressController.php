<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    // 📌 Menampilkan daftar alamat user
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->get();
        return view('addresses.index', compact('addresses'));
    }

    // 📌 Form Tambah Alamat
    public function create()
    {
        return view('addresses.create');
    }

    // 📌 Menyimpan Alamat Baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_name'   => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string|max:500', // Alamat jalan, RT/RW, dll.
            'province'         => 'required|string|max:100', // Nama Provinsi
            'province_code'    => 'required|string|max:20', // Kode Provinsi
            'city'             => 'required|string|max:100', // Nama Kota/Kabupaten
            'city_code'        => 'required|string|max:20', // Kode Kota/Kabupaten
            'district'         => 'required|string|max:100', // Nama Kecamatan
            'district_code'    => 'required|string|max:20', // Kode Kecamatan
            // 'village'          => 'nullable|string|max:100', // Opsional jika Anda punya input kelurahan/desa
            // 'village_code'     => 'nullable|string|max:20', // Opsional
            'postal_code'      => 'required|string|max:10',
            'destination_id'   => 'required|string|max:20', // destination_id akan menjadi district_code
            'is_default'       => 'boolean', // Ini akan otomatis handle jika checkbox ada/tidak
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = Auth::user();

        // Jika alamat ini diatur sebagai default, set semua alamat lain user menjadi non-default
        if ($request->has('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        // Buat alamat baru
        $user->addresses()->create([
            'recipient_name'   => $request->recipient_name,
            'phone'            => $request->phone,
            'address'          => $request->address,
            'province'         => $request->province,
            'province_code'    => $request->province_code,
            'city'             => $request->city,
            'city_code'        => $request->city_code,
            'district'         => $request->district,
            'district_code'    => $request->district_code,
            // 'village'          => $request->village, // Tambahkan jika ada input
            // 'village_code'     => $request->village_code, // Tambahkan jika ada input
            'postal_code'      => $request->postal_code,
            'destination_id'   => $request->destination_id, // Menggunakan district_code sebagai destination_id
            'is_default'       => $request->has('is_default'),
        ]);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil ditambahkan.');
    }




    // 📌 Form Edit Alamat
    public function edit(Address $address)
    {
        $this->authorizeAddress($address);
        return view('addresses.edit', compact('address'));
    }

    // 📌 Update Alamat
     public function update(Request $request, Address $address)
    {
        $this->authorizeAddress($address);

        $validator = Validator::make($request->all(), [
            'recipient_name'   => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string|max:500',
            'province'         => 'required|string|max:100',
            'province_code'    => 'required|string|max:20',
            'city'             => 'required|string|max:100',
            'city_code'        => 'required|string|max:20',
            'district'         => 'required|string|max:100',
            'district_code'    => 'required|string|max:20',
            // 'village'          => 'nullable|string|max:100', // Opsional
            // 'village_code'     => 'nullable|string|max:20', // Opsional
            'postal_code'      => 'required|string|max:10',
            'destination_id'   => 'required|string|max:20', // destination_id akan menjadi district_code
            'is_default'       => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // Jika alamat ini diatur sebagai default, set semua alamat lain user menjadi non-default
        if ($request->has('is_default')) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        $address->update([
            'recipient_name'   => $request->recipient_name,
            'phone'            => $request->phone,
            'address'          => $request->address,
            'province'         => $request->province,
            'province_code'    => $request->province_code,
            'city'             => $request->city,
            'city_code'        => $request->city_code,
            'district'         => $request->district,
            'district_code'    => $request->district_code,
            // 'village'          => $request->village, // Tambahkan jika ada input
            // 'village_code'     => $request->village_code, // Tambahkan jika ada input
            'postal_code'      => $request->postal_code,
            'destination_id'   => $request->destination_id,
            'is_default'       => $request->has('is_default'),
        ]);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diperbarui.');
    }



    // 📌 Hapus Alamat
    public function destroy(Address $address)
    {
        $this->authorizeAddress($address);

        if ($address->is_default) {
            return redirect()->route('addresses.index')->with('error', 'Alamat default tidak bisa dihapus.');
        }

        $address->delete();
        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil dihapus.');
    }

    // 📌 Mengatur Alamat Default
     // Mengatur Alamat Default
    public function setDefault(Address $address)
    {
        $this->authorizeAddress($address);

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('addresses.index')->with('success', 'Alamat default berhasil diubah.');
    }
    // 📌 Fungsi untuk validasi kepemilikan alamat
    private function authorizeAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
    // AddressController.php

public function getKomercePostal(Request $request)
{
    /* ───── Ambil nama kecamatan & desa dari query ───── */
    $district = trim($request->query('district'));   // kecamatan
    $village  = trim($request->query('village'));    // kelurahan/desa

    if (!$district && !$village) {
        return response()->json(['message' => 'Parameter district / village kosong'], 400);
    }

    // helper anonim untuk mem‐anggil API sekali
    $search = function (string $keyword) {
        return Http::withHeaders([
                'x-api-key' => env('KOMERCE_API_KEY')
            ])->get(
                'https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search',
                ['keyword' => $keyword]
            );
    };

    /* ───── 1) coba desa + kecamatan ───── */
    $keyword1 = trim("$village $district");          // “Kapalo Koto Pauh”
    $response = $search($keyword1);

    $data = $response->successful()
            ? ($response->json('data') ?? [])
            : [];

    /* ───── 2) fallback: hanya kecamatan ───── */
    if (empty($data) && $district) {
        $response = $search($district);              // “Pauh”
        $data = $response->successful()
                ? ($response->json('data') ?? [])
                : [];
    }

    /* ───── hasil ───── */
    return response()->json([
        'data'    => $data,
        'keyword' => empty($data) ? $district : $keyword1, // info debugging
    ]);
}
    // public function getKomercePostal(Request $request)
    // {
    //     $keyword = $request->query('village');

    //     if (!$keyword) {
    //         return response()->json(['message' => 'Keyword kelurahan tidak valid'], 400);
    //     }

    //     try {
    //         $response = Http::withHeaders([
    //             'x-api-key' => env('KOMERCE_API_KEY') // ✅ Ubah ini
    //         ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
    //             'keyword' => $keyword
    //         ]);

    //         if ($response->successful()) {
    //             return response()->json(['data' => $response->json('data') ?? []]);
    //         }

    //         return response()->json(['message' => 'Gagal ambil data dari Komerce'], $response->status());

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


}
