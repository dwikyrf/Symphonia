<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search?keyword=';
    }

    public function getProvinces()
    {
        return Http::withHeaders([
            'x-api-key' => $this->apiKey,
        ])->get($this->baseUrl . 'province')->json();
    }

    public function getCities($provinceId)
    {
        return Http::withHeaders([
            'key' => $this->apiKey,
        ])->get($this->baseUrl . 'city?province=' . $provinceId)->json();
    }

    public function getCosts($origin, $destination, $weight, $courier = 'jne')
    {
        return Http::withHeaders([
            'key' => $this->apiKey,
        ])->post($this->baseUrl . 'cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight, // dalam gram
            'courier' => $courier,
        ])->json();
    }
}
