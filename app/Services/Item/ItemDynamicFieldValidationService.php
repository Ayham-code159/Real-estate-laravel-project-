<?php

namespace App\Services\Item;

use App\Models\Subcategory;
use App\Models\SubcategoryField;
use Illuminate\Validation\ValidationException;

class ItemDynamicFieldValidationService
{
    public function validate(?Subcategory $subcategory, array $dynamicValues = []): array
    {
        if (! $subcategory) {
            return [];
        }

        $fields = $subcategory->fields()
            ->orderBy('sort_order')
            ->get();

        $validatedValues = [];

        foreach ($fields as $field) {
            $key = $field->field_key;
            $valueExists = array_key_exists($key, $dynamicValues);
            $value = $valueExists ? $dynamicValues[$key] : null;

            if ($field->is_required && ($value === null || $value === '')) {
                throw ValidationException::withMessages([
                    "dynamic_values.$key" => ["{$field->label_en} is required."],
                ]);
            }

            if (! $valueExists || $value === null || $value === '') {
                continue;
            }

            $validatedValues[$key] = $this->validateFieldValue($field, $value);
        }

        return $validatedValues;
    }

    private function validateFieldValue(SubcategoryField $field, mixed $value): mixed
    {
        return match ($field->field_type) {
            SubcategoryField::TYPE_TEXT => $this->validateText($field, $value),
            SubcategoryField::TYPE_NUMBER => $this->validateNumber($field, $value),
            SubcategoryField::TYPE_SELECT => $this->validateSelect($field, $value),
            SubcategoryField::TYPE_BOOLEAN => $this->validateBoolean($field, $value),
            SubcategoryField::TYPE_DATE => $this->validateDate($field, $value),
            default => $value,
        };
    }

    private function validateText(SubcategoryField $field, mixed $value): string
    {
        if (! is_string($value) && ! is_numeric($value)) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must be text."],
            ]);
        }

        $value = trim((string) $value);

        if ($field->text_rule === SubcategoryField::TEXT_RULE_LETTERS_ONLY && ! preg_match('/^\pL+$/u', $value)) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must contain letters only."],
            ]);
        }

        if ($field->text_rule === SubcategoryField::TEXT_RULE_LETTERS_SPACES_ONLY && ! preg_match('/^[\pL\s]+$/u', $value)) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must contain letters and spaces only."],
            ]);
        }

        if ($field->text_rule === SubcategoryField::TEXT_RULE_ALPHA_NUMERIC && ! preg_match('/^[\pL\pN\s]+$/u', $value)) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must contain letters and numbers only."],
            ]);
        }

        return $value;
    }

    private function validateNumber(SubcategoryField $field, mixed $value): float|int
    {
        if (! is_numeric($value)) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must be a number."],
            ]);
        }

        $number = $value + 0;

        if ($field->min_value !== null && $number < (float) $field->min_value) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must be at least {$field->min_value}."],
            ]);
        }

        if ($field->max_value !== null && $number > (float) $field->max_value) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must be at most {$field->max_value}."],
            ]);
        }

        return $number;
    }

    private function validateSelect(SubcategoryField $field, mixed $value): string
    {
        $value = trim((string) $value);
        $options = $field->options ?? [];

        if (! in_array($value, $options, true)) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must be one of: " . implode(', ', $options)],
            ]);
        }

        return $value;
    }

    private function validateBoolean(SubcategoryField $field, mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if ($value === 1 || $value === '1' || $value === 'true') {
            return true;
        }

        if ($value === 0 || $value === '0' || $value === 'false') {
            return false;
        }

        throw ValidationException::withMessages([
            "dynamic_values.{$field->field_key}" => ["{$field->label_en} must be true or false."],
        ]);
    }

    private function validateDate(SubcategoryField $field, mixed $value): string
    {
        $date = date_create((string) $value);

        if (! $date) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must be a valid date."],
            ]);
        }

        $dateString = $date->format('Y-m-d');

        if ($field->min_date && $dateString < $field->min_date->format('Y-m-d')) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must be after or equal {$field->min_date->format('Y-m-d')}."],
            ]);
        }

        if ($field->max_date && $dateString > $field->max_date->format('Y-m-d')) {
            throw ValidationException::withMessages([
                "dynamic_values.{$field->field_key}" => ["{$field->label_en} must be before or equal {$field->max_date->format('Y-m-d')}."],
            ]);
        }

        return $dateString;
    }
}
