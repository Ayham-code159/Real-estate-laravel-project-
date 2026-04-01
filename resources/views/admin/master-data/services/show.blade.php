@extends('layouts.app')

@section('title', 'Service Details')

@section('content')
    <x-page-title
        title="Service Details"
        subtitle="View, update, or delete this main service and manage its related subcategories."
    >
        <x-slot:actions>
            <a href="{{ route('admin.master-data.services.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>Back to Services</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">Service Name</div>
                <div class="info-value">{{ $service->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Subcategories Count</div>
                <div class="info-value">{{ $service->subcategories->count() }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Created At</div>
                <div class="info-value">{{ $service->created_at->format('Y-m-d h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Edit Main Service</h2>
            <p class="section-subtitle">
                Update the name of this main service category.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.services.update', $service->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Service Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $service->name) }}"
                        placeholder="Enter main service name"
                        required
                    >
                </div>

                <div>
                    <x-button type="submit" variant="primary">
                        <span>💾</span>
                        <span>Update Service</span>
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Add New Subcategory</h2>
            <p class="section-subtitle">
                Create a subcategory under this service.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.service-subcategories.store') }}">
            @csrf

            <input type="hidden" name="service_id" value="{{ $service->id }}">

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Subcategory Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name') }}"
                        placeholder="Enter subcategory name"
                        required
                    >
                </div>

                <div>
                    <x-button type="submit" variant="primary">
                        <span>＋</span>
                        <span>Add Subcategory</span>
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Related Subcategories</h2>
            <p class="section-subtitle">
                Click on a subcategory to view its details and edit it.
            </p>
        </div>

        @forelse($service->subcategories as $subcategory)
            <a href="{{ route('admin.master-data.service-subcategories.show', $subcategory->id) }}" style="display: block; margin-bottom: 12px;">
                <div class="activity-item">
                    <div class="activity-icon">🪜</div>

                    <div class="activity-body" style="width: 100%;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                            <h4 style="margin: 0;">{{ $subcategory->name }}</h4>
                            <span class="badge badge-primary">ID #{{ $subcategory->id }}</span>
                        </div>

                        <p style="margin-top: 8px;">
                            Created at {{ $subcategory->created_at->format('Y-m-d h:i A') }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🪜</div>
                <h3>No Subcategories Yet</h3>
                <p>
                    This service does not have any subcategories yet.
                </p>
            </div>
        @endforelse
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Danger Zone</h2>
            <p class="section-subtitle">
                Deleting this service will also delete its related subcategories. Later, when user-created listings are added, they will be linked to cascade as well.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.services.destroy', $service->id) }}">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>Delete Service</span>
            </x-button>
        </form>
    </x-card>
@endsection
