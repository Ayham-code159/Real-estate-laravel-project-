<?php

namespace App\Http\Controllers\Admin\MasterData;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\MasterData\AdminMasterDataService;
use App\Http\Requests\Admin\MasterData\StoreServiceRequest;
use App\Http\Requests\Admin\MasterData\UpdateServiceRequest;
use App\Http\Requests\Admin\MasterData\StoreBusinessTypeRequest;
use App\Http\Requests\Admin\MasterData\UpdateBusinessTypeRequest;
use App\Http\Requests\Admin\MasterData\StoreServiceSubcategoryRequest;
use App\Http\Requests\Admin\MasterData\UpdateServiceSubcategoryRequest;

class AdminMasterDataController extends Controller
{
    public function __construct(
        private AdminMasterDataService $adminMasterDataService
    ) {}

    public function businessTypes()
    {
        $businessTypes = $this->adminMasterDataService->getBusinessTypes();

        return view('admin.master-data.business-types.index', compact('businessTypes'));
    }

    public function showBusinessType(int $businessType)
    {
        $businessType = $this->adminMasterDataService->getBusinessTypeDetails($businessType);

        return view('admin.master-data.business-types.show', compact('businessType'));
    }

    public function storeBusinessType(StoreBusinessTypeRequest $request): RedirectResponse
    {
        $this->adminMasterDataService->createBusinessType($request->validated());

        return redirect()
            ->route('admin.master-data.business-types.index')
            ->with('success', 'Business account type created successfully.');
    }

    public function updateBusinessType(
        UpdateBusinessTypeRequest $request,
        int $businessType
    ): RedirectResponse {
        $this->adminMasterDataService->updateBusinessType(
            $businessType,
            $request->validated()
        );

        return redirect()
            ->route('admin.master-data.business-types.show', $businessType)
            ->with('success', 'Business account type updated successfully.');
    }

    public function destroyBusinessType(int $businessType): RedirectResponse
    {
        $this->adminMasterDataService->deleteBusinessType($businessType);

        return redirect()
            ->route('admin.master-data.business-types.index')
            ->with('success', 'Business account type deleted successfully.');
    }

    public function services()
    {
        $services = $this->adminMasterDataService->getServices();

        return view('admin.master-data.services.index', compact('services'));
    }

    public function storeService(StoreServiceRequest $request): RedirectResponse
    {
        $this->adminMasterDataService->createService($request->validated());

        return redirect()
            ->route('admin.master-data.services.index')
            ->with('success', 'Main service category created successfully.');
    }

    public function showService(int $service)
    {
        $service = $this->adminMasterDataService->getServiceDetails($service);

        return view('admin.master-data.services.show', compact('service'));
    }

    public function updateService(
        UpdateServiceRequest $request,
        int $service
    ): RedirectResponse {
        $this->adminMasterDataService->updateService(
            $service,
            $request->validated()
        );

        return redirect()
            ->route('admin.master-data.services.show', $service)
            ->with('success', 'Main service category updated successfully.');
    }

    public function destroyService(int $service): RedirectResponse
    {
        $this->adminMasterDataService->deleteService($service);

        return redirect()
            ->route('admin.master-data.services.index')
            ->with('success', 'Main service category deleted successfully.');
    }

    public function storeServiceSubcategory(StoreServiceSubcategoryRequest $request): RedirectResponse
    {
        $subcategory = $this->adminMasterDataService->createServiceSubcategory($request->validated());

        return redirect()
            ->route('admin.master-data.services.show', $subcategory->service_id)
            ->with('success', 'Service subcategory created successfully.');
    }

    public function showServiceSubcategory(int $serviceSubcategory)
    {
        $subcategory = $this->adminMasterDataService->getSubcategoryDetails($serviceSubcategory);

        return view('admin.master-data.service-subcategories.show', compact('subcategory'));
    }

    public function updateServiceSubcategory(
        UpdateServiceSubcategoryRequest $request,
        int $serviceSubcategory
    ): RedirectResponse {
        $subcategory = $this->adminMasterDataService->updateServiceSubcategory(
            $serviceSubcategory,
            $request->validated()
        );

        return redirect()
            ->route('admin.master-data.service-subcategories.show', $subcategory->id)
            ->with('success', 'Service subcategory updated successfully.');
    }

    public function destroyServiceSubcategory(int $serviceSubcategory): RedirectResponse
    {
        $subcategory = $this->adminMasterDataService->getSubcategoryDetails($serviceSubcategory);

        $serviceId = $subcategory->service_id;

        $this->adminMasterDataService->deleteServiceSubcategory($serviceSubcategory);

        return redirect()
            ->route('admin.master-data.services.show', $serviceId)
            ->with('success', 'Service subcategory deleted successfully.');
    }
}
