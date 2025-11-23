<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PokeApiService;
use App\Services\PokemonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query('limit');
        $pokemons = PokeApiService::getPokemons($limit);
        $bannedPokemon = PokemonService::getPokemonFromFile('banned-pokemon.json');

        if (isset($pokemons['results'])) {
            $filteredResults = array_filter($pokemons['results'], function($pokemon) use ($bannedPokemon) {
                return !in_array(strtolower($pokemon['name']), array_map('strtolower', $bannedPokemon));
            });
            $pokemons['results'] = array_values($filteredResults);
        }
        return response()->json($pokemons);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('admin');
        $apiKey = $request->header('X-Api-Key');

        if ($apiKey === config('service.api_key')) {
            $customPokemons = PokemonService::getPokemonFromFile('custom-pokemon.json');
            if ($this->assertNameInUnique(strtolower($request->name), $customPokemons)){
                return response()->json([
                    'error' => 'Pokemon name must be unique'
                ], 422);
            }
            PokemonService::savePokemonInFile('custom-pokemon.json', $request->all());
            return response()->json([
                'message' => 'Pokemon Added'
            ]);
        }else {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->authorize('admin');
        $apiKey = $request->header('X-Api-Key');

        if ($apiKey === config('service.api_key')) {
            PokemonService::removePokemonFromFile('custom-pokemon.json', $request->name);
            return response()->json([
                'message' => 'Pokemon Removed'
            ]);
        }else {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $this->authorize('admin');
        $apiKey = $request->header('X-Api-Key');

        if ($apiKey === config('service.api_key')) {
            $newName = strtolower($request->name);
            $customPokemons = PokemonService::getPokemonFromFile('custom-pokemon.json');
            if ($this->assertNameInUnique($newName, $customPokemons)){
                return response()->json([
                    'error' => 'Pokemon name must be unique'
                ], 422);
            }

            foreach ($customPokemons as $pokemon) {
                if (strtolower($pokemon['name']) === $newName) {
                    $pokemon['name'] = $newName;
                    PokemonService::savePokemonInFile('custom-pokemon.json', $customPokemons);
                    return response()->json([
                        'message' => 'Pokemon Updated'
                    ]);
                }
            }
            return response()->json([
                'message' => 'Pokemon Updated'
            ]);

        }else {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    }


    private function assertNameInUnique(string $name, array $customPokemons): bool
    {
        $newName = strtolower($name);
        $bannedPokemons = PokemonService::getPokemonFromFile('banned-pokemon.json');
        $pokeApiResponse = PokeApiService::getPokemons();
        $pokeApi = array_map(
            'strtolower',
            array_map(fn($pokemon) => $pokemon['name'], $pokeApiResponse['results'])
        );
        if (in_array($newName, array_map('strtolower', $customPokemons)) ||
            in_array($newName, array_map('strtolower', $bannedPokemons)) ||
            in_array($newName, $pokeApi)) {
            return false;
        }
        return true;
    }
}
