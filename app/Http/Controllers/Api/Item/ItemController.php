<?php

namespace App\Http\Controllers\Api\Item;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Item\ItemService;
use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;

class ItemController extends Controller
{
    public function __construct(
        private ItemService $itemService
    ) {}

    public function categories(): JsonResponse
    {
        return response()->json([
            'message' => 'categories.retrieved',
            'data' => $this->itemService->getAllCategories(),
        ]);
    }

    public function subcategories(int $categoryId): JsonResponse
    {
        return response()->json([
            'message' => 'subcategories.retrieved',
            'data' => $this->itemService->getSubcategoriesByCategory($categoryId),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'items.retrieved',
            'data' => $this->itemService->listForUser($request->user()),
        ]);
    }

    public function store(StoreItemRequest $request): JsonResponse
    {
        $item = $this->itemService->create(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'item.created',
            'data' => $item,
        ], 201);
    }

    public function show(Request $request, Item $item): JsonResponse
    {
        return response()->json([
            'message' => 'item.retrieved',
            'data' => $this->itemService->show($request->user(), $item),
        ]);
    }

    public function update(UpdateItemRequest $request, Item $item): JsonResponse
    {
        $item = $this->itemService->update(
            $request->user(),
            $item,
            $request->validated()
        );

        return response()->json([
            'message' => 'item.updated',
            'data' => $item,
        ]);
    }

    public function destroy(Request $request, Item $item): JsonResponse
    {
        $this->itemService->delete($request->user(), $item);

        return response()->json([
            'message' => 'item.deleted',
            'data' => null,
        ]);
    }

    public function addSubPhotos(Request $request, Item $item): JsonResponse
    {
        $validated = $request->validate([
            'sub_photos' => ['required', 'array'],
            'sub_photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $item = $this->itemService->addSubPhotos(
            $request->user(),
            $item,
            $validated['sub_photos']
        );

        return response()->json([
            'message' => 'item.sub_photos_added',
            'data' => $item,
        ]);
    }

    public function replaceMainPhoto(Request $request, Item $item): JsonResponse
    {
        $validated = $request->validate([
            'main_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $item = $this->itemService->replaceMainPhoto(
            $request->user(),
            $item,
            $validated['main_photo']
        );

        return response()->json([
            'message' => 'item.main_photo_replaced',
            'data' => $item,
        ]);
    }
}
