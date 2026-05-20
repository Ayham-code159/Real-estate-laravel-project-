<aside class="sidebar">
    <div class="sidebar-title">
        {{ __('messages.navigation') }}
    </div>

    <nav class="nav-menu">

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span>
            <span class="nav-text">{{ __('messages.dashboard') }}</span>
        </a>

        @if(auth('admin')->user()?->isSuperAdmin())
            <a href="{{ route('admin.admins.index') }}"
               class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <span class="nav-icon">🛡️</span>
                <span class="nav-text">{{ __('messages.admins') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageBusinessAccounts())
            <a href="{{ route('admin.business-accounts.index') }}"
               class="nav-link {{ request()->routeIs('admin.business-accounts.*') ? 'active' : '' }}">
                <span class="nav-icon">🏢</span>
                <span class="nav-text">{{ __('messages.business_accounts') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageUsers())
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👤</span>
                <span class="nav-text">{{ __('messages.users') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageBusinessTypes())
            <a href="{{ route('admin.master-data.business-types.index') }}"
               class="nav-link {{ request()->routeIs('admin.master-data.business-types.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span>
                <span class="nav-text">{{ __('messages.business_types') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageCategories())
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="nav-icon">🗂️</span>
                <span class="nav-text">{{ __('messages.categories') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageItems())
            <a href="{{ route('admin.items.index') }}"
               class="nav-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span>
                <span class="nav-text">{{ __('messages.items') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageSliders())
            <a href="{{ route('admin.sliders.index') }}"
               class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                <span class="nav-icon">🎞️</span>
                <span class="nav-text">{{ __('messages.sliders') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageCities())
            <a href="{{ route('admin.cities.index') }}"
                class="nav-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                    <span class="nav-icon">🏙️</span>
                    <span class="nav-text">{{ __('messages.cities') }}</span>
            </a>
        @endif





      

    </nav>
</aside>
