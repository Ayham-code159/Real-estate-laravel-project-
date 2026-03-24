<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <style>
        :root {
            --primary: #6F3CC3;
            --primary-dark: #5E2FB0;
            --primary-soft: #EFE7FF;
            --primary-light: #F7F2FF;
            --page-bg: #F7F8FC;
            --card-bg: #FFFFFF;
            --border: #E7EAF3;
            --text-main: #172033;
            --text-muted: #6B7280;
            --text-soft: #8A93A5;
            --danger: #DC2626;
            --danger-soft: #FFF1F2;
            --success: #16A34A;
            --success-soft: #ECFDF3;
            --warning: #D97706;
            --warning-soft: #FFF7ED;
            --shadow-sm: 0 8px 20px rgba(15, 23, 42, 0.05);
            --shadow-md: 0 16px 40px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 22px 50px rgba(111, 60, 195, 0.12);
            --radius-xl: 26px;
            --radius-lg: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;
            --transition: 0.22s ease;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text-main);
            background: #f7f7fc;
            overflow-x: hidden;
            position: relative;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input {
            font-family: inherit;
        }

        .admin-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        .admin-main {
            flex: 1;
            display: flex;
            position: relative;
        }

        /*
        =========================
        MAIN DASHBOARD CANVAS
        =========================
        This is where the "real"
        purple atmosphere lives.
        */

        .admin-content {
            flex: 1;
            padding: 32px;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 8% 82%, rgba(175, 142, 255, 0.28), transparent 26%),
                radial-gradient(circle at 48% 8%, rgba(157, 104, 255, 0.24), transparent 20%),
                radial-gradient(circle at 88% 8%, rgba(175, 128, 255, 0.28), transparent 24%),
                radial-gradient(circle at 100% 62%, rgba(199, 164, 255, 0.18), transparent 23%),
                linear-gradient(135deg, #f3efff 0%, #f7f4ff 45%, #f3f0ff 75%, #f7f8fc 100%);
        }

        /*
        soft white mist
        */
        .admin-content::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 30%, rgba(255,255,255,0.68) 0, rgba(255,255,255,0) 34%),
                radial-gradient(circle at 62% 14%, rgba(255,255,255,0.64) 0, rgba(255,255,255,0) 26%),
                radial-gradient(circle at 86% 58%, rgba(255,255,255,0.42) 0, rgba(255,255,255,0) 24%);
            pointer-events: none;
            z-index: 0;
        }

        /*
        particles / subtle sparkles
        */
        .admin-content::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(rgba(255,255,255,0.95) 1.4px, transparent 1.4px),
                radial-gradient(rgba(255,255,255,0.55) 0.9px, transparent 0.9px),
                radial-gradient(rgba(164, 130, 255, 0.18) 0.8px, transparent 0.8px);
            background-size:
                190px 190px,
                240px 240px,
                280px 280px;
            background-position:
                0 0,
                60px 90px,
                30px 50px;
            opacity: 0.52;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .card {
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid rgba(231, 234, 243, 0.95);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: #ddd8f7;
        }

        .card-body {
            padding: 26px;
        }

        .glass-accent {
            position: relative;
            overflow: hidden;
        }

        .glass-accent::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(111, 60, 195, 0.04), transparent 40%);
            pointer-events: none;
        }

        .btn {
            border: none;
            outline: none;
            cursor: pointer;
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 15px;
            font-weight: 700;
            transition: transform var(--transition), background var(--transition), box-shadow var(--transition), border-color var(--transition), color var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #8B5CF6);
            color: #fff;
            box-shadow: 0 14px 30px rgba(111, 60, 195, 0.22);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), #7C3AED);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 1px solid #d8c8ff;
        }

        .btn-outline:hover {
            background: var(--primary-light);
        }

        .btn-danger {
            background: var(--danger-soft);
            color: var(--danger);
            border: 1px solid #fecdd3;
        }

        .btn-danger:hover {
            background: #ffe4e6;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 9px;
            color: var(--text-main);
            font-size: 14px;
            font-weight: 700;
        }

        .form-input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 14px 16px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.94);
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition), transform var(--transition);
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(111, 60, 195, 0.10);
            transform: translateY(-1px);
        }

        .text-muted {
            color: var(--text-muted);
        }

        .text-soft {
            color: var(--text-soft);
        }

        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .stats-card {
            padding: 22px;
            position: relative;
            overflow: hidden;
        }

        .stats-card::after {
            content: "";
            position: absolute;
            width: 110px;
            height: 110px;
            right: -28px;
            top: -28px;
            border-radius: 50%;
            background: rgba(111, 60, 195, 0.06);
        }

        .stats-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .stats-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            background: var(--primary-light);
            color: var(--primary);
            box-shadow: inset 0 0 0 1px rgba(111, 60, 195, 0.08);
        }

        .stats-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 6px;
            font-weight: 600;
        }

        .stats-value {
            font-size: 30px;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stats-meta {
            font-size: 13px;
            color: var(--text-soft);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-primary {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .badge-success {
            background: var(--success-soft);
            color: var(--success);
        }

        .badge-warning {
            background: var(--warning-soft);
            color: var(--warning);
        }

        .badge-danger {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .alert-danger {
            background: #FEF2F2;
            color: var(--danger);
            border: 1px solid #FECACA;
        }

        .alert-success {
            background: var(--success-soft);
            color: var(--success);
            border: 1px solid #BBF7D0;
        }

        .page-title {
            margin-bottom: 28px;
        }

        .page-title-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .page-title h1 {
            margin: 0;
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .page-title p {
            margin: 0;
            color: var(--text-muted);
            font-size: 16px;
            line-height: 1.7;
            max-width: 760px;
        }

        .section-title {
            margin: 0 0 10px;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .section-subtitle {
            margin: 0;
            color: var(--text-muted);
            font-size: 15px;
            line-height: 1.7;
        }

        .info-list {
            display: grid;
            gap: 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 18px 0;
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--text-muted);
            font-weight: 700;
            font-size: 15px;
        }

        .info-value {
            font-weight: 800;
            color: var(--text-main);
            text-align: right;
        }

        /*
        CLEANER TOPBAR + SIDEBAR
        */
        .topbar {
            background: rgba(255, 255, 255, 0.74);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(231, 234, 243, 0.88);
            padding: 18px 32px;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .topbar-inner {
            max-width: 1240px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-badge {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--primary), #8B5CF6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 22px;
            box-shadow: var(--shadow-lg);
        }

        .brand-text h2 {
            margin: 0;
            font-size: 19px;
            font-weight: 800;
        }

        .brand-text p {
            margin: 3px 0 0;
            color: var(--text-muted);
            font-size: 13px;
        }

        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.58);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-right: 1px solid rgba(231, 234, 243, 0.88);
            padding: 28px 14px;
            position: relative;
            z-index: 1;
        }

        .sidebar-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.10em;
            color: var(--text-muted);
            margin-bottom: 18px;
            font-weight: 800;
        }

        .nav-menu {
            display: grid;
            gap: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 16px;
            border-radius: 16px;
            color: var(--text-main);
            font-weight: 700;
            transition: background var(--transition), color var(--transition), transform var(--transition), box-shadow var(--transition);
        }

        .nav-link:hover {
            transform: translateX(2px);
            background: rgba(247, 243, 255, 0.92);
            color: var(--primary);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(241, 232, 255, 0.97), rgba(251, 248, 255, 0.94));
            color: var(--primary);
            box-shadow: inset 0 0 0 1px #E6DBFF;
        }

        .nav-icon {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #F7F4FE;
            font-size: 15px;
        }

        .footer {
            padding: 18px 32px;
            border-top: 1px solid rgba(231, 234, 243, 0.88);
            background: rgba(255, 255, 255, 0.74);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            color: var(--text-muted);
            font-size: 13px;
            position: relative;
            z-index: 1;
        }

        /*
        AUTH PAGE
        */
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 18px;
            background:
                radial-gradient(circle at 18% 80%, rgba(175, 142, 255, 0.26), transparent 28%),
                radial-gradient(circle at 80% 10%, rgba(157, 104, 255, 0.24), transparent 22%),
                radial-gradient(circle at 92% 88%, rgba(199, 164, 255, 0.16), transparent 24%),
                linear-gradient(135deg, #f3efff 0%, #f8f5ff 45%, #f7f8fc 100%);
            position: relative;
            overflow: hidden;
        }

        .auth-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(rgba(255,255,255,0.90) 1.3px, transparent 1.3px),
                radial-gradient(rgba(164, 130, 255, 0.18) 0.8px, transparent 0.8px);
            background-size: 170px 170px, 240px 240px;
            background-position: 0 0, 50px 70px;
            opacity: 0.55;
            pointer-events: none;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            padding: 32px;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(231, 234, 243, 0.95);
            border-radius: 30px;
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(111, 60, 195, 0.06), transparent 38%);
            pointer-events: none;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
        }

        .auth-logo {
            width: 70px;
            height: 70px;
            border-radius: 22px;
            background: linear-gradient(135deg, var(--primary), #8B5CF6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            font-size: 28px;
            font-weight: 800;
            box-shadow: 0 18px 40px rgba(111, 60, 195, 0.24);
        }

        .auth-header h1 {
            margin: 0 0 10px;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .auth-header p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .auth-footer {
            text-align: center;
            margin-top: 18px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: 1.4fr 0.9fr;
            gap: 20px;
        }

        .activity-list {
            display: grid;
            gap: 14px;
            margin-top: 20px;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: rgba(252, 252, 255, 0.84);
            transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
        }

        .activity-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
            border-color: #ddd8f7;
        }

        .activity-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 16px;
            flex-shrink: 0;
        }

        .activity-body h4 {
            margin: 0 0 6px;
            font-size: 15px;
            font-weight: 800;
        }

        .activity-body p {
            margin: 0;
            font-size: 13px;
            line-height: 1.6;
            color: var(--text-muted);
        }

        .subtle-panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.90) 0%, rgba(252, 252, 255, 0.84) 100%);
        }

        .empty-state {
            text-align: center;
            padding: 26px 20px;
            border: 1px dashed #d7dbe8;
            border-radius: 18px;
            background: rgba(252, 252, 255, 0.84);
        }

        .empty-state-icon {
            width: 54px;
            height: 54px;
            margin: 0 auto 12px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 22px;
            font-weight: 800;
        }

        .empty-state h3 {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .empty-state p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .section-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        @media (max-width: 1100px) {
            .overview-grid,
            .grid-4,
            .grid-3 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                display: none;
            }

            .admin-content {
                padding: 20px;
            }

            .grid-2,
            .grid-3,
            .grid-4,
            .overview-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 16px 20px;
            }

            .page-title h1 {
                font-size: 34px;
            }
        }

        @media (max-width: 560px) {
            .auth-card {
                padding: 24px;
            }

            .page-title h1 {
                font-size: 28px;
            }

            .stats-value {
                font-size: 24px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-value {
                text-align: left;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @hasSection('auth')
        @yield('auth')
    @else
        <div class="admin-shell">
            @include('partials.header')

            <div class="admin-main">
                @include('partials.navigation')

                <main class="admin-content">
                    <div class="container">
                        @include('partials.flash-messages')
                        @yield('content')
                    </div>
                </main>
            </div>

            @include('partials.footer')
        </div>
    @endif

    @stack('scripts')
</body>
</html>
