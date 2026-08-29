<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Reviewer Portal - BMRC Journal')
    </title>

     {{-- BMRC Favicon --}}
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
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body {
            background-color: #f5f7fa;
            font-family:
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;
            color: #212529;
        }

        /* ==============================
           TOP NAVBAR
        ============================== */

        .reviewer-navbar {
            background: #1f2937;
            min-height: 64px;
        }

        .reviewer-brand {
            color: #ffffff !important;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .reviewer-brand small {
            display: block;
            font-size: 11px;
            font-weight: 400;
            opacity: .75;
            letter-spacing: 0;
        }

        .reviewer-navbar .nav-link {
            color: rgba(255,255,255,.85);
            font-size: 14px;
            padding-left: 12px;
            padding-right: 12px;
        }

        .reviewer-navbar .nav-link:hover {
            color: #ffffff;
        }

        /* ==============================
           SIDEBAR
        ============================== */

        .reviewer-sidebar {
            width: 250px;
            position: fixed;
            top: 64px;
            bottom: 0;
            left: 0;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-title {
            padding: 20px 20px 8px;
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .reviewer-sidebar .nav-link {
            margin: 3px 12px;
            padding: 10px 14px;
            border-radius: 7px;
            color: #4b5563;
            font-size: 14px;
            font-weight: 500;
        }

        .reviewer-sidebar .nav-link i {
            width: 24px;
            font-size: 16px;
        }

        .reviewer-sidebar .nav-link:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .reviewer-sidebar .nav-link.active {
            background: #e8f1ff;
            color: #0d6efd;
            font-weight: 600;
        }

        /* ==============================
           MAIN CONTENT
        ============================== */

        .reviewer-main {
            margin-left: 250px;
            padding: 30px;
            min-height: calc(100vh - 64px);
        }

        /* ==============================
           PAGE HEADER
        ============================== */

        .page-header {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 22px 24px;
            margin-bottom: 24px;
        }

        .page-header h1,
        .page-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #6b7280;
            margin-bottom: 0;
            font-size: 14px;
        }

        /* ==============================
           CARDS
        ============================== */

        .reviewer-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,.03);
        }

        .reviewer-card .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
        }

        /* ==============================
           FOOTER
        ============================== */

        .reviewer-footer {
            margin-left: 250px;
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 15px 30px;
            color: #6b7280;
            font-size: 13px;
        }

        /* ==============================
           MOBILE
        ============================== */

        @media (max-width: 991.98px) {

            .reviewer-sidebar {
                position: static;
                width: 100%;
                height: auto;
                border-right: 0;
                border-bottom: 1px solid #e5e7eb;
            }

            .reviewer-main {
                margin-left: 0;
                padding: 20px 15px;
            }

            .reviewer-footer {
                margin-left: 0;
            }

            .sidebar-mobile {
                display: none;
            }

            .sidebar-mobile.show {
                display: block;
            }

        }

        @media (max-width: 575.98px) {

            .reviewer-main {
                padding: 15px 10px;
            }

            .page-header {
                padding: 18px;
            }

            .page-header h1,
            .page-header h2 {
                font-size: 20px;
            }

        }

    </style>

    @stack('styles')

</head>

<body>

{{-- ==========================================================
     TOP NAVBAR
========================================================== --}}

<nav class="navbar reviewer-navbar navbar-dark">

    <div class="container-fluid">

        <a class="navbar-brand reviewer-brand"
           href="{{ route('reviewer.dashboard') }}">

            BMRC Journal

            <small>
                Reviewer Portal
            </small>

        </a>


        {{-- Mobile Menu Button --}}

        <button
            class="navbar-toggler d-lg-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#reviewerNavbar">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div class="d-none d-lg-flex align-items-center">

            @auth

                <span class="text-white small me-3">

                    <i class="bi bi-person-circle me-1"></i>

                    {{ auth()->user()->name }}

                </span>

                <form
                    method="POST"
                    action="{{ route('reviewer.logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-sm btn-outline-light">

                        <i class="bi bi-box-arrow-right me-1"></i>

                        Logout

                    </button>

                </form>

            @endauth

        </div>

    </div>

</nav>


{{-- ==========================================================
     SIDEBAR
========================================================== --}}

<aside class="reviewer-sidebar">

    <div class="sidebar-title">
        Reviewer Workspace
    </div>


    <nav class="nav flex-column">

        {{-- Dashboard --}}

        <a href="{{ route('reviewer.dashboard') }}"
           class="nav-link {{ request()->routeIs('reviewer.dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid-1x2"></i>

            Dashboard

        </a>


        {{-- Invitations --}}

        <div class="sidebar-title">
            Reviews
        </div>


        <a href="#"
           class="nav-link">

            <i class="bi bi-envelope"></i>

            Review Invitations

        </a>


        <a href="#"
           class="nav-link">

            <i class="bi bi-hourglass-split"></i>

            Pending Reviews

        </a>


        <a href="#"
           class="nav-link">

            <i class="bi bi-check2-circle"></i>

            Completed Reviews

        </a>


        <a href="#"
           class="nav-link">

            <i class="bi bi-clock-history"></i>

            Review History

        </a>


        {{-- Manuscripts --}}

        <div class="sidebar-title">
            Manuscripts
        </div>


        <a href="#"
           class="nav-link">

            <i class="bi bi-file-earmark-text"></i>

            Assigned Manuscripts

        </a>


        <a href="#"
           class="nav-link">

            <i class="bi bi-search"></i>

            Review Manuscript

        </a>


        {{-- Payment --}}

        <div class="sidebar-title">
            Finance
        </div>


        <a href="#"
           class="nav-link">

            <i class="bi bi-wallet2"></i>

            Reviewer Payments

        </a>


        <a href="#"
           class="nav-link">

            <i class="bi bi-receipt"></i>

            Payment History

        </a>


        {{-- Profile --}}

        <div class="sidebar-title">
            Account
        </div>


        <a href="#"
           class="nav-link">

            <i class="bi bi-person-vcard"></i>

            Reviewer Profile

        </a>


        <a href="#"
           class="nav-link">

            <i class="bi bi-file-earmark-person"></i>

            Tax & TDS Information

        </a>


        <a href="#"
           class="nav-link">

            <i class="bi bi-shield-lock"></i>

            Account Security

        </a>


        {{-- Logout --}}

        <div class="d-lg-none mt-3 px-3">

            <form
                method="POST"
                action="{{ route('reviewer.logout') }}">

                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-danger w-100">

                    <i class="bi bi-box-arrow-right me-1"></i>

                    Logout

                </button>

            </form>

        </div>

    </nav>

</aside>


{{-- ==========================================================
     MAIN CONTENT
========================================================== --}}

<main class="reviewer-main">

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @yield('content')

</main>


{{-- ==========================================================
     FOOTER
========================================================== --}}

<footer class="reviewer-footer">

    <div class="d-flex flex-wrap justify-content-between">

        <span>
            © {{ date('Y') }} BMRC Journal
        </span>

        <span>
            Reviewer Portal
        </span>

    </div>

</footer>


{{-- Bootstrap JS --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


@stack('scripts')

</body>

</html>