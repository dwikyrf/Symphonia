<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KomerceProxyController extends Controller
{
    protected string $baseUrl = 'https://api-sandbox.collaborator.komerce.id/v1/location';

    protected function komerceRequest(string $endpoint, array $params = [])
    {
        $response = Http::withToken(env('KOMERCE_API_KEY'))
            ->get("{$this->baseUrl}/{$endpoint}", $params);

        if (!$response->successful()) {
            Log::error("Komerce API Error [{$endpoint}]: " . $response->body());
            return response()->json([
                'error' => 'Failed to fetch data from Komerce API',
                'status' => $response->status(),
                'body' => $response->body(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    public function provinces()
    {
        return $this->komerceRequest('provinces');
    }

    public function cities(Request $request)
    {
        $request->validate(['province_code' => 'required|string']);
        return $this->komerceRequest('cities', ['province_code' => $request->province_code]);
    }

    public function districts(Request $request)
    {
        $request->validate(['city_code' => 'required|string']);
        return $this->komerceRequest('districts', ['city_code' => $request->city_code]);
    }

    public function postalCodes(Request $request)
    {
        $request->validate(['district_code' => 'required|string']);
        return $this->komerceRequest('postal-codes', ['district_code' => $request->district_code]);
    }
}
