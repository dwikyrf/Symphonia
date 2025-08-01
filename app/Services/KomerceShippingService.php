<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KomerceShippingService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('KOMERCE_API_KEY');
        $this->baseUrl = 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1/';
    }

    public function searchDestination(string $keyword)
    {
        return Http::withHeaders([
            'x-api-key' => $this->apiKey,
        ])->get($this->baseUrl . 'destination/search', [
            'keyword' => $keyword
        ])->json('data') ?? [];
    }

    public function calculateShipping(array $params)
{
    $query = http_build_query([
        'shipper_destination_id' => $params['origin_id'],
        'receiver_destination_id' => $params['destination_id'],
        'weight' => $params['weight'],
        'item_value' => $params['item_value'],
        'cod' => 'no'
    ]);

    $url = $this->baseUrl . 'calculate?' . $query;

    $response = Http::withHeaders([
        'x-api-key' => $this->apiKey,
    ])->get($url);

    return $response->json();
}

}
