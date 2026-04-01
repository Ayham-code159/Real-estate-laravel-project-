@extends('layouts.app')

@section('title', 'Subcategory Details')

@section('content')
    <x-page-title
        title="Subcategory Details"
        subtitle="View this subcategory, update it, or delete it."
    >
        <x-slot:actions>
            <a href="{{ route('admin.master-data.services.show', $subcategory->service_id) }}" class="btn btn-outline">
                <span>←</span>
                <span>Back to Service</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">Subcategory Name</div>
                <div class="info-value">{{ $subcategory->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Parent Service</div>
                <div class="info-value">{{ $subcategory->service?->name ?? 'N/A' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Created At</div>
                <div class="info-value">{{ $subcategory->created_at->format('Y-m-d h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Edit Subcategory</h2>
            <p class="section-subtitle">
                Update the name of this subcategory.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.service-subcategories.update', $subcategory->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">Subcategory Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $subcategory->name) }}"
                        placeholder="Enter subcategory name"
                        required
                    >
                </div>

                <div>
                    <x-button type="submit" variant="primary">
                        <span>💾</span>
                        <span>Update Subcategory</span>
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">Danger Zone</h2>
            <p class="section-subtitle">
                Deleting this subcategory will also delete all user-created listings connected to it once the listing system is built.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.service-subcategories.destroy', $subcategory->id) }}">
            @csrf
            @method('DELETE')

            <x-button type="submit" variant="danger">
                <span>🗑</span>
                <span>Delete Subcategory</span>
            </x-button>
        </form>
    </x-card>
@endsection
