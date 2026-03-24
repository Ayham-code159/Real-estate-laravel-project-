<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <div class="brand-badge">S</div>

            <div class="brand-text">
                <h2>Servixa Admin</h2>
                <p>Control panel</p>
            </div>
        </div>

        @auth('admin')
            <div class="badge badge-primary">
                {{ str_replace('_', ' ', ucwords(auth('admin')->user()->role, '_')) }}
            </div>
        @endauth
    </div>
</header>
