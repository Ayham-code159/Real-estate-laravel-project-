<aside class="sidebar">
    <div class="sidebar-title">Navigation</div>

    <nav class="nav-menu">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span>
            <span>Dashboard</span>
        </a>

        @if(auth('admin')->user()?->canManageBusinessAccounts())
            <a href="{{ route('admin.business-accounts.index') }}"
               class="nav-link {{ request()->routeIs('admin.business-accounts.*') ? 'active' : '' }}">
                <span class="nav-icon">🏢</span>
                <span>Business Accounts</span>
            </a>
        @endif

        @if(auth('admin')->user()?->canManageUsers())
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👤</span>
                <span>Users</span>
            </a>
        @endif

        @if(auth('admin')->user()?->isSuperAdmin())
            <a href="{{ route('admin.admins.index') }}"
               class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <span class="nav-icon">🛡️</span>
                <span>Admins</span>
            </a>

            <a href="{{ route('admin.service-listings.index') }}"
               class="nav-link {{ request()->routeIs('admin.service-listings.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span>
                <span>Listings</span>
            </a>

            <a href="{{ route('admin.master-data.business-types.index') }}"
               class="nav-link {{ request()->routeIs('admin.master-data.business-types.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span>
                <span>Business Types</span>
            </a>

            <a href="{{ route('admin.master-data.services.index') }}"
               class="nav-link {{ request()->routeIs('admin.master-data.services.*') ? 'active' : '' }}">
                <span class="nav-icon">🧩</span>
                <span>Services</span>
            </a>
        @endif

        <a href="#"
           class="nav-link">
            <span class="nav-icon">⚙️</span>
            <span>Settings</span>
        </a>
    </nav>
</aside>
