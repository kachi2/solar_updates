<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class GeocodingService
{
    /**
     * Look up the latitude/longitude of an address with the Google Geocoding API.
     *
     * @return array{lat: float, lng: float}|null
     */
    public function locate(string $address): ?array
    {
        try {
            $response = Http::timeout(15)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'key' => config('services.google_maps.key'),
                'address' => $address,
            ]);
        } catch (ConnectionException) {
            return null;
        }

        if ($response->json('status') !== 'OK') {
            return null;
        }

        return [
            'lat' => (float) $response->json('results.0.geometry.location.lat'),
            'lng' => (float) $response->json('results.0.geometry.location.lng'),
        ];
    }
}
