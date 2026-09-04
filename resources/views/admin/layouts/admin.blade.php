<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin Dashboard') | BMRC Journal
    </title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('favicon.png') }}">

    <link rel="apple-touch-icon"
          href="{{ asset('favicon.png') }}">

    {{-- Bootstrap 5 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')

    <style>

        body {
            background: #f5f7fb;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* =====================================================
           TOP NAVBAR
        ===================================================== */

        .top-navbar {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
        }

        .brand-area {
            width: 250px;
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-right: 1px solid #e5e7eb;
        }

        .brand-area img {
            height: 42px;
            width: 42px;
            object-fit: contain;
        }

        .brand-text {
            margin-left: 10px;
            line-height: 1.1;
        }

        .brand-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #6b7280;
        }

        /* =====================================================
           SIDEBAR
        ===================================================== */

        .sidebar {
            position: fixed;
            top: 64px;
            left: 0;
            bottom: 0;
            width: 250px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            overflow-y: auto;
            padding: 15px 10px;
            z-index: 1045;
        }

        .sidebar-section {
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            padding: 15px 12px 7px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4b5563;
            padding: 10px 12px;
            margin-bottom: 3px;
            border-radius: 8px;
            font-size: 14px;
            transition: .2s;
            text-decoration: none;
        }

        .sidebar .nav-link i {
            width: 20px;
            font-size: 17px;
            flex-shrink: 0;
        }

        .sidebar .nav-link:hover {
            background: #f3f4f6;
            color: #0d6efd;
        }

        .sidebar .nav-link.active {
            background: #eaf2ff;
            color: #0d6efd;
            font-weight: 600;
        }

        .sidebar .arrow {
            margin-left: auto;
            font-size: 12px;
        }

        .submenu {
            padding-left: 18px;
        }

        .submenu .nav-link {
            font-size: 13px;
            padding: 8px 12px;
        }

        /* =====================================================
           BADGES
        ===================================================== */

        .sidebar .badge {
            font-size: 11px;
            min-width: 22px;
            text-align: center;
        }

        /* =====================================================
           MAIN CONTENT
        ===================================================== */

        .main-wrapper {
            margin-left: 250px;
            padding-top: 64px;
            min-height: 100vh;
        }

        .main-content {
            padding: 25px;
        }

        /* =====================================================
           USER AVATAR
        ===================================================== */

        .user-avatar {
            height: 38px;
            width: 38px;
            border-radius: 50%;
            background: #0d6efd;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 600;
        }

        /* =====================================================
           OVERLAY
        ===================================================== */

        .sidebar-overlay {
            display: none;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            padding: 20px 25px;
            color: #6b7280;
            font-size: 13px;
        }

        /* =====================================================
           MOBILE
        ===================================================== */

        @media(max-width:991.98px) {

            .brand-area {
                width: auto;
                border-right: 0;
            }

            .sidebar {
                left: -260px;
                transition: .3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 64px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,.35);
                z-index: 1040;
            }

            .sidebar-overlay.show {
                display: block;
            }

        }

        /* =====================================================
           COLLAPSE MENU
        ===================================================== */

        .collapse:not(.show) {
            display: none;
        }

        .sidebar .collapse {
            visibility: visible !important;
        }

    </style>

</head>


<body>


{{-- =========================================================
   TOP NAVBAR
========================================================= --}}

<nav class="top-navbar">

    <div class="d-flex justify-content-between align-items-center h-100">


        {{-- BRAND --}}

        <div class="brand-area">

            <button
                class="btn btn-light d-lg-none me-2"
                id="sidebarToggle"
                type="button"
            >
                <i class="bi bi-list fs-5"></i>
            </button>


            <img
                src="{{ asset('favicon.png') }}"
                alt="BMRC Logo"
            >


            <div class="brand-text">

                <div class="brand-title">
                    BMRC Journal
                </div>

                <div class="brand-subtitle">
                    Administration Portal
                </div>

            </div>

        </div>


        {{-- USER MENU --}}

        <div class="d-flex align-items-center px-3 gap-3">

            <div class="dropdown">

                <button
                    class="btn d-flex align-items-center gap-2"
                    data-bs-toggle="dropdown"
                    type="button"
                >

                    <div class="user-avatar">

                        {{ strtoupper(
                            substr(
                                auth()->user()->name ?? 'A',
                                0,
                                1
                            )
                        ) }}

                    </div>


                    <div class="d-none d-md-block">

                        <div class="fw-semibold small">
                            {{ auth()->user()->name ?? 'Administrator' }}
                        </div>

                        <div class="text-muted small">
                            Administrator
                        </div>

                    </div>


                    <i class="bi bi-chevron-down"></i>

                </button>


                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a
                            class="dropdown-item"
                            href="#"
                        >
                            <i class="bi bi-person me-2"></i>
                            Profile
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="dropdown-item text-danger"
                            >

                                <i class="bi bi-box-arrow-right me-2"></i>

                                Logout

                            </button>

                        </form>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>


{{-- =========================================================
   SIDEBAR OVERLAY
========================================================= --}}

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
></div>


{{-- =========================================================
   SIDEBAR
========================================================= --}}

<aside
    class="sidebar"
    id="sidebar"
>


    {{-- =====================================================
       MAIN
    ====================================================== --}}

    <div class="sidebar-section">
        Main
    </div>


    <a
        href="{{ route('admin.dashboard') }}"
        class="nav-link
            {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
    >

        <i class="bi bi-speedometer2"></i>

        <span>
            Dashboard
        </span>

    </a>


    {{-- =====================================================
       MANUSCRIPTS
    ====================================================== --}}

    <div class="sidebar-section">
        Manuscripts
    </div>


    <a
        href="{{ route('admin.manuscripts.index') }}"
        class="nav-link
            {{ request()->routeIs('admin.manuscripts.index') ? 'active' : '' }}"
    >

        <i class="bi bi-journal-text"></i>

        <span class="flex-grow-1">
            Manuscripts
        </span>

    </a>


    {{-- TECHNICAL REVIEW --}}

    @can('technical_check.view')

        <a
            href="{{ route('admin.manuscripts.technical-review.index') }}"
            class="nav-link
                {{ request()->routeIs('admin.manuscripts.technical-review.*') ? 'active' : '' }}"
        >

            <i class="bi bi-clipboard-check"></i>

            <span class="flex-grow-1">
                Technical Review
            </span>

        </a>

    @endcan


    {{-- =====================================================
       PAYMENT
    ====================================================== --}}

    <div class="sidebar-section">
        Finance
    </div>


    {{-- PAYMENT SETUP --}}

    @can('payment.view')

        <a
            href="{{ route('admin.manuscripts.payment.index') }}"
            class="nav-link
                {{ request()->routeIs('admin.manuscripts.payment.*') ? 'active' : '' }}"
        >

            <i class="bi bi-credit-card"></i>

            <span class="flex-grow-1">
                Payment Setup
            </span>

        </a>

    @endcan


    {{-- PAYMENT VERIFICATION --}}

    @can('payment.verify')

        <a
            href="{{ route('admin.payments.verification.index') }}"
            class="nav-link
                {{ request()->routeIs('admin.payments.verification.*') ? 'active' : '' }}"
        >

            <i class="bi bi-credit-card-2-front"></i>

            <span class="flex-grow-1">
                Payment Verification
            </span>


            @if(($pendingPaymentVerificationCount ?? 0) > 0)

                <span
                    class="badge bg-danger rounded-pill"
                    title="Pending payment verification"
                >
                    {{ $pendingPaymentVerificationCount }}
                </span>

            @endif

        </a>

    @endcan


    {{-- =====================================================
       EDITORIAL
    ====================================================== --}}

    <div class="sidebar-section">
        Editorial
    </div>


    <a
        href="#"
        class="nav-link"
    >

        <i class="bi bi-person-check"></i>

        <span class="flex-grow-1">
            Editorial Assessment
        </span>

    </a>


    <a
        href="#"
        class="nav-link"
    >

        <i class="bi bi-search"></i>

        <span class="flex-grow-1">
            Similarity Check
        </span>

    </a>


    <a
        href="#"
        class="nav-link"
    >

        <i class="bi bi-people"></i>

        <span class="flex-grow-1">
            Peer Review
        </span>

    </a>


    {{-- =====================================================
       PUBLICATION
    ====================================================== --}}

    <div class="sidebar-section">
        Publication
    </div>


    <a
        href="#"
        class="nav-link"
    >

        <i class="bi bi-pencil-square"></i>

        <span class="flex-grow-1">
            Copy Editing
        </span>

    </a>


    <a
        href="#"
        class="nav-link"
    >

        <i class="bi bi-check2-square"></i>

        <span class="flex-grow-1">
            Proofreading
        </span>

    </a>


    <a
        href="#"
        class="nav-link"
    >

        <i class="bi bi-gear"></i>

        <span class="flex-grow-1">
            Production
        </span>

    </a>


    <a
        href="#"
        class="nav-link"
    >

        <i class="bi bi-book"></i>

        <span class="flex-grow-1">
            Published Articles
        </span>

    </a>


    {{-- =====================================================
       USERS & ACCESS
    ====================================================== --}}

    <div class="sidebar-section">
        Users & Access
    </div>


    @can('user.view')

        <a
            href="#"
            class="nav-link"
        >

            <i class="bi bi-people-fill"></i>

            <span class="flex-grow-1">
                Users
            </span>

        </a>

    @endcan


    <a
        href="#"
        class="nav-link"
    >

        <i class="bi bi-shield-lock"></i>

        <span class="flex-grow-1">
            Roles & Permissions
        </span>

    </a>


    {{-- =====================================================
       SETTINGS
    ====================================================== --}}

    <div class="sidebar-section">
        System
    </div>


    <a
        href="#"
        class="nav-link"
    >

        <i class="bi bi-gear-wide-connected"></i>

        <span class="flex-grow-1">
            Settings
        </span>

    </a>


    {{-- LOGOUT --}}

    <form
        method="POST"
        action="{{ route('logout') }}"
    >

        @csrf

        <button
            type="submit"
            class="nav-link border-0 bg-transparent w-100 text-start"
        >

            <i class="bi bi-box-arrow-right"></i>

            <span>
                Logout
            </span>

        </button>

    </form>


</aside>


{{-- =========================================================
   MAIN CONTENT
========================================================= --}}

<div class="main-wrapper">


    <main class="main-content">


        {{-- SUCCESS MESSAGE --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ERROR MESSAGE --}}

        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- WARNING MESSAGE --}}

        @if(session('warning'))

            <div
                class="alert alert-warning alert-dismissible fade show"
                role="alert"
            >

                <i class="bi bi-exclamation-triangle me-2"></i>

                {{ session('warning') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- VALIDATION ERRORS --}}

        @if($errors->any())

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <strong>
                    Please correct the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @yield('content')


    </main>


    {{-- FOOTER --}}

    <footer class="footer">

        <div class="d-flex justify-content-between flex-wrap">

            <div>
                © {{ date('Y') }}
                Bangladesh Medical Research Council (BMRC)
            </div>

            <div>
                BMRC Journal Administration Portal
            </div>

        </div>

    </footer>


</div>


{{-- =========================================================
   BOOTSTRAP
========================================================= --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | Mobile Sidebar
        |--------------------------------------------------------------------------
        */

        const sidebar =
            document.getElementById('sidebar');

        const toggle =
            document.getElementById('sidebarToggle');

        const overlay =
            document.getElementById('sidebarOverlay');


        if (toggle) {

            toggle.addEventListener(
                'click',
                function () {

                    sidebar.classList.toggle('show');

                    overlay.classList.toggle('show');

                }
            );

        }


        if (overlay) {

            overlay.addEventListener(
                'click',
                function () {

                    sidebar.classList.remove('show');

                    overlay.classList.remove('show');

                }
            );

        }

    }
);

</script>


@stack('scripts')

</body>

</html>