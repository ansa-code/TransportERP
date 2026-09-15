<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Al Shaqra Transport</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: linear-gradient(
                to right,
                #08182b 0,
                #08182b 260px,
                #f4f6f9 260px,
                #f4f6f9 100%
            );
        }

        /* =========================================================
           SIDEBAR
        ========================================================= */

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #08182b;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #334e68;
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #08182b;
        }

        /* =========================================================
           BRAND
        ========================================================= */

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 2px 8px 20px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            border-radius: 15px;
            background: linear-gradient(
                135deg,
                #087cff,
                #18b6a4
            );
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 21px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .brand-text {
            min-width: 0;
        }

        .brand-title {
            font-size: 17px;
            font-weight: 800;
            line-height: 1.1;
            color: #ffffff;
        }

        .brand-subtitle {
            font-size: 12px;
            font-weight: 600;
            color: #8fa3b8;
            margin-top: 6px;
        }

        /* =========================================================
           MENU SECTION TITLE
        ========================================================= */

        .menu-section-title {
            color: #71869d;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.2px;
            padding: 0 12px;
            margin: 5px 0 12px;
        }

        /* =========================================================
           GENERAL SIDEBAR MENU
        ========================================================= */

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 2px;
        }

        .sidebar ul li a,
        .finance-toggle,
        .assignment-toggle {
            min-height: 42px;
            padding: 8px 12px;
            border-radius: 12px;
            color: #c9d4df;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 14px;
            font-weight: 600;
            transition: .2s;
            cursor: pointer;
        }

        .sidebar ul li a:hover,
        .finance-toggle:hover,
        .assignment-toggle:hover {
            background: #102640;
            color: white;
        }

        .menu-icon {
            width: 23px;
            min-width: 23px;
            text-align: center;
            font-size: 17px;
            line-height: 1;
        }

        .sidebar ul li.active > a {
            background: #163b78;
            color: white;
            box-shadow:
                inset 0 0 0 1px rgba(60, 145, 255, .45),
                0 4px 12px rgba(0, 0, 0, .15);
        }

        .sidebar ul li.active > a .menu-icon {
            color: #4ca3ff;
        }

        /* =========================================================
           ASSIGNMENT MENU
        ========================================================= */

        .assignment-menu {
            margin-bottom: 5px;
        }

        .assignment-toggle {
            width: 100%;
            border: none;
            background: transparent;
            font-family: inherit;
            text-align: left;
        }

        .assignment-arrow {
            margin-left: auto;
            font-size: 12px;
            transition: .2s;
        }

        .assignment-submenu {
            display: none;
            padding: 4px 0 4px 38px;
        }

        .assignment-menu.open .assignment-submenu {
            display: block;
        }

        .assignment-menu.open .assignment-arrow {
            transform: rotate(90deg);
        }

        .assignment-submenu a {
            min-height: 40px !important;
            padding: 8px 12px !important;
            border-radius: 10px !important;
            font-size: 13px !important;
        }

        .assignment-submenu a:hover {
            background: #102640;
            color: white;
        }

        .assignment-submenu a.active {
            background: #163b78;
            color: white;
        }

        /* =========================================================
           FINANCE MENU
        ========================================================= */

        .finance-menu {
            margin-bottom: 5px;
        }

        .finance-toggle {
            width: 100%;
            border: none;
            background: transparent;
            font-family: inherit;
            text-align: left;
        }

        .finance-arrow {
            margin-left: auto;
            font-size: 12px;
            transition: .2s;
        }

        .finance-submenu {
            display: none;
            padding: 4px 0 4px 38px;
        }

        .finance-menu.open .finance-submenu {
            display: block;
        }

        .finance-menu.open .finance-arrow {
            transform: rotate(90deg);
        }

        .finance-submenu a {
            min-height: 40px !important;
            padding: 8px 12px !important;
            border-radius: 10px !important;
            font-size: 13px !important;
        }

        .finance-submenu a:hover {
            background: #102640;
            color: white;
        }

        .finance-submenu a.active {
            background: #163b78;
            color: white;
        }

        /* =========================================================
           ADMINISTRATION
        ========================================================= */

        .admin-section {
            margin-top: 18px;
            padding: 0 0 4px;
        }

        .admin-title {
            color: #71869d;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.2px;
            padding: 0 12px;
            margin: 0 0 12px;
        }

        .admin-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-menu li {
            margin-bottom: 2px;
        }

        .admin-menu li a {
            min-height: 42px;
        }

        .admin-menu li.active > a {
            background: #163b78;
            color: white;
            box-shadow:
                inset 0 0 0 1px rgba(60, 145, 255, .45),
                0 4px 12px rgba(0, 0, 0, .15);
        }

        .admin-menu li.active > a .menu-icon {
            color: #4ca3ff;
        }

        /* =========================================================
           BILINGUAL
        ========================================================= */

        .bilingual-section {
            margin-top: 18px;
            padding-bottom: 20px;
        }

        .language-link {
            display: flex !important;
            align-items: center;
            gap: 13px;
        }

        .language-icon {
            color: #00a8e8;
            font-size: 20px;
        }

        /* =========================================================
           MAIN AREA
        ========================================================= */

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            background: #f4f6f9;
        }

        /* =========================================================
           NAVBAR
        ========================================================= */

        .navbar {
            height: 70px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
        }

        .navbar h2 {
            color: #1d3557;
            font-size: 21px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .alert-link {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #1d3557;
            font-size: 13px;
            font-weight: 700;
            background: #fff3cd;
            padding: 8px 12px;
            border-radius: 8px;
            white-space: nowrap;
        }

        .alert-link:hover {
            background: #ffe69c;
            color: #1d3557;
        }

        .alert-link-count {
            font-weight: 800;
        }

        .profile {
            font-weight: bold;
            color: #1d3557;
            white-space: nowrap;
        }

        /* =========================================================
           CONTENT
        ========================================================= */

        .content {
            padding: 30px;
            min-height: calc(100vh - 70px);
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #f5c6cb;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 900px) {

            .sidebar {
                width: 230px;
            }

            .main {
                margin-left: 230px;
                width: calc(100% - 230px);
            }

            .brand-title {
                font-size: 15px;
            }

            .navbar-right {
                gap: 10px;
            }

            .alert-link {
                padding: 7px 9px;
            }

        }

        @media (max-width: 650px) {

            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
                width: calc(100% - 220px);
            }

            .navbar {
                padding: 0 18px;
            }

            .content {
                padding: 18px;
            }

            .navbar-right {
                gap: 8px;
            }

            .alert-link {
                font-size: 12px;
                padding: 7px 8px;
            }

        }

    </style>

</head>

<body>

    <!-- =========================================================
         SIDEBAR
    ========================================================= -->

    <div class="sidebar">

        <div class="brand">

            <div class="brand-logo">
                AS
            </div>

            <div class="brand-text">

                <div class="brand-title">
                    AL SHAQRA<br>
                    TRANSPORT
                </div>

                <div class="brand-subtitle">
                    Fleet ERP Platform
                </div>

            </div>

        </div>


        <div class="menu-section-title">
            MAIN MENU
        </div>

        <ul>

            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">

                <a href="/dashboard">

                    <span class="menu-icon">📊</span>

                    <span>Dashboard</span>

                </a>

            </li>


            <li class="{{ request()->is('vehicles*') ? 'active' : '' }}">

                <a href="/vehicles">

                    <span class="menu-icon">🚛</span>

                    <span>Fleet</span>

                </a>

            </li>


            <li class="{{ request()->is('drivers*') ? 'active' : '' }}">

                <a href="/drivers">

                    <span class="menu-icon">👤</span>

                    <span>Drivers</span>

                </a>

            </li>


            <li class="{{ request()->is('clients*') ? 'active' : '' }}">

                <a href="/clients">

                    <span class="menu-icon">🏢</span>

                    <span>Clients</span>

                </a>

            </li>


            <li class="assignment-menu
                {{ request()->is('assignments*')
                    || request()->is('trips*')
                    || request()->is('leaves*')
                    ? 'open'
                    : '' }}">

                <button
                    type="button"
                    class="assignment-toggle"
                >

                    <span class="menu-icon">🔄</span>

                    <span>Assignments</span>

                    <span class="assignment-arrow">▶</span>

                </button>


                <div class="assignment-submenu">

                    <a
                        href="/assignments"
                        class="{{ request()->is('assignments*') ? 'active' : '' }}"
                    >
                        Assignment Center
                    </a>

                    <a
                        href="/trips"
                        class="{{ request()->is('trips*') ? 'active' : '' }}"
                    >
                        Trips
                    </a>

                    <a
                        href="/leaves"
                        class="{{ request()->is('leaves*') ? 'active' : '' }}"
                    >
                        Leave
                    </a>

                </div>

            </li>


            <li class="{{ request()->is('maintenances*') ? 'active' : '' }}">

                <a href="/maintenances">

                    <span class="menu-icon">🛠️</span>

                    <span>Maintenance</span>

                </a>

            </li>


            <li class="finance-menu
                {{ request()->is('vendors*')
                    || request()->is('fuels*')
                    || request()->is('expenses*')
                    || request()->is('payrolls*')
                    || request()->is('invoices*')
                    || request()->is('payments*')
                    || request()->is('driver-advances*')
                    || request()->is('traffic-fines*')
                    ? 'open'
                    : '' }}">

                <button
                    type="button"
                    class="finance-toggle"
                >

                    <span class="menu-icon">💳</span>

                    <span>Finance</span>

                    <span class="finance-arrow">▶</span>

                </button>


                <div class="finance-submenu">

                    <a
                        href="/vendors"
                        class="{{ request()->is('vendors*') ? 'active' : '' }}"
                    >
                        Vendors
                    </a>

                    <a
                        href="/fuels"
                        class="{{ request()->is('fuels*') ? 'active' : '' }}"
                    >
                        Fuels
                    </a>

                    <a
                        href="/expenses"
                        class="{{ request()->is('expenses*') ? 'active' : '' }}"
                    >
                        Expenses
                    </a>

                    <a
                        href="/payrolls"
                        class="{{ request()->is('payrolls*') ? 'active' : '' }}"
                    >
                        Payroll
                    </a>

                    <a
                        href="/invoices"
                        class="{{ request()->is('invoices*') ? 'active' : '' }}"
                    >
                        Invoices
                    </a>

                    <a
                        href="{{ route('payments.index') }}"
                        class="{{ request()->routeIs('payments.*') ? 'active' : '' }}"
                    >
                        Payments
                    </a>

                    <a
                        href="{{ route('driver-advances.index') }}"
                        class="{{ request()->routeIs('driver-advances.*') ? 'active' : '' }}"
                    >
                        Driver Advances
                    </a>

                    <a
                        href="{{ route('traffic-fines.index') }}"
                        class="{{ request()->routeIs('traffic-fines.*') ? 'active' : '' }}"
                    >
                        Traffic Fines
                    </a>

                </div>

            </li>


            <!-- Reports -->

            <li class="{{ request()->is('reports*') ? 'active' : '' }}">

                <a href="/reports">

                    <span class="menu-icon">📈</span>

                    <span>Reports</span>

                </a>

            </li>

        </ul>
``
<!-- =====================================================
             ADMINISTRATION
        ====================================================== -->

        <div class="admin-section">

            <div class="admin-title">
                ADMINISTRATION
            </div>


            <ul class="admin-menu">

                <!-- Documents -->

                <li class="{{ request()->routeIs('documents.*') ? 'active' : '' }}">

                    <a href="{{ route('documents.index') }}">

                        <span class="menu-icon">📄</span>

                        <span>Documents</span>

                    </a>

                </li>


                <!-- Users -->

                <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">

                    <a href="{{ route('users.index') }}">

                        <span class="menu-icon">👥</span>

                        <span>Users</span>

                    </a>

                </li>


                <!-- Roles -->

                <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">

                    <a href="{{ route('roles.index') }}">

                        <span class="menu-icon">🛡️</span>

                        <span>Roles</span>

                    </a>

                </li>


                <!-- Activity Logs -->

                <li class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">

                    <a href="{{ route('activity-logs.index') }}">

                        <span class="menu-icon">📋</span>

                        <span>Activity Logs</span>

                    </a>

                </li>


                <!-- Settings -->

                <li class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">

                   <a href="{{ route('settings.index') }}">

                        <span class="menu-icon">⚙️</span>

                        <span>Settings</span>

                    </a>

                </li>

            </ul>

        </div>


        <!-- =====================================================
             BILINGUAL
        ====================================================== -->

        <div class="bilingual-section">

            <div class="menu-section-title">
                BILINGUAL
            </div>


            <ul>

                <li>

                    <a
                        href="#"
                        class="language-link"
                    >

                        <span class="language-icon">🌐</span>

                        <span>EN / العربية</span>

                    </a>

                </li>

            </ul>

        </div>

    </div>


    <!-- =========================================================
         MAIN
    ========================================================= -->

    <div class="main">


        <!-- =====================================================
             NAVBAR
        ====================================================== -->

        <div class="navbar">

            <h2>

                @if(request()->is('dashboard'))

                    Dashboard

                @elseif(request()->is('vehicles*'))

                    Fleet

                @elseif(request()->is('drivers*'))

                    Drivers

                @elseif(request()->is('clients*'))

                    Clients

                @elseif(request()->is('assignments*'))

                    Assignments

                @elseif(request()->is('trips*'))

                    Trips

                @elseif(request()->is('leaves*'))

                    Leave

                @elseif(request()->is('maintenances*'))

                    Maintenance

                @elseif(request()->is('vendors*'))

                    Vendors

                @elseif(request()->is('fuels*'))

                    Fuels

                @elseif(request()->is('expenses*'))

                    Expenses

                @elseif(request()->is('payrolls*'))

                    Payroll

                @elseif(request()->is('invoices*'))

                    Invoices

                @elseif(request()->is('payments*'))

                    Payments

                @elseif(request()->is('driver-advances*'))

                    Driver Advances

                @elseif(request()->is('traffic-fines*'))

                    Traffic Fines

                @elseif(request()->is('reports*'))

                    Reports

                @elseif(request()->is('alerts*'))

                    Alerts & Notifications

                @elseif(request()->is('documents*'))

                    Documents & Expiry Center

                @elseif(request()->is('users*'))

                    Users

                @elseif(request()->is('roles*'))

                    Roles

                @elseif(request()->is('activity-logs*'))

                    Activity Logs

                @elseif(request()->is('settings*'))

                    Settings

                @else

                    Transport ERP

                @endif

            </h2>


            <div class="navbar-right">

                <a
                    href="{{ route('alerts.index') }}"
                    class="alert-link"
                >

                    <span>🔔</span>

                    <span class="alert-link-count">
                        {{ $alertCount ?? 0 }}
                    </span>

                    <span>Alerts</span>

                </a>


                <div class="profile">
                    Admin
                </div>

            </div>

        </div>


        <!-- =====================================================
             CONTENT START
        ====================================================== -->

        <div class="content">

            @if(session('success'))

                <div class="success-message">
                    {{ session('success') }}
                </div>

            @endif


            @if(session('error'))

                <div class="error-message">
                    {{ session('error') }}
                </div>

            @endif

            @yield('content')

        </div>

    </div>
    <!-- =========================================================
         JAVASCRIPT
    ========================================================= -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {


            /* =====================================================
               ASSIGNMENT MENU
            ===================================================== */

            const assignmentToggle =
                document.querySelector('.assignment-toggle');

            const assignmentMenu =
                document.querySelector('.assignment-menu');


            if (assignmentToggle && assignmentMenu) {

                assignmentToggle.addEventListener(
                    'click',
                    function () {

                        assignmentMenu.classList.toggle('open');

                    }
                );

            }


            /* =====================================================
               FINANCE MENU
            ===================================================== */

            const financeToggle =
                document.querySelector('.finance-toggle');

            const financeMenu =
                document.querySelector('.finance-menu');


            if (financeToggle && financeMenu) {

                financeToggle.addEventListener(
                    'click',
                    function () {

                        financeMenu.classList.toggle('open');

                    }
                );

            }

        });

    </script>

</body>

</html>