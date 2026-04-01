@extends('layouts.app')

@section('title', 'Service Subcategories')

@section('content')
    <x-page-title
        title="Service Subcategories"
        subtitle="Manage the subcategories under each main service category."
    >
        <x-slot:actions>
            <span class="badge badge-primary">Super Admin Only</span>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="POST" action="{{ route('admin.master-data.service-subcategories.store') }}">
            @csrf

            <div class="grid grid-2" style="align-items: end; margin-bottom: 16px;">
                <div>
                    <label class="form-label">Main Service</label>
                    <select name="service_id" class="form-input" required>
                        <option value="">Select main service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">New Subcategory</label>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name') }}"
                        placeholder="Enter service subcategory"
                        required
                    >
                </div>
            </div>

            <x-button type="submit" variant="primary">
                <span>＋</span>
                <span>Add Subcategory</span>
            </x-button>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">All Service Subcategories</h2>
            <p class="section-subtitle">
                Each subcategory belongs to one main service category.
            </p>
        </div>

        @forelse($serviceSubcategories as $subcategory)
            <div class="activity-item" style="margin-bottom: 12px;">
                <div class="activity-icon">🪜</div>

                <div class="activity-body" style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                        <h4 style="margin: 0;">{{ $subcategory->name }}</h4>
                        <span class="badge badge-primary">{{ $subcategory->service?->name ?? 'N/A' }}</span>
                    </div>

                    <p style="margin-top: 8px;">
                        Created at {{ $subcategory->created_at->format('Y-m-d h:i A') }}
                    </p>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🪜</div>
                <h3>No Service Subcategories</h3>
                <p>
                    No subcategories found.
                </p>
            </div>
        @endforelse
    </x-card>
@endsection
