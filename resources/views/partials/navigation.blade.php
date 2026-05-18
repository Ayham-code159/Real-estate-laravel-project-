<aside class="sidebar">
    <div class="sidebar-title">{{ __('messages.navigation') }}</div>

    <nav class="nav-menu">

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span>
            <span>{{ __('messages.dashboard') }}</span>
        </a>

        @if(auth('admin')->user()?->isSuperAdmin())
            <a href="{{ route('admin.admins.index') }}"
               class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <span class="nav-icon">🛡️</span>
                <span>{{ __('messages.admins') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageBusinessAccounts())
            <a href="{{ route('admin.business-accounts.index') }}"
               class="nav-link {{ request()->routeIs('admin.business-accounts.*') ? 'active' : '' }}">
                <span class="nav-icon">🏢</span>
                <span>{{ __('messages.business_accounts') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageUsers())
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👤</span>
                <span>{{ __('messages.users') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageBusinessTypes())
            <a href="{{ route('admin.master-data.business-types.index') }}"
               class="nav-link {{ request()->routeIs('admin.master-data.business-types.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span>
                <span>{{ __('messages.business_types') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageCategories())
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="nav-icon">🗂️</span>
                <span>{{ __('messages.categories') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageItems())
            <a href="{{ route('admin.items.index') }}"
               class="nav-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span>
                <span>{{ __('messages.items') }}</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageSliders())
            <a href="{{ route('admin.sliders.index') }}"
               class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                <span class="nav-icon">🎞️</span>
                <span>{{ __('messages.sliders') }}</span>
            </a>
        @endif

        <a href="#" class="nav-link">
            <span class="nav-icon">⚙️</span>
            <span>{{ __('messages.settings') }}</span>
        </a>

    </nav>
</aside>
