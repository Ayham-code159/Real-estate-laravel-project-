<?php

namespace App\Http\Controllers\Admin;

use App\Models\ItemSlider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Slider\AdminItemSliderService;

class ItemSliderController extends Controller
{
    public function __construct(
        private AdminItemSliderService $sliderService
    ) {}

    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');

        $sliders = $this->sliderService->paginatedSliders(
            $categoryId ? (int) $categoryId : null
        );

        $categories = $this->sliderService->categories();

        return view('admin.sliders.index', compact('sliders', 'categories', 'categoryId'));
    }

    public function show(ItemSlider $slider)
    {
        $this->sliderService->increaseClickCount($slider);

        $slider = $this->sliderService->getDetails($slider);

        return view('admin.sliders.show', compact('slider'));
    }

    public function toggleActive(ItemSlider $slider)
    {
        $this->sliderService->toggleActive($slider);

        return back()->with('success', 'Slider status updated successfully.');
    }

    public function update(Request $request, ItemSlider $slider)
    {
        $data = $request->validate([
            'priority' => ['required', 'string', 'in:normal,high,top'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->sliderService->update($slider, $data);

        return back()->with('success', 'Slider updated successfully.');
    }

    public function destroy(ItemSlider $slider)
    {
        $this->sliderService->delete($slider);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider deleted successfully.');
    }
}
