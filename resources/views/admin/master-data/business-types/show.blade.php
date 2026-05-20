@extends('layouts.app')

@section('title', __('messages.business_type_details'))

@section('content')
    <x-page-title
        :title="__('messages.business_type_details')"
        :subtitle="__('messages.business_type_details_subtitle')"
    >
        <x-slot:actions>
            <a href="{{ route('admin.master-data.business-types.index') }}" class="btn btn-outline">
                <span>←</span>
                <span>{{ __('messages.back_to_business_types') }}</span>
            </a>
        </x-slot:actions>
    </x-page-title>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div class="info-list">
            <div class="info-row">
                <div class="info-label">{{ __('messages.name_english') }}</div>
                <div class="info-value">{{ $businessType->name_en }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.name_arabic') }}</div>
                <div class="info-value">{{ $businessType->name_ar }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.related_business_accounts') }}</div>
                <div class="info-value">{{ $businessType->business_accounts_count }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">{{ __('messages.created_at') }}</div>
                <div class="info-value">{{ $businessType->created_at->format('Y-m-d h:i A') }}</div>
            </div>
        </div>
    </x-card>

    <x-card class="subtle-panel" style="margin-bottom: 24px;">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title">{{ __('messages.edit_business_type') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.edit_business_type_subtitle') }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.master-data.business-types.update', $businessType->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="align-items: end; margin-bottom: 16px;">
                <div>
                    <label class="form-label">{{ __('messages.name_english') }}</label>
                    <input
                        type="text"
                        name="name_en"
                        class="form-input"
                        value="{{ old('name_en', $businessType->name_en) }}"
                        placeholder="{{ __('messages.enter_name_in_english') }}"
                        required
                    >
                </div>

                <div>
                    <label class="form-label">{{ __('messages.name_arabic') }}</label>
                    <input
                        type="text"
                        name="name_ar"
                        class="form-input"
                        value="{{ old('name_ar', $businessType->name_ar) }}"
                        placeholder="{{ __('messages.enter_name_in_arabic') }}"
                        required
                    >
                </div>
            </div>

            <div>
                <x-button type="submit" variant="primary">
                    <span>💾</span>
                    <span>{{ __('messages.update_type') }}</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card class="subtle-panel" style="border: 1px solid rgba(220,38,38,0.45);">
        <div style="margin-bottom: 20px;">
            <h2 class="section-title" style="color: #dc2626;">{{ __('messages.danger_zone') }}</h2>
            <p class="section-subtitle">
                {{ __('messages.delete_business_type_warning') }}
            </p>
        </div>

        <div class="info-row" style="border-top: 1px solid rgba(220,38,38,0.25);">
            <div>
                <div class="info-label" style="color: #dc2626;">{{ __('messages.delete_business_type') }}</div>
                <div class="text-muted">
                    {{ __('messages.delete_this_business_type_will_also_delete__all_business_accounts_that_use_it') }}

                </div>
            </div>

            <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
                {{ __('messages.delete_business_type') }}
            </button>
        </div>
    </x-card>

    <div id="deleteModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.72); z-index: 9999; align-items: center; justify-content: center;">
        <div style="width: 520px; max-width: 92%; background: #05070d; border: 1px solid #30363d; border-radius: 16px; overflow: hidden; color: #f0f6fc;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 18px; border-bottom: 1px solid #30363d;">
                <strong>Delete {{ $businessType->name_en }}</strong>

                <button type="button" onclick="closeDeleteModal()" style="background: #21262d; border: 0; color: #f0f6fc; border-radius: 8px; width: 34px; height: 34px; cursor: pointer;">
                    ×
                </button>
            </div>

            <div style="padding: 28px 18px; text-align: center; border-bottom: 1px solid #30363d;">
                <div style="font-size: 34px; margin-bottom: 12px;">🏷️</div>

                <h2 style="margin: 0 0 10px;">
                    {{ $businessType->name_en }}
                </h2>

                <p style="color: #8b949e; margin: 0;">
                    {{ $businessType->business_accounts_count }} related business accounts may be deleted.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.master-data.business-types.destroy', $businessType->id) }}" style="padding: 18px;">
                @csrf
                @method('DELETE')

                <label style="display: block; font-weight: 800; margin-bottom: 8px;">
                    To confirm, type "{{ $businessType->name_en }}" in the box below
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
                    Delete this business type
                </button>
            </form>
        </div>
    </div>

    <script>
        const requiredBusinessTypeName = @json($businessType->name_en);

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

            if (input.value === requiredBusinessTypeName) {
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
