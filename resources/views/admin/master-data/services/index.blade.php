@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <x-page-title
        title="Main Service Categories"
        subtitle="Manage the main service categories that service listings will belong to."
    >
        <x-slot:actions>
            <span class="badge badge-primary">Super Admin Only</span>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="POST" action="{{ route('admin.master-data.services.store') }}">
            @csrf

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">New Main Service</label>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name') }}"
                        placeholder="Enter main service category"
                        required
                    >
                </div>

                <div>
                    <x-button type="submit" variant="primary">
                        <span>＋</span>
                        <span>Add Service</span>
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">All Main Services</h2>
            <p class="section-subtitle">
                Click on a service to manage its details, update its name, or delete it.
            </p>
        </div>

        @forelse($services as $service)
            <a href="{{ route('admin.master-data.services.show', $service->id) }}" style="display: block; margin-bottom: 12px;">
                <div class="activity-item">
                    <div class="activity-icon">🧩</div>

                    <div class="activity-body" style="width: 100%;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                            <h4 style="margin: 0;">{{ $service->name }}</h4>
                            <span class="badge badge-primary">{{ $service->subcategories_count }} subcategories</span>
                        </div>

                        <p style="margin-top: 8px;">
                            Created at {{ $service->created_at->format('Y-m-d h:i A') }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🧩</div>
                <h3>No Main Services</h3>
                <p>
                    No main service categories found.
                </p>
            </div>
        @endforelse
    </x-card>
@endsection
