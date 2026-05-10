<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryField;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CategoryManagementService
{
    public function categories(): Collection
    {
        return Category::query()
            ->with([
                'subcategories.fields' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])
            ->latest()
            ->get();
    }

    public function createCategory(array $data): Category
    {
        $nameEn = trim($data['name_en']);
        $nameAr = isset($data['name_ar']) ? trim((string) $data['name_ar']) : null;

        return Category::create([
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr ?: null,
            'description' => $data['description_en'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'description_ar' => $data['description_ar'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        $nameEn = trim($data['name_en']);
        $nameAr = isset($data['name_ar']) ? trim((string) $data['name_ar']) : null;

        $category->update([
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr ?: null,
            'description' => $data['description_en'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'description_ar' => $data['description_ar'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $category->fresh([
            'subcategories.fields' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);
    }

    public function deleteCategory(Category $category): void
    {
        $category->delete();
    }

    public function createSubcategory(Category $category, array $data): Subcategory
    {
        $nameEn = trim($data['name_en']);
        $nameAr = isset($data['name_ar']) ? trim((string) $data['name_ar']) : null;

        return Subcategory::create([
            'category_id' => $category->id,
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr ?: null,
            'description' => $data['description_en'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'description_ar' => $data['description_ar'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updateSubcategory(Subcategory $subcategory, array $data): Subcategory
    {
        $nameEn = trim($data['name_en']);
        $nameAr = isset($data['name_ar']) ? trim((string) $data['name_ar']) : null;

        $subcategory->update([
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr ?: null,
            'description' => $data['description_en'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'description_ar' => $data['description_ar'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $subcategory->fresh([
            'category',
            'fields' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);
    }

    public function deleteSubcategory(Subcategory $subcategory): void
    {
        $subcategory->delete();
    }

    public function createSubcategoryField(Subcategory $subcategory, array $data): SubcategoryField
    {
        $this->validateFieldRules($data);

        $labelEn = trim($data['label_en']);
        $fieldKey = $this->generateUniqueFieldKey($subcategory, $labelEn);

        return SubcategoryField::create([
            'subcategory_id' => $subcategory->id,
            'field_key' => $fieldKey,
            'label_en' => $labelEn,
            'label_ar' => isset($data['label_ar']) ? trim((string) $data['label_ar']) : null,
            'field_type' => $data['field_type'],
            'is_required' => $data['is_required'] ?? false,
            'options' => $data['options'] ?? null,
            'min_value' => $data['min_value'] ?? null,
            'max_value' => $data['max_value'] ?? null,
            'min_date' => $data['min_date'] ?? null,
            'max_date' => $data['max_date'] ?? null,
            'text_rule' => $data['text_rule'] ?? SubcategoryField::TEXT_RULE_NONE,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    public function updateSubcategoryField(SubcategoryField $field, array $data): SubcategoryField
    {
        $this->validateFieldRules($data);

        $labelEn = trim($data['label_en']);
        $fieldKey = $this->generateUniqueFieldKey($field->subcategory, $labelEn, $field->id);

        $field->update([
            'field_key' => $fieldKey,
            'label_en' => $labelEn,
            'label_ar' => isset($data['label_ar']) ? trim((string) $data['label_ar']) : null,
            'field_type' => $data['field_type'],
            'is_required' => $data['is_required'] ?? false,
            'options' => $data['options'] ?? null,
            'min_value' => $data['min_value'] ?? null,
            'max_value' => $data['max_value'] ?? null,
            'min_date' => $data['min_date'] ?? null,
            'max_date' => $data['max_date'] ?? null,
            'text_rule' => $data['text_rule'] ?? SubcategoryField::TEXT_RULE_NONE,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return $field->fresh('subcategory');
    }

    public function deleteSubcategoryField(SubcategoryField $field): void
    {
        $field->delete();
    }

    private function generateUniqueFieldKey(Subcategory $subcategory, string $labelEn, ?int $ignoreFieldId = null): string
    {
        $baseKey = Str::slug($labelEn, '_');

        if ($baseKey === '') {
            throw ValidationException::withMessages([
                'label_en' => ['The English field name must contain valid letters or numbers.'],
            ]);
        }

        $fieldKey = $baseKey;
        $counter = 2;

        while (
            SubcategoryField::query()
                ->where('subcategory_id', $subcategory->id)
                ->where('field_key', $fieldKey)
                ->when($ignoreFieldId, function ($query) use ($ignoreFieldId) {
                    $query->where('id', '!=', $ignoreFieldId);
                })
                ->exists()
        ) {
            $fieldKey = $baseKey . '_' . $counter;
            $counter++;
        }

        return $fieldKey;
    }

    private function validateFieldRules(array $data): void
    {
        $fieldType = $data['field_type'];

        if ($fieldType === SubcategoryField::TYPE_SELECT) {
            if (empty($data['options']) || ! is_array($data['options'])) {
                throw ValidationException::withMessages([
                    'options' => ['Options are required when field type is select.'],
                ]);
            }
        }

        if ($fieldType !== SubcategoryField::TYPE_SELECT && ! empty($data['options'])) {
            throw ValidationException::withMessages([
                'options' => ['Options are only allowed for select fields.'],
            ]);
        }

        if ($fieldType === SubcategoryField::TYPE_NUMBER) {
            if (isset($data['min_value'], $data['max_value']) && (float) $data['min_value'] > (float) $data['max_value']) {
                throw ValidationException::withMessages([
                    'min_value' => ['Minimum value cannot be greater than maximum value.'],
                ]);
            }
        }

        if ($fieldType !== SubcategoryField::TYPE_NUMBER) {
            if (isset($data['min_value']) || isset($data['max_value'])) {
                throw ValidationException::withMessages([
                    'min_value' => ['Minimum and maximum values are only allowed for number fields.'],
                ]);
            }
        }

        if ($fieldType === SubcategoryField::TYPE_DATE) {
            if (isset($data['min_date'], $data['max_date']) && $data['min_date'] > $data['max_date']) {
                throw ValidationException::withMessages([
                    'min_date' => ['Minimum date cannot be after maximum date.'],
                ]);
            }
        }

        if ($fieldType !== SubcategoryField::TYPE_DATE) {
            if (isset($data['min_date']) || isset($data['max_date'])) {
                throw ValidationException::withMessages([
                    'min_date' => ['Minimum and maximum dates are only allowed for date fields.'],
                ]);
            }
        }

        if ($fieldType !== SubcategoryField::TYPE_TEXT && ! empty($data['text_rule']) && $data['text_rule'] !== SubcategoryField::TEXT_RULE_NONE) {
            throw ValidationException::withMessages([
                'text_rule' => ['Text validation rules are only allowed for text fields.'],
            ]);
        }
    }
}
