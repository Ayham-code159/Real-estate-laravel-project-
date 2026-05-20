<?php

namespace App\Http\Controllers\Api\Item;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Item\ItemFavoriteService;

class ItemFavoriteController extends Controller
{
    public function __construct(
        private ItemFavoriteService $favoriteService
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'favorites.retrieved',
            'data' => $this->favoriteService->list($request->user()),
        ]);
    }

    public function store(Request $request, Item $item): JsonResponse
    {
        return response()->json([
            'message' => 'item.added_to_favorites',
            'data' => $this->favoriteService->add($request->user(), $item),
        ]);
    }

    public function destroy(Request $request, Item $item): JsonResponse
    {
        $this->favoriteService->remove($request->user(), $item);

        return response()->json([
            'message' => 'item.removed_from_favorites',
            'data' => null,
        ]);
    }
}
