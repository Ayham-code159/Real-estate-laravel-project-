<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Item\AdminItemManagementService;

class ItemManagementController extends Controller
{
    public function __construct(
        private AdminItemManagementService $itemService
    ) {}

    public function index()
    {
        $items = $this->itemService->paginatedItems();

        return view('admin.items.index', compact('items'));
    }

    public function show(Item $item)
    {
        $item = $this->itemService->getItemDetails($item);

        return view('admin.items.show', compact('item'));
    }

    public function updateStatus(Request $request, Item $item)
    {
        $data = $request->validate([
            'status' => ['required', 'integer'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->itemService->updateStatus($item, $data);

        return redirect()
            ->route('admin.items.show', $item)
            ->with('success', 'Item status updated successfully.');
    }

    public function destroy(Item $item)
    {
        $this->itemService->delete($item);

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
