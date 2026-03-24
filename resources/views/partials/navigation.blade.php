<aside class="sidebar">
    <div class="sidebar-title">Navigation</div>

    <nav class="nav-menu">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span>
            <span>Dashboard</span>
        </a>

        <a href="#"
           class="nav-link">
            <span class="nav-icon">🏢</span>
            <span>Business Accounts</span>
        </a>

        <a href="#"
           class="nav-link">
            <span class="nav-icon">⚙️</span>
            <span>Settings</span>
        </a>
    </nav>
</aside>
