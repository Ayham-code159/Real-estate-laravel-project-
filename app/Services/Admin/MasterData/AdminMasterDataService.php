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
        return BusinessType::create([
            'name' => trim($data['name']),
        ]);
    }

    public function updateBusinessType(int $businessTypeId, array $data): BusinessType
    {
        $businessType = BusinessType::query()->findOrFail($businessTypeId);

        $exists = BusinessType::query()
            ->where('name', trim($data['name']))
            ->where('id', '!=', $businessType->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['This business type already exists.'],
            ]);
        }

        $businessType->update([
            'name' => trim($data['name']),
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
        return Service::create([
            'name' => trim($data['name']),
        ]);
    }

    public function updateService(int $serviceId, array $data): Service
    {
        $service = Service::query()->findOrFail($serviceId);

        $exists = Service::query()
            ->where('name', trim($data['name']))
            ->where('id', '!=', $service->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['This service already exists.'],
            ]);
        }

        $service->update([
            'name' => trim($data['name']),
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
        $exists = ServiceSubcategory::query()
            ->where('service_id', (int) $data['service_id'])
            ->where('name', trim($data['name']))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['This subcategory already exists for the selected service.'],
            ]);
        }

        return ServiceSubcategory::create([
            'service_id' => (int) $data['service_id'],
            'name' => trim($data['name']),
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

        $exists = ServiceSubcategory::query()
            ->where('service_id', $subcategory->service_id)
            ->where('name', trim($data['name']))
            ->where('id', '!=', $subcategory->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['This subcategory already exists for the selected service.'],
            ]);
        }

        $subcategory->update([
            'name' => trim($data['name']),
        ]);

        return $subcategory->fresh('service');
    }

    public function deleteServiceSubcategory(int $subcategoryId): void
    {
        $subcategory = ServiceSubcategory::query()->findOrFail($subcategoryId);
        $subcategory->delete();
    }
}
