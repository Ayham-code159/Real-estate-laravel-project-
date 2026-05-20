@extends('layouts.app')

@section('title', __('messages.business_account_details'))

@section('content')
    <x-page-title :title="__('messages.business_account_details')">
        <x-slot:actions>
            <a href="{{ route('admin.business-accounts.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 class="section-title">{{ $businessAccount->business_name }}</h2>
                <p class="section-subtitle">
                    {{ $businessAccount->businessType->name }} • {{ $businessAccount->city->name }}
                </p>
            </div>

            <span class="badge {{ $businessAccount->status_badge_class }}">
                {{ $businessAccount->status_label }}
            </span>
        </div>

        <div class="info-list">
            <div class="info-row">
                <div class="info-label">{{ __('messages.owner') }}</div>
                <div class="info-value">{{ $businessAccount->user->full_name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.email') }}</div>
                <div class="info-value">{{ $businessAccount->user->email ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.phone') }}</div>
                <div class="info-value">{{ $businessAccount->user->phone ?? __('messages.not_available') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.business_type') }}</div>
                <div class="info-value">{{ $businessAccount->businessType->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.city') }}</div>
                <div class="info-value">{{ $businessAccount->city->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.location') }}</div>
                <div class="info-value">
                    {{ $businessAccount->location_label ?? __('messages.not_available') }}

                    @if($businessAccount->google_maps_url)
                        <div style="margin-top: 10px;">
                            <a href="{{ $businessAccount->google_maps_url }}" target="_blank" class="btn btn-outline">
                                🗺 {{ __('messages.open_map') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.items_count') }}</div>
                <div class="info-value">{{ $businessAccount->items()->count() }}</div>
            </div>

            @if($businessAccount->isRejected() && $businessAccount->rejection_reason)
                <div class="info-row">
                    <div class="info-label">{{ __('messages.rejection_reason') }}</div>
                    <div class="info-value">{{ $businessAccount->rejection_reason }}</div>
                </div>
            @endif
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.update_status') }}</h2>
        </div>

        <form method="POST" action="{{ route('admin.business-accounts.update-status', $businessAccount) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end;">
                <div>
                    <label class="form-label">{{ __('messages.update_status') }}</label>
                    <select name="status" class="form-input">
                        @foreach(\App\Models\BusinessAccount::statuses() as $value => $label)
                            <option value="{{ $value }}" {{ $businessAccount->status == $value ? 'selected' : '' }}>
                                {{ __('messages.business_account_status_' . strtolower($label)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">{{ __('messages.rejection_reason_optional') }}</label>
                    <input
                        type="text"
                        name="rejection_reason"
                        class="form-input"
                        value="{{ old('rejection_reason', $businessAccount->rejection_reason) }}"
                        placeholder="{{ __('messages.add_reason_if_rejecting_business_account') }}"
                    >
                </div>
            </div>

            <div style="margin-top: 18px;">
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.update') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.items') }}</h2>
        </div>

        @forelse($businessAccount->items as $item)
            @php
                $itemTitle = $item->title ?? $item->name ?? $item->item_name ?? 'Item';
                $itemPrice = $item->price_usd ?? $item->price ?? null;
                $itemStatusLabel = $item->status_label ?? ucfirst((string) ($item->status ?? 'Unknown'));
                $itemStatusBadgeClass = $item->status_badge_class ?? 'badge-primary';
                $itemCategory = $item->category->name ?? $item->itemCategory->name ?? __('messages.not_available');
                $itemSubcategory = $item->subcategory->name ?? $item->itemSubcategory->name ?? __('messages.not_available');
            @endphp

            @if(\Illuminate\Support\Facades\Route::has('admin.items.show'))
                <a href="{{ route('admin.items.show', $item->id) }}" style="display: block; margin-bottom: 12px;">
            @else
                <div style="display: block; margin-bottom: 12px;">
            @endif
                    <div class="activity-item">
                        <div class="activity-icon">📦</div>

                        <div class="activity-body" style="width: 100%;">
                            <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                                <h4 style="margin: 0;">{{ $itemTitle }}</h4>

                                <span class="badge {{ $itemStatusBadgeClass }}">
                                    {{ $itemStatusLabel }}
                                </span>
                            </div>

                            <p style="margin-top: 6px;">
                                {{ $itemCategory }} • {{ $itemSubcategory }}
                            </p>

                            @if($itemPrice !== null)
                                <p style="margin-top: 4px;">
                                    ${{ number_format((float) $itemPrice, 2) }}
                                </p>
                            @endif
                        </div>
                    </div>
            @if(\Illuminate\Support\Facades\Route::has('admin.items.show'))
                </a>
            @else
                </div>
            @endif
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>{{ __('messages.no_items_yet') }}</h3>
                <p></p>
            </div>
        @endforelse
    </x-card>


    <x-card class="subtle-panel" style="margin-top: 24px; border: 1px solid rgba(220,38,38,0.45);">
    <div style="margin-bottom: 20px;">
        <h2 class="section-title" style="color: #dc2626;">{{ __('messages.danger_zone') }}</h2>
        <p class="section-subtitle">
            {{ __('messages.delete_business_account_warning') }}
        </p>
    </div>

    <div class="info-row" style="border-top: 1px solid rgba(220,38,38,0.25);">
        <div>
            <div class="info-label" style="color: #dc2626;">{{ __('messages.delete_business_account') }}</div>
            <div class="text-muted">
                {{ __('messages.delete_business_account_warning_details') }}
            </div>
        </div>

        <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
            {{ __('messages.delete_business_account') }}
        </button>
    </div>
</x-card>

<div id="deleteModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.72); z-index: 9999; align-items: center; justify-content: center;">
    <div style="width: 520px; max-width: 92%; background: #05070d; border: 1px solid #30363d; border-radius: 16px; overflow: hidden; color: #f0f6fc;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 18px; border-bottom: 1px solid #30363d;">
            <strong>{{ __('messages.delete_business_account_modal_title', ['name' => $businessAccount->business_name]) }}</strong>

            <button type="button" onclick="closeDeleteModal()" style="background: #21262d; border: 0; color: #f0f6fc; border-radius: 8px; width: 34px; height: 34px; cursor: pointer;">
                ×
            </button>
        </div>

        <div style="padding: 28px 18px; text-align: center; border-bottom: 1px solid #30363d;">
            <div style="font-size: 34px; margin-bottom: 12px;">🏢</div>

            <h2 style="margin: 0 0 10px;">
                {{ $businessAccount->business_name }}
            </h2>

            <p style="color: #8b949e; margin: 0;">
                {{ __('messages.business_account_items_delete_count', ['count' => $businessAccount->items()->count()]) }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.business-accounts.destroy', $businessAccount) }}" style="padding: 18px;">
            @csrf
            @method('DELETE')

            <label style="display: block; font-weight: 800; margin-bottom: 8px;">
                {{ __('messages.confirm_delete_by_typing', ['name' => $businessAccount->business_name]) }}
            </label>

            <input
                id="deleteConfirmInput"
                type="text"
                autocomplete="off"
                style="width: 100%; box-sizing: border-box; background: #010409; color: #f0f6fc; border: 1px solid #f85149; border-radius: 8px; padding: 12px; margin-bottom: 12px;"
                oninput="checkDeleteConfirmation()"
            >

            <button
                id="deleteConfirmButton"
                type="submit"
                disabled
                style="width: 100%; padding: 12px; border-radius: 8px; border: 0; background: #21262d; color: #f85149; font-weight: 900; cursor: not-allowed;"
            >
                {{ __('messages.delete_this_business_account') }}
            </button>
        </form>
    </div>
</div>

<script>
    const requiredBusinessName = @json($businessAccount->business_name);

    function openDeleteModal() {
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.getElementById('deleteConfirmInput').value = '';
        checkDeleteConfirmation();
    }

    function checkDeleteConfirmation() {
        const input = document.getElementById('deleteConfirmInput');
        const button = document.getElementById('deleteConfirmButton');

        if (input.value === requiredBusinessName) {
            button.disabled = false;
            button.style.background = '#da3633';
            button.style.color = '#ffffff';
            button.style.cursor = 'pointer';
        } else {
            button.disabled = true;
            button.style.background = '#21262d';
            button.style.color = '#f85149';
            button.style.cursor = 'not-allowed';
        }
    }
</script>




@endsection
