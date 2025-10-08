<?php

namespace App\Http\Controllers;

use App\Http\Requests\Favorite\StoreRequest;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $favorites = $user->favorites;

        return response()->json($favorites);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $user = Auth::user();

        $favorite = Favorite::firstOrCreate(
            ['name' => $request->name],
            ['image_url' => $request->image_url]
        );

        $user->favorites()->syncWithoutDetaching($favorite->id);

        return response()->json(['message' => 'Favorite added successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite): JsonResponse
    {
        $user = Auth::user();
        $user->favorites()->detach($favorite->id);

        return response()->json(['message' => 'Favorite removed successfully.']);
    }
}
