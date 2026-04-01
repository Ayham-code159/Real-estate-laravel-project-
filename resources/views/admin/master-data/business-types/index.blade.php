@extends('layouts.app')

@section('title', 'Business Types')

@section('content')
    <x-page-title
        title="Business Account Types"
        subtitle="Manage the main account types that users can choose when creating a business account."
    >
        <x-slot:actions>
            <span class="badge badge-primary">Super Admin Only</span>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <form method="POST" action="{{ route('admin.master-data.business-types.store') }}">
            @csrf

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">New Business Type</label>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name') }}"
                        placeholder="Enter business account type"
                        required
                    >
                </div>

                <div>
                    <x-button type="submit" variant="primary">
                        <span>＋</span>
                        <span>Add Type</span>
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">All Business Types</h2>
            <p class="section-subtitle">
                Click a business type to manage its details.
            </p>
        </div>

        @forelse($businessTypes as $businessType)
            <a href="{{ route('admin.master-data.business-types.show', $businessType->id) }}" style="display: block; margin-bottom: 12px;">
                <div class="activity-item">
                    <div class="activity-icon">🏷️</div>

                    <div class="activity-body" style="width: 100%;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                            <h4 style="margin: 0;">{{ $businessType->name }}</h4>
                            <span class="badge badge-primary">ID #{{ $businessType->id }}</span>
                        </div>

                        <p style="margin-top: 8px;">
                            Created at {{ $businessType->created_at->format('Y-m-d h:i A') }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🏷️</div>
                <h3>No Business Types</h3>
                <p>
                    No business account types found.
                </p>
            </div>
        @endforelse
    </x-card>
@endsection
