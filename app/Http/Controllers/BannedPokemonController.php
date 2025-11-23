<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PokemonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannedPokemonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('admin');

        $apiKey = $request->header('X-Api-Key');

        if ($apiKey === config('service.api_key')){
            return response()->json(PokemonService::getPokemonFromFile('banned-pokemon.json'));
        } else {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->authorize('admin');

        $apiKey = $request->header('X-Api-Key');

        if ($apiKey === config('service.api_key')){
            PokemonService::removePokemonFromFile('banned-pokemon.json', $request->name);
            return response()->json([
                'message' => 'Pokemon removed'
            ]);
        } else {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    }

    public function store(Request $request)
    {
        $this->authorize('admin');

        $apiKey = $request->header('X-Api-Key');

        if ($apiKey === config('service.api_key')){
            PokemonService::savePokemonInFile('banned_pokemon.json', $request->all());
            return response()->json([
                'message' => 'Pokemon Added'
            ]);
        } else {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    }
}
