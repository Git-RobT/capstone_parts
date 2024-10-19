<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class BlockCountry
{
    public function handle($request, Closure $next)
    {
        // Get the public IP address
        $publicIp = Http::get('http://ipinfo.io/ip')->body();

        $client = new Client();
        $response = $client->get('https://api.ipgeolocation.io/ipgeo', [
            'query' => [
                'apiKey' => 'f3faf5ad6a2f4e049e397c944468d2ec', // Replace with your IPGeolocation.io API key
                'ip' => $publicIp,
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $countryCode = $data['country_code2'];
        $allowedCountries = ['PH']; // List of blocked country codes

        if (!in_array($countryCode, $allowedCountries)) {
            return response('Access denied', 403);
        }

        return $next($request);
    }
}
