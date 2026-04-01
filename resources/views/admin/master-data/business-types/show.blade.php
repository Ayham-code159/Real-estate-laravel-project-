@extends('layouts.app')

@section('title', 'Business Type Details')

@section('content')
    <x-page-title
        title="Business Type Details"
        subtitle="View, update, or delete this business account type."
    >
        <x-slot:actions>
            <a href="{{ route('admin.master-data.business-types.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>Back to Business Types</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">Type Name</div>
                <div class="info-value">{{ $businessType->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Related Business Accounts</div>
                <div class="info-value">{{ $businessType->business_accounts_count }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Created At</div>
                <div class="info-value">{{ $businessType->created_at->format('Y-m-d h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Edit Business Type</h2>
            <p class="section-subtitle">
                Updating the name only changes the label. The underlying system structure stays the same.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.business-types.update', $businessType->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Business Type Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $businessType->name) }}"
                        placeholder="Enter business type name"
                        required
                    >
                </div>

                <div>
                    <x-button type="submit" variant="primary">
                        <span>💾</span>
                        <span>Update Type</span>
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Danger Zone</h2>
            <p class="section-subtitle">
                Deleting this business type will also delete all business accounts that use it.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.business-types.destroy', $businessType->id) }}">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>Delete Business Type</span>
            </x-button>
        </form>
    </x-card>
@endsection
