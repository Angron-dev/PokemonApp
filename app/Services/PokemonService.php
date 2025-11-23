<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;

final class PokemonService
{
    public static function getPokemonFromFile(string $file): array
    {
        $json = Storage::get($file);
        return json_decode($json, true);
    }
    public static function savePokemonInFile(string $file, array $bannedPokemons): void
    {
        Storage::put($file, json_encode($bannedPokemons, JSON_PRETTY_PRINT));
    }

    public static function removePokemonFromFile(string $file, string $pokemonName): array
    {
        $bannedPokemons = array_filter(self::getPokemonFromFile($file), function($p) use ($pokemonName) {
            return strtolower($p) !== strtolower($pokemonName);
        });
        Storage::put($file, json_encode(array_values($bannedPokemons), JSON_PRETTY_PRINT));

        return $bannedPokemons;
    }
}
