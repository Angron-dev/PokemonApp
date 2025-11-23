<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

final class PokeApiService
{
    const API_URL = 'https://pokeapi.co/api/v2/';

    public static function getPokemons(int|null $limit = null): array
    {
        if ($limit) {
            $url = self::API_URL . 'pokemon?limit=' . $limit;
        } else {
            $url = self::API_URL . 'pokemon';
        }
        $response = Http::get($url);
        return $response->json();
    }
}
