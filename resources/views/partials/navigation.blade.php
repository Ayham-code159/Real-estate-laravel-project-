<aside class="sidebar">
    <div class="sidebar-title">{{ __('messages.navigation') }}</div>

    <nav class="nav-menu">

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span>
            <span>{{ __('messages.dashboard') }}</span>
        </a>

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

        @if(auth('admin')->user()?->isSuperAdmin())

            <a href="{{ route('admin.admins.index') }}"
               class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <span class="nav-icon">🛡️</span>
                <span>{{ __('messages.admins') }}</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="nav-icon">🗂️</span>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.service-listings.index') }}"
               class="nav-link {{ request()->routeIs('admin.service-listings.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span>
                <span>{{ __('messages.listings') }}</span>
            </a>

            <a href="{{ route('admin.master-data.business-types.index') }}"
               class="nav-link {{ request()->routeIs('admin.master-data.business-types.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span>
                <span>{{ __('messages.business_types') }}</span>
            </a>

            <a href="{{ route('admin.master-data.services.index') }}"
               class="nav-link {{ request()->routeIs('admin.master-data.services.*') ? 'active' : '' }}">
                <span class="nav-icon">🧩</span>
                <span>{{ __('messages.services') }}</span>
            </a>

            <a href="{{ route('admin.items.index') }}"
                class="nav-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                 <span class="nav-icon">📦</span>
                 <span>Items</span>
            </a>

        @endif

        <a href="#"
           class="nav-link">
            <span class="nav-icon">⚙️</span>
            <span>{{ __('messages.settings') }}</span>
        </a>

    </nav>
</aside>
