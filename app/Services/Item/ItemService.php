<?php

namespace App\Services\Item;

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\BusinessAccount;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ItemService
{
    public function __construct(
        private ItemDynamicFieldValidationService $dynamicFieldValidationService
    ) {}

    public function getAllCategories(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->withCount('subcategories')
            ->orderBy(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en')
            ->get();
    }

    public function getSubcategoriesByCategory(int $categoryId): Collection
    {
        $category = $this->getActiveCategory($categoryId);

        return Subcategory::query()
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->with([
                'fields' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])
            ->orderBy(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en')
            ->get();
    }

    public function create(User $user, array $data): Item
    {
        $businessAccount = $this->getApprovedActiveBusinessAccount($user);

        $category = $this->getActiveCategory((int) $data['category_id']);
        $subcategory = $this->getValidSubcategory($category, $data['subcategory_id'] ?? null);

        $dynamicValues = $this->dynamicFieldValidationService->validate(
            $subcategory,
            $data['dynamic_values'] ?? []
        );

        $titleEn = trim($data['title_en']);
        $titleAr = isset($data['title_ar']) ? trim((string) $data['title_ar']) : null;

        $item = Item::create([
            'business_account_id' => $businessAccount->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory?->id,

            'title' => $titleEn,
            'title_en' => $titleEn,
            'title_ar' => $titleAr ?: null,

            'description' => $data['description_en'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'description_ar' => $data['description_ar'] ?? null,

            'item_type' => $data['item_type'],

            'price_usd' => (float) $data['price_usd'],
            'price_syp' => (float) $data['price_syp'],

            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
            'location_label' => isset($data['location_label']) ? trim((string) $data['location_label']) : null,

            'dynamic_values' => $dynamicValues,

            'status' => Item::STATUS_PENDING,
            'rejection_reason' => null,
        ]);

        $this->syncMedia($item, $data);

        return $this->freshItem($item);
    }

    public function update(User $user, Item $item, array $data): Item
    {
        $this->ensureItemBelongsToUser($user, $item);

        $category = $this->getActiveCategory((int) $data['category_id']);
        $subcategory = $this->getValidSubcategory($category, $data['subcategory_id'] ?? null);

        $dynamicValues = $this->dynamicFieldValidationService->validate(
            $subcategory,
            $data['dynamic_values'] ?? []
        );

        $titleEn = trim($data['title_en']);
        $titleAr = isset($data['title_ar']) ? trim((string) $data['title_ar']) : null;

        $item->update([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory?->id,

            'title' => $titleEn,
            'title_en' => $titleEn,
            'title_ar' => $titleAr ?: null,

            'description' => $data['description_en'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'description_ar' => $data['description_ar'] ?? null,

            'item_type' => $data['item_type'],

            'price_usd' => (float) $data['price_usd'],
            'price_syp' => (float) $data['price_syp'],

            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
            'location_label' => isset($data['location_label']) ? trim((string) $data['location_label']) : null,

            'dynamic_values' => $dynamicValues,

            'status' => Item::STATUS_PENDING,
            'rejection_reason' => null,
        ]);

        $this->syncMedia($item, $data);

        return $this->freshItem($item);
    }

    public function listForUser(User $user): Collection
    {
        return Item::query()
            ->with([
                'businessAccount.businessType',
                'businessAccount.city',
                'category',
                'subcategory',
            ])
            ->whereHas('businessAccount', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();
    }

    public function show(User $user, Item $item): Item
    {
        $this->ensureItemBelongsToUser($user, $item);

        return $this->freshItem($item);
    }

    public function delete(User $user, Item $item): void
    {
        $this->ensureItemBelongsToUser($user, $item);

        $item->delete();
    }

    public function addSubPhotos(User $user, Item $item, array $photos): Item
    {
        $this->ensureItemBelongsToUser($user, $item);

        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $item
                    ->addMedia($photo)
                    ->toMediaCollection('sub_photos');
            }
        }

        return $this->freshItem($item);
    }

    public function replaceMainPhoto(User $user, Item $item, UploadedFile $photo): Item
    {
        $this->ensureItemBelongsToUser($user, $item);

        $item->clearMediaCollection('main_photo');

        $item
            ->addMedia($photo)
            ->toMediaCollection('main_photo');

        return $this->freshItem($item);
    }

    private function getApprovedActiveBusinessAccount(User $user): BusinessAccount
    {
        if (! $user->active_business_account_id) {
            throw ValidationException::withMessages([
                'business_account' => ['You do not have an active business account selected.'],
            ]);
        }

        $businessAccount = $user->activeBusinessAccount()
            ->with(['businessType', 'city'])
            ->first();

        if (! $businessAccount) {
            throw ValidationException::withMessages([
                'business_account' => ['Your active business account was not found.'],
            ]);
        }

        if ($businessAccount->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'business_account' => ['The selected active business account does not belong to you.'],
            ]);
        }

        if (! $businessAccount->isApproved()) {
            throw ValidationException::withMessages([
                'business_account' => ['You can only add items through an approved active business account.'],
            ]);
        }

        return $businessAccount;
    }

    private function getActiveCategory(int $categoryId): Category
    {
        $category = Category::query()
            ->withCount('subcategories')
            ->findOrFail($categoryId);

        if (! $category->is_active) {
            throw ValidationException::withMessages([
                'category_id' => ['The selected category is inactive.'],
            ]);
        }

        return $category;
    }

    private function getValidSubcategory(Category $category, mixed $subcategoryId): ?Subcategory
    {
        if ($category->subcategories_count > 0 && ! $subcategoryId) {
            throw ValidationException::withMessages([
                'subcategory_id' => ['This category has subcategories, so subcategory_id is required.'],
            ]);
        }

        if (! $subcategoryId) {
            return null;
        }

        $subcategory = Subcategory::query()
            ->where('category_id', $category->id)
            ->where('id', (int) $subcategoryId)
            ->first();

        if (! $subcategory) {
            throw ValidationException::withMessages([
                'subcategory_id' => ['The selected subcategory does not belong to the selected category.'],
            ]);
        }

        if (! $subcategory->is_active) {
            throw ValidationException::withMessages([
                'subcategory_id' => ['The selected subcategory is inactive.'],
            ]);
        }

        return $subcategory;
    }

    private function ensureItemBelongsToUser(User $user, Item $item): void
    {
        $businessAccount = $item->businessAccount;

        if (! $businessAccount || $businessAccount->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'item' => ['This item does not belong to you.'],
            ]);
        }
    }

    private function syncMedia(Item $item, array $data): void
    {
        if (isset($data['main_photo']) && $data['main_photo'] instanceof UploadedFile) {
            $item->clearMediaCollection('main_photo');

            $item
                ->addMedia($data['main_photo'])
                ->toMediaCollection('main_photo');
        }

        if (isset($data['sub_photos']) && is_array($data['sub_photos'])) {
            foreach ($data['sub_photos'] as $photo) {
                if ($photo instanceof UploadedFile) {
                    $item
                        ->addMedia($photo)
                        ->toMediaCollection('sub_photos');
                }
            }
        }
    }

    private function freshItem(Item $item): Item
    {
        return $item->fresh([
            'businessAccount.businessType',
            'businessAccount.city',
            'category',
            'subcategory',
        ]);
    }
}
