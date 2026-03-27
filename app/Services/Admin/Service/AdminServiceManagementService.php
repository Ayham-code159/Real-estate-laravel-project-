<?php

namespace App\Services\Admin\Service;

use App\Models\SellService;
use App\Models\RentService;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AdminServiceManagementService
{
    public function getApprovedServicesCount(): array
    {
        $approvedSellServices = SellService::where('status', SellService::STATUS_APPROVED)->count();
        $approvedRentServices = RentService::where('status', RentService::STATUS_APPROVED)->count();

        return [
            'approved_sell_services' => $approvedSellServices,
            'approved_rent_services' => $approvedRentServices,
            'total_approved_services' => $approvedSellServices + $approvedRentServices,
        ];
    }

    public function getServicesSummaryCounts(): array
    {
        $sellPending = SellService::where('status', SellService::STATUS_PENDING)->count();
        $sellApproved = SellService::where('status', SellService::STATUS_APPROVED)->count();
        $sellRejected = SellService::where('status', SellService::STATUS_REJECTED)->count();

        $rentPending = RentService::where('status', RentService::STATUS_PENDING)->count();
        $rentApproved = RentService::where('status', RentService::STATUS_APPROVED)->count();
        $rentRejected = RentService::where('status', RentService::STATUS_REJECTED)->count();

        return [
            'total_services' => SellService::count() + RentService::count(),
            'pending_services' => $sellPending + $rentPending,
            'approved_services' => $sellApproved + $rentApproved,
            'rejected_services' => $sellRejected + $rentRejected,
        ];
    }

    public function getAllServices(?string $search = null, ?string $type = null): Collection
    {
        $services = collect();

        if ($type === null || $type === 'sell') {
            $sellServices = SellService::with(['subtype', 'businessAccount.user'])
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'like', '%' . trim($search) . '%');
                })
                ->get()
                ->map(function ($service) {
                    return $this->mapServiceForList($service, 'sell');
                });

            $services = $services->merge($sellServices);
        }

        if ($type === null || $type === 'rent') {
            $rentServices = RentService::with(['subtype', 'businessAccount.user'])
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'like', '%' . trim($search) . '%');
                })
                ->get()
                ->map(function ($service) {
                    return $this->mapServiceForList($service, 'rent');
                });

            $services = $services->merge($rentServices);
        }

        return $services->sortByDesc('created_at')->values();
    }

    public function getServiceDetails(string $type, int $id): SellService|RentService
    {
        return match ($type) {
            'sell' => SellService::with(['subtype', 'businessAccount.user', 'businessAccount.businessType', 'businessAccount.city'])->findOrFail($id),
            'rent' => RentService::with(['subtype', 'businessAccount.user', 'businessAccount.businessType', 'businessAccount.city'])->findOrFail($id),
            default => throw ValidationException::withMessages([
                'type' => ['Invalid service type.'],
            ]),
        };
    }

    public function updateServiceStatus(string $type, int $id, array $data): SellService|RentService
    {
        $service = $this->getServiceDetails($type, $id);

        $service->status = (int) $data['status'];

        if ($service->status === SellService::STATUS_REJECTED) {
            $service->rejection_reason = $data['rejection_reason'] ?? null;
        } else {
            $service->rejection_reason = null;
        }

        $service->save();

        return $service;
    }

    private function mapServiceForList(SellService|RentService $service, string $type): array
    {
        return [
            'id' => $service->id,
            'type' => $type,
            'title' => $service->title,
            'subtype_name' => $service->subtype?->name ?? 'N/A',
            'user_name' => $service->businessAccount?->user?->full_name ?? 'N/A',
            'business_account_name' => $service->businessAccount?->business_name ?? 'N/A',
            'status' => $service->status,
            'status_label' => $service->status_label,
            'status_badge_class' => $service->status_badge_class,
            'created_at' => $service->created_at,
        ];
    }
}
