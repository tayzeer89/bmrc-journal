<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Author Dashboard') | BMRC Journal
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
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f7fb;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* =========================
           TOP NAVBAR
        ========================== */

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
            max-height: 42px;
            max-width: 42px;
            object-fit: contain;
        }

        .brand-text {
            margin-left: 10px;
            line-height: 1.1;
        }

        .brand-title {
            font-weight: 700;
            font-size: 16px;
            color: #1f2937;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #6b7280;
        }


        /* =========================
           SIDEBAR
        ========================== */

        .sidebar {
            position: fixed;
            top: 64px;
            left: 0;
            bottom: 0;
            width: 250px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            overflow-y: auto;
            z-index: 1040;
            padding: 15px 10px;
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
            transition: 0.2s;
        }

        .sidebar .nav-link i {
            width: 20px;
            font-size: 17px;
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

        .sidebar .nav-link .arrow {
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


        /* =========================
           MAIN CONTENT
        ========================== */

        .main-wrapper {
            margin-left: 250px;
            padding-top: 64px;
            min-height: 100vh;
        }

        .main-content {
            padding: 25px;
        }


        /* =========================
           PAGE HEADER
        ========================== */

        .page-header {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }


        /* =========================
           USER PROFILE
        ========================== */

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }


        /* =========================
           SIDEBAR MOBILE
        ========================== */

        @media (max-width: 991.98px) {

            .brand-area {
                width: auto;
                border-right: 0;
            }

            .sidebar {
                left: -250px;
                transition: 0.3s;
            }

            .sidebar.show {
                left: 0;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 64px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.35);
                z-index: 1030;
            }

            .sidebar-overlay.show {
                display: block;
            }

        }


        /* =========================
           CARDS
        ========================== */

        .dashboard-card {
            border: 0;
            border-radius: 12px;
            transition: 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
        }


        /* =========================
           FOOTER
        ========================== */

        .footer {
            padding: 20px 25px;
            color: #6b7280;
            font-size: 13px;
        }

    </style>

    @stack('styles')

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
                id="sidebarToggle">

                <i class="bi bi-list fs-5"></i>

            </button>


            {{-- Replace with actual BMRC logo --}}

            <img
                src="{{ asset('images/bmrc-logo.png') }}"
                alt="BMRC Logo"
            >


            <div class="brand-text">

                <div class="brand-title">
                    BMRC Journal
                </div>

                <div class="brand-subtitle">
                    Author Portal
                </div>

            </div>

        </div>


        {{-- RIGHT NAVIGATION --}}

        <div class="d-flex align-items-center px-3 gap-3">


            {{-- Search --}}

            <div class="d-none d-md-block">

                <div class="input-group">

                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        class="form-control bg-light border-0"
                        placeholder="Search..."
                    >

                </div>

            </div>


            {{-- Notifications --}}

            <a
                href="#"
                class="btn btn-light position-relative">

                <i class="bi bi-bell fs-5"></i>

                @if(isset($unreadNotifications) && $unreadNotifications > 0)

                    <span
                        class="position-absolute top-0 start-100 translate-middle
                               badge rounded-pill bg-danger">

                        {{ $unreadNotifications }}

                    </span>

                @endif

            </a>


            {{-- User --}}

            <div class="dropdown">

                <button
                    class="btn d-flex align-items-center gap-2"
                    data-bs-toggle="dropdown">

                    <div class="user-avatar">

                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}

                    </div>

                    <div class="d-none d-md-block text-start">

                        <div class="fw-semibold small">

                            {{ auth()->user()->name }}

                        </div>

                        <div class="text-muted"
                             style="font-size:11px;">

                            Author

                        </div>

                    </div>

                    <i class="bi bi-chevron-down small"></i>

                </button>


                <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                    <li>

                        <a
                            class="dropdown-item"
                            href="{{ route('author.profile.edit') }}">

                            <i class="bi bi-person me-2"></i>

                            My Profile

                        </a>

                    </li>

                    <li>

                        <a
                            class="dropdown-item"
                            href="#">

                            <i class="bi bi-bell me-2"></i>

                            Notifications

                        </a>

                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <form
                            method="POST"
                            action="{{ route('author.logout') }}">

                            @csrf

                            <button
                                type="submit"
                                class="dropdown-item text-danger">

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
    id="sidebarOverlay">
</div>



{{-- =========================================================
     LEFT SIDEBAR
========================================================= --}}

<aside
    class="sidebar"
    id="sidebar">


    {{-- =========================
         MAIN
    ========================== --}}

    <div class="sidebar-section">
        Main
    </div>


    <a
        href="{{ route('author.dashboard') }}"
        class="nav-link
        {{ request()->routeIs('author.dashboard') ? 'active' : '' }}">

        <i class="bi bi-speedometer2"></i>

        Dashboard

    </a>


    {{-- =========================
         PROFILE
    ========================== --}}

    <div class="sidebar-section">
        Author
    </div>


    <a
        href="{{ route('author.profile.edit') }}"
        class="nav-link
        {{ request()->routeIs('author.profile.*') ? 'active' : '' }}">

        <i class="bi bi-person"></i>

        My Profile

    </a>


    <a
        href="{{ route('author.profile.step2') }}"
        class="nav-link">

        <i class="bi bi-person-vcard"></i>

        Personal Information

    </a>


    <a
        href="{{ route('author.profile.step3') }}"
        class="nav-link">

        <i class="bi bi-building"></i>

        Professional Information

    </a>


    {{-- =========================
         SUBMISSIONS
    ========================== --}}

    <div class="sidebar-section">
        Manuscripts
    </div>


    <a
        href="#submissionMenu"
        class="nav-link"
        data-bs-toggle="collapse">

        <i class="bi bi-journal-text"></i>

        Submissions

        <i class="bi bi-chevron-down arrow"></i>

    </a>


    <div
        class="collapse show submenu"
        id="submissionMenu">


        <a
            href="#"
            class="nav-link">

            <i class="bi bi-plus-circle"></i>

            New Submission

        </a>


        <a
            href="#"
            class="nav-link">

            <i class="bi bi-files"></i>

            My Manuscripts

        </a>


        <a
            href="#"
            class="nav-link">

            <i class="bi bi-file-earmark"></i>

            Drafts

        </a>


        <a
            href="#"
            class="nav-link">

            <i class="bi bi-send"></i>

            Submitted

        </a>


        <a
            href="#"
            class="nav-link">

            <i class="bi bi-search"></i>

            Under Review

        </a>


        <a
            href="#"
            class="nav-link">

            <i class="bi bi-arrow-repeat"></i>

            Revision Required

        </a>


        <a
            href="#"
            class="nav-link">

            <i class="bi bi-check-circle"></i>

            Accepted Articles

        </a>


        <a
            href="#"
            class="nav-link">

            <i class="bi bi-book"></i>

            Published Articles

        </a>

    </div>



    {{-- =========================
         PAYMENTS
    ========================== --}}

    <div class="sidebar-section">
        Finance
    </div>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-credit-card"></i>

        Payments

    </a>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-receipt"></i>

        Invoices

    </a>



    {{-- =========================
         PRODUCTION
    ========================== --}}

    <div class="sidebar-section">
        Publication
    </div>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-file-earmark-pdf"></i>

        Proofs

    </a>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-book-half"></i>

        Published Articles

    </a>



    {{-- =========================
         COMMUNICATION
    ========================== --}}

    <div class="sidebar-section">
        Communication
    </div>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-bell"></i>

        Notifications

        @if(isset($unreadNotifications) && $unreadNotifications > 0)

            <span class="badge bg-danger ms-auto">

                {{ $unreadNotifications }}

            </span>

        @endif

    </a>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-chat-dots"></i>

        Messages

    </a>



    {{-- =========================
         DOCUMENTS
    ========================== --}}

    <div class="sidebar-section">
        Resources
    </div>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-folder"></i>

        Documents

    </a>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-journal-bookmark"></i>

        Submission Guidelines

    </a>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-question-circle"></i>

        Help / Support

    </a>



    {{-- =========================
         ACCOUNT
    ========================== --}}

    <div class="sidebar-section">
        Account
    </div>


    <a
        href="#"
        class="nav-link">

        <i class="bi bi-key"></i>

        Change Password

    </a>


    <form
        method="POST"
        action="{{ route('author.logout') }}">

        @csrf

        <button
            type="submit"
            class="nav-link border-0 bg-transparent w-100 text-start">

            <i class="bi bi-box-arrow-right"></i>

            Logout

        </button>

    </form>


</aside>



{{-- =========================================================
     MAIN WRAPPER
========================================================= --}}

<div class="main-wrapper">


    <main class="main-content">

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

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- PAGE CONTENT --}}

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

                BMRC Journal Author Portal

            </div>

        </div>

    </footer>


</div>



{{-- Bootstrap JS --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

    document.addEventListener('DOMContentLoaded', function () {

        const sidebar = document.getElementById('sidebar');

        const toggle = document.getElementById('sidebarToggle');

        const overlay = document.getElementById('sidebarOverlay');


        if (toggle) {

            toggle.addEventListener('click', function () {

                sidebar.classList.toggle('show');

                overlay.classList.toggle('show');

            });

        }


        if (overlay) {

            overlay.addEventListener('click', function () {

                sidebar.classList.remove('show');

                overlay.classList.remove('show');

            });

        }

    });

</script>


@stack('scripts')

</body>

</html>
