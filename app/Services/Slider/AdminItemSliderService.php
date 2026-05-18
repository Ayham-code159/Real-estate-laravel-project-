<?php

namespace App\Services\Slider;

use App\Models\ItemSlider;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminItemSliderService
{
    public function paginatedSliders(?int $categoryId = null): LengthAwarePaginator
    {
        return ItemSlider::query()
            ->with([
                'item.businessAccount',
                'item.category',
                'item.subcategory',
                'item.media',
            ])
            ->whereHas('item', function ($query) use ($categoryId) {
                $query->where('status', 2);

                if ($categoryId) {
                    $query->where('category_id', $categoryId);
                }
            })
            ->latest()
            ->paginate(20);
    }

    public function categories()
    {
        return Category::query()
            ->whereHas('items.slider')
            ->orderBy(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en')
            ->get();
    }

    public function getDetails(ItemSlider $slider): ItemSlider
    {
        return $slider->load([
            'item.businessAccount.user',
            'item.businessAccount.businessType',
            'item.businessAccount.city',
            'item.category',
            'item.subcategory',
            'item.media',
        ]);
    }

    public function toggleActive(ItemSlider $slider): ItemSlider
    {
        $slider->update([
            'is_active' => ! $slider->is_active,
        ]);

        return $this->getDetails($slider);
    }

    public function update(ItemSlider $slider, array $data): ItemSlider
    {
        $slider->update([
            'priority' => $data['priority'],
            'admin_note' => $data['admin_note'] ?? null,
            'is_active' => $data['is_active'] ?? false,
        ]);

        return $this->getDetails($slider);
    }

    public function increaseClickCount(ItemSlider $slider): void
    {
        $slider->increment('click_count');
    }

    public function delete(ItemSlider $slider): void
    {
        $slider->delete();
    }
}
