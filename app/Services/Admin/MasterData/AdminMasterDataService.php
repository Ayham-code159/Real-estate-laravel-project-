<?php

namespace App\Services\Admin\MasterData;

use App\Models\Service;
use App\Models\BusinessType;
use App\Models\ServiceSubcategory;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;

class AdminMasterDataService
{
    public function getBusinessTypes(): Collection
    {
        return BusinessType::query()
            ->latest()
            ->get();
    }

    public function getBusinessTypeDetails(int $businessTypeId): BusinessType
    {
        return BusinessType::query()
            ->withCount('businessAccounts')
            ->findOrFail($businessTypeId);
    }

    public function createBusinessType(array $data): BusinessType
    {
        $nameEn = trim($data['name_en']);
        $nameAr = trim($data['name_ar']);

        $exists = BusinessType::query()
            ->where('name_en', $nameEn)
            ->orWhere('name_ar', $nameAr)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name_en' => ['This business type already exists in one of the selected languages.'],
            ]);
        }

        return BusinessType::create([
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr,
        ]);
    }

    public function updateBusinessType(int $businessTypeId, array $data): BusinessType
    {
        $businessType = BusinessType::query()->findOrFail($businessTypeId);

        $nameEn = trim($data['name_en']);
        $nameAr = trim($data['name_ar']);

        $exists = BusinessType::query()
            ->where('id', '!=', $businessType->id)
            ->where(function ($query) use ($nameEn, $nameAr) {
                $query->where('name_en', $nameEn)
                    ->orWhere('name_ar', $nameAr);
            })
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name_en' => ['This business type already exists in one of the selected languages.'],
            ]);
        }

        $businessType->update([
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr,
        ]);

        return $businessType->fresh();
    }

    public function deleteBusinessType(int $businessTypeId): void
    {
        $businessType = BusinessType::query()->findOrFail($businessTypeId);
        $businessType->delete();
    }

    public function getServices(): Collection
    {
        return Service::query()
            ->withCount('subcategories')
            ->latest()
            ->get();
    }

    public function getServiceDetails(int $serviceId): Service
    {
        return Service::query()
            ->with(['subcategories' => function ($query) {
                $query->latest();
            }])
            ->findOrFail($serviceId);
    }

    public function createService(array $data): Service
    {
        $nameEn = trim($data['name_en']);
        $nameAr = trim($data['name_ar']);

        $exists = Service::query()
            ->where('name_en', $nameEn)
            ->orWhere('name_ar', $nameAr)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name_en' => ['This service already exists in one of the selected languages.'],
            ]);
        }

        return Service::create([
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr,
        ]);
    }

    public function updateService(int $serviceId, array $data): Service
    {
        $service = Service::query()->findOrFail($serviceId);

        $nameEn = trim($data['name_en']);
        $nameAr = trim($data['name_ar']);

        $exists = Service::query()
            ->where('id', '!=', $service->id)
            ->where(function ($query) use ($nameEn, $nameAr) {
                $query->where('name_en', $nameEn)
                    ->orWhere('name_ar', $nameAr);
            })
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name_en' => ['This service already exists in one of the selected languages.'],
            ]);
        }

        $service->update([
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr,
        ]);

        return $service->fresh();
    }

    public function deleteService(int $serviceId): void
    {
        $service = Service::query()->findOrFail($serviceId);
        $service->delete();
    }

    public function createServiceSubcategory(array $data): ServiceSubcategory
    {
        $serviceId = (int) $data['service_id'];
        $nameEn = trim($data['name_en']);
        $nameAr = trim($data['name_ar']);

        $exists = ServiceSubcategory::query()
            ->where('service_id', $serviceId)
            ->where(function ($query) use ($nameEn, $nameAr) {
                $query->where('name_en', $nameEn)
                    ->orWhere('name_ar', $nameAr);
            })
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name_en' => ['This subcategory already exists for the selected service in one of the selected languages.'],
            ]);
        }

        return ServiceSubcategory::create([
            'service_id' => $serviceId,
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr,
        ]);
    }

    public function getSubcategoryDetails(int $subcategoryId): ServiceSubcategory
    {
        return ServiceSubcategory::query()
            ->with('service')
            ->findOrFail($subcategoryId);
    }

    public function updateServiceSubcategory(int $subcategoryId, array $data): ServiceSubcategory
    {
        $subcategory = ServiceSubcategory::query()->findOrFail($subcategoryId);

        $nameEn = trim($data['name_en']);
        $nameAr = trim($data['name_ar']);

        $exists = ServiceSubcategory::query()
            ->where('service_id', $subcategory->service_id)
            ->where('id', '!=', $subcategory->id)
            ->where(function ($query) use ($nameEn, $nameAr) {
                $query->where('name_en', $nameEn)
                    ->orWhere('name_ar', $nameAr);
            })
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name_en' => ['This subcategory already exists for the selected service in one of the selected languages.'],
            ]);
        }

        $subcategory->update([
            'name' => $nameEn,
            'name_en' => $nameEn,
            'name_ar' => $nameAr,
        ]);

        return $subcategory->fresh('service');
    }

    public function deleteServiceSubcategory(int $subcategoryId): void
    {
        $subcategory = ServiceSubcategory::query()->findOrFail($subcategoryId);
        $subcategory->delete();
    }
}
