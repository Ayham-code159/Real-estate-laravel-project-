<?php

namespace App\Services\ServiceListing;

use App\Models\User;
use App\Models\Service;
use App\Models\ServiceListing;
use App\Models\BusinessAccount;
use App\Models\ServiceSubcategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ServiceListingService
{
    private const USD_TO_SYP_RATE = 12000;

    public function getAllServices(): Collection
    {
        return Service::query()
            ->orderBy('name')
            ->get();
    }

    public function getSubcategoriesByService(int $serviceId): Collection
    {
        return ServiceSubcategory::query()
            ->where('service_id', $serviceId)
            ->orderBy('name')
            ->get();
    }

    public function create(User $user, array $data): ServiceListing
    {
        $businessAccount = $this->getApprovedActiveBusinessAccount($user);

        $this->ensureSubcategoryBelongsToService(
            (int) $data['service_id'],
            (int) $data['service_subcategory_id']
        );

        $priceUsd = (float) $data['price_usd'];
        $priceSyp = $this->convertUsdToSyp($priceUsd);

        $serviceListing = ServiceListing::create([
            'business_account_id' => $businessAccount->id,
            'service_id' => (int) $data['service_id'],
            'service_subcategory_id' => (int) $data['service_subcategory_id'],
            'title' => trim($data['title']),
            'description' => $data['description'] ?? null,
            'mode' => $data['mode'],
            'price_usd' => $priceUsd,
            'price_syp' => $priceSyp,
            'metadata' => $data['metadata'] ?? null,
            'status' => ServiceListing::STATUS_PENDING,
            'rejection_reason' => null,
        ]);

        $this->syncMedia($serviceListing, $data);

        return $serviceListing->fresh([
            'service',
            'subcategory',
            'businessAccount.businessType',
            'businessAccount.city',
        ]);
    }

    public function listForUser(User $user): Collection
    {
        return ServiceListing::with([
                'service',
                'subcategory',
                'businessAccount.businessType',
                'businessAccount.city',
            ])
            ->whereHas('businessAccount', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();
    }

    public function listForActiveBusinessAccount(User $user): Collection
    {
        $businessAccount = $this->getApprovedActiveBusinessAccount($user);

        return ServiceListing::with([
                'service',
                'subcategory',
                'businessAccount.businessType',
                'businessAccount.city',
            ])
            ->where('business_account_id', $businessAccount->id)
            ->latest()
            ->get();
    }

    public function update(User $user, ServiceListing $serviceListing, array $data): ServiceListing
    {
        $this->ensureListingBelongsToUser($user, $serviceListing);

        $this->ensureSubcategoryBelongsToService(
            (int) $data['service_id'],
            (int) $data['service_subcategory_id']
        );

        $priceUsd = (float) $data['price_usd'];
        $priceSyp = $this->convertUsdToSyp($priceUsd);

        $serviceListing->update([
            'service_id' => (int) $data['service_id'],
            'service_subcategory_id' => (int) $data['service_subcategory_id'],
            'title' => trim($data['title']),
            'description' => $data['description'] ?? null,
            'mode' => $data['mode'],
            'price_usd' => $priceUsd,
            'price_syp' => $priceSyp,
            'metadata' => $data['metadata'] ?? null,
        ]);

        $this->syncMedia($serviceListing, $data);

        return $serviceListing->fresh([
            'service',
            'subcategory',
            'businessAccount.businessType',
            'businessAccount.city',
        ]);
    }

    public function delete(User $user, ServiceListing $serviceListing): void
    {
        $this->ensureListingBelongsToUser($user, $serviceListing);

        $serviceListing->delete();
    }

    public function addSubPhotos(User $user, ServiceListing $serviceListing, array $photos): ServiceListing
    {
        $this->ensureListingBelongsToUser($user, $serviceListing);

        foreach ($photos as $photo) {
            if ($photo instanceof UploadedFile) {
                $serviceListing
                    ->addMedia($photo)
                    ->toMediaCollection('sub_photos');
            }
        }

        return $serviceListing->fresh([
            'service',
            'subcategory',
            'businessAccount.businessType',
            'businessAccount.city',
        ]);
    }

    public function replaceMainPhoto(User $user, ServiceListing $serviceListing, UploadedFile $photo): ServiceListing
    {
        $this->ensureListingBelongsToUser($user, $serviceListing);

        $serviceListing->clearMediaCollection('main_photo');

        $serviceListing
            ->addMedia($photo)
            ->toMediaCollection('main_photo');

        return $serviceListing->fresh([
            'service',
            'subcategory',
            'businessAccount.businessType',
            'businessAccount.city',
        ]);
    }

    public function deleteSubPhoto(User $user, ServiceListing $serviceListing, int $mediaId): ServiceListing
    {
        $this->ensureListingBelongsToUser($user, $serviceListing);

        $media = $serviceListing->media()
            ->where('id', $mediaId)
            ->where('collection_name', 'sub_photos')
            ->first();

        if (! $media) {
            throw ValidationException::withMessages([
                'media' => ['The selected sub photo was not found for this listing.'],
            ]);
        }

        $media->delete();

        return $serviceListing->fresh([
            'service',
            'subcategory',
            'businessAccount.businessType',
            'businessAccount.city',
        ]);
    }

    private function syncMedia(ServiceListing $serviceListing, array $data): void
    {
        if (isset($data['main_photo']) && $data['main_photo'] instanceof UploadedFile) {
            $serviceListing
                ->addMedia($data['main_photo'])
                ->toMediaCollection('main_photo');
        }

        if (isset($data['sub_photos']) && is_array($data['sub_photos'])) {
            foreach ($data['sub_photos'] as $photo) {
                if ($photo instanceof UploadedFile) {
                    $serviceListing
                        ->addMedia($photo)
                        ->toMediaCollection('sub_photos');
                }
            }
        }
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
                'business_account' => ['You can only add listings through an approved active business account.'],
            ]);
        }

        return $businessAccount;
    }

    private function ensureSubcategoryBelongsToService(int $serviceId, int $subcategoryId): void
    {
        $exists = ServiceSubcategory::query()
            ->where('id', $subcategoryId)
            ->where('service_id', $serviceId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'service_subcategory_id' => ['The selected subcategory does not belong to the selected service.'],
            ]);
        }
    }

    private function ensureListingBelongsToUser(User $user, ServiceListing $serviceListing): void
    {
        $businessAccount = $serviceListing->businessAccount;

        if (! $businessAccount || $businessAccount->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'service_listing' => ['This listing does not belong to you.'],
            ]);
        }
    }

    private function convertUsdToSyp(float $priceUsd): float
    {
        return $priceUsd * self::USD_TO_SYP_RATE;
    }
}
