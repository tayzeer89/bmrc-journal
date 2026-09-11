<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Dashboard')
        | {{ config('app.name', 'BMRC Journal') }}
    </title>

    {{-- BMRC Favicon --}}
    <link rel="icon"
        type="image/png"
        href="{{ asset('favicon.png') }}">

    <link rel="apple-touch-icon"
        href="{{ asset('favicon.png') }}">

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <style>

        html,
        body {
            height: 100%;
        }

        body {
            background-color: #f5f6f8;
            font-family: Arial, Helvetica, sans-serif;
        }


        /* =========================================================
           MAIN WRAPPER
        ========================================================= */

        .admin-wrapper {
            min-height: 100vh;
            display: flex;
        }


        /* =========================================================
           SIDEBAR
        ========================================================= */

        .admin-sidebar {
            width: 260px;
            min-width: 260px;
            background: #343a40;
            color: #fff;
            min-height: 100vh;
            transition: all 0.3s ease;
        }


        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            background: #2f3439;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }


        .sidebar-brand h5 {
            margin: 0;
            font-weight: 600;
        }


        .sidebar-menu {
            padding: 15px 10px;
        }


        .sidebar-menu-title {
            color: #adb5bd;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 10px 12px 6px;
        }


        .sidebar-menu a {
            color: #dee2e6;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 12px;
            margin-bottom: 3px;
            border-radius: 6px;
            font-size: 14px;
            transition: 0.2s;
        }


        .sidebar-menu a i {
            width: 20px;
            text-align: center;
        }


        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }


        .sidebar-menu a.active {
            background: #0d6efd;
            color: #fff;
        }


        /* =========================================================
           MAIN CONTENT
        ========================================================= */

        .admin-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }


        /* =========================================================
           TOP NAVBAR
        ========================================================= */

        .admin-navbar {
            height: 70px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }


        .sidebar-toggle {
            border: 0;
            background: transparent;
            font-size: 22px;
            color: #343a40;
        }


        .admin-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }


        .admin-user-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #0d6efd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        .admin-user-name {
            font-size: 14px;
            font-weight: 600;
        }


        .admin-user-role {
            font-size: 12px;
            color: #6c757d;
        }


        /* =========================================================
           CONTENT
        ========================================================= */

        .admin-content {
            flex: 1;
            padding: 25px;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .admin-footer {
            background: #fff;
            border-top: 1px solid #dee2e6;
            padding: 15px 25px;
            font-size: 13px;
            color: #6c757d;
        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 991.98px) {

            .admin-sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                bottom: 0;
                z-index: 1050;
            }


            .admin-sidebar.show {
                left: 0;
            }


            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.45);
                z-index: 1040;
            }


            .sidebar-overlay.show {
                display: block;
            }


            .admin-navbar {
                padding: 0 15px;
            }


            .admin-content {
                padding: 15px;
            }


            .admin-user-details {
                display: none;
            }

        }


        /* =========================================================
           SMALL MOBILE
        ========================================================= */

        @media (max-width: 575.98px) {

            .admin-navbar {
                height: 60px;
            }


            .admin-content {
                padding: 12px;
            }


            .sidebar-brand {
                height: 60px;
            }


            .admin-footer {
                text-align: center;
                padding: 12px;
            }

        }

    </style>


    @stack('styles')

</head>


<body>


<div class="admin-wrapper">



{{-- =========================================================
     SIDEBAR
========================================================= --}}

<aside id="adminSidebar"
       class="admin-sidebar">

    {{-- =====================================================
         BRAND
    ====================================================== --}}

    <div class="sidebar-brand">

        <div>

            <h5>
                BMRC Journal
            </h5>

            <small class="text-secondary">
                Administration
            </small>

        </div>

    </div>


    {{-- =====================================================
         SIDEBAR MENU
    ====================================================== --}}

    <div class="sidebar-menu">


        {{-- =====================================================
             MAIN / DASHBOARD
        ====================================================== --}}

        <div class="sidebar-menu-title">
            Main
        </div>


        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <i class="bi bi-speedometer2"></i>

            <span>
                Dashboard
            </span>

        </a>



        {{-- =====================================================
             USER MANAGEMENT
        ====================================================== --}}

        @canany([
            'user.view',
            'role.view'
        ])

            <div class="sidebar-menu-title">
                User Management
            </div>


            {{-- Users --}}

            @can('user.view')

                <a href="{{ route('admin.users.index') }}"
                   class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                    <i class="bi bi-people"></i>

                    <span>
                        Users
                    </span>

                </a>

            @endcan


            {{-- Roles & Permissions --}}

            @can('role.view')

                <a href="{{ route('admin.roles.index') }}"
                   class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">

                    <i class="bi bi-shield-lock"></i>

                    <span>
                        Roles & Permissions
                    </span>

                </a>

            @endcan

        @endcanany



        {{-- =====================================================
             EDITORIAL
        ====================================================== --}}

        @canany([
            'manuscript.view',
            'editor.assign'
        ])

            <div class="sidebar-menu-title">
                Editorial
            </div>


            {{-- Manuscripts --}}

            @can('manuscript.view')

                <a href="{{ route('admin.manuscripts.index') }}"
                   class="{{ request()->routeIs('admin.manuscripts.*') ? 'active' : '' }}">

                    <i class="bi bi-file-earmark-text"></i>

                    <span>
                        Manuscripts
                    </span>

                </a>

            @endcan


            {{-- Editors --}}

            @can('editor.assign')

                <a href="#">

                    <i class="bi bi-person-workspace"></i>

                    <span>
                        Editors
                    </span>

                </a>

            @endcan

        @endcanany



        {{-- =====================================================
             REVIEWER MANAGEMENT
        ====================================================== --}}

        @canany([
            'reviewer.view',
            'reviewer.search',
            'reviewer.request',
            'reviewer.create',
            'reviewer.edit',
            'reviewer.approve',
            'reviewer.reject',
            'reviewer.suspend',
            'reviewer.invite',
            'reviewer.assign',
            'reviewer.performance.view'
        ])

            <div class="sidebar-menu-title">
                Reviewer Management
            </div>



            {{-- =================================================
                 ALL / APPROVED REVIEWER POOL
            ================================================== --}}

            @can('reviewer.view')

                <a href="{{ route('admin.reviewers.index') }}"
                   class="{{ request()->routeIs('admin.reviewers.index') ||
                             request()->routeIs('admin.reviewers.show')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-people"></i>

                    <span>
                        Reviewer Pool
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 SEARCH REVIEWER
                 Handling / Associate Editor
            ================================================== --}}

            @can('reviewer.search')

                <a href="{{ route('admin.reviewers.search') }}"
                   class="{{ request()->routeIs('admin.reviewers.search')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-search"></i>

                    <span>
                        Search Reviewer
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 REQUEST NEW REVIEWER
                 Handling / Associate Editor
            ================================================== --}}

            @can('reviewer.request')

                <a href="{{ route('admin.reviewers.requests.create') }}"
                   class="{{ request()->routeIs('admin.reviewers.requests.*')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-person-plus"></i>

                    <span>
                        Request New Reviewer
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 ADD REVIEWER
                 Editorial Officer / Journal Officer
            ================================================== --}}

            @can('reviewer.create')

                <a href="{{ route('admin.reviewers.create') }}"
                   class="{{ request()->routeIs('admin.reviewers.create')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-person-plus-fill"></i>

                    <span>
                        Add Reviewer
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 PROFILE INCOMPLETE
            ================================================== --}}

            @can('reviewer.view')

                <a href="{{ route('admin.reviewers.profile-incomplete') }}"
                   class="{{ request()->routeIs('admin.reviewers.profile-incomplete')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-person-exclamation"></i>

                    <span class="flex-grow-1">
                        Profile Incomplete
                    </span>


                    @if(($incompleteReviewerCount ?? 0) > 0)

                        <span class="badge bg-warning text-dark rounded-pill">

                            {{ $incompleteReviewerCount }}

                        </span>

                    @endif

                </a>

            @endcan



            {{-- =================================================
                 PENDING APPROVAL
                 Editor-in-Chief
            ================================================== --}}

            @can('reviewer.approve')

                <a href="{{ route('admin.reviewers.pending') }}"
                   class="{{ request()->routeIs('admin.reviewers.pending')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-hourglass-split"></i>

                    <span class="flex-grow-1">
                        Pending Approval
                    </span>


                    @if(($pendingReviewerApprovalCount ?? 0) > 0)

                        <span class="badge bg-danger rounded-pill">

                            {{ $pendingReviewerApprovalCount }}

                        </span>

                    @endif

                </a>

            @endcan



            {{-- =================================================
                 UPDATE REQUESTED
            ================================================== --}}

            @can('reviewer.approve')

                <a href="{{ route('admin.reviewers.update-requested') }}"
                   class="{{ request()->routeIs('admin.reviewers.update-requested')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-arrow-repeat"></i>

                    <span>
                        Update Requested
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 APPROVED REVIEWERS
            ================================================== --}}

            @can('reviewer.view')

                <a href="{{ route('admin.reviewers.approved') }}"
                   class="{{ request()->routeIs('admin.reviewers.approved')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-person-check-fill"></i>

                    <span>
                        Approved Reviewers
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 REJECTED REVIEWERS
            ================================================== --}}

            @can('reviewer.approve')

                <a href="{{ route('admin.reviewers.rejected') }}"
                   class="{{ request()->routeIs('admin.reviewers.rejected')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-person-x"></i>

                    <span>
                        Rejected Reviewers
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 SUSPENDED REVIEWERS
            ================================================== --}}

            @can('reviewer.suspend')

                <a href="{{ route('admin.reviewers.suspended') }}"
                   class="{{ request()->routeIs('admin.reviewers.suspended')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-person-dash"></i>

                    <span>
                        Suspended Reviewers
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 REVIEWER INVITATIONS
            ================================================== --}}

            @can('reviewer.invite')

                <a href="{{ route('admin.reviewer-invitations.index') }}"
                   class="{{ request()->routeIs('admin.reviewer-invitations.*')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-envelope"></i>

                    <span class="flex-grow-1">
                        Reviewer Invitations
                    </span>


                    @if(($pendingReviewerInvitationCount ?? 0) > 0)

                        <span class="badge bg-primary rounded-pill">

                            {{ $pendingReviewerInvitationCount }}

                        </span>

                    @endif

                </a>

            @endcan



            {{-- =================================================
                 ASSIGNED REVIEWERS
                 Handling / Associate Editor
            ================================================== --}}

            @can('reviewer.assign')

                <a href="{{ route('admin.reviewer-assignments.index') }}"
                   class="{{ request()->routeIs('admin.reviewer-assignments.*')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-person-check"></i>

                    <span>
                        Assigned Reviewers
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 REVIEWER WORKLOAD
            ================================================== --}}

            @can('reviewer.performance.view')

                <a href="{{ route('admin.reviewers.workload') }}"
                   class="{{ request()->routeIs('admin.reviewers.workload')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-clipboard-data"></i>

                    <span>
                        Reviewer Workload
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 REVIEW HISTORY
            ================================================== --}}

            @can('reviewer.performance.view')

                <a href="{{ route('admin.reviewers.review-history') }}"
                   class="{{ request()->routeIs('admin.reviewers.review-history')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-clock-history"></i>

                    <span>
                        Review History
                    </span>

                </a>

            @endcan



            {{-- =================================================
                 REVIEWER PERFORMANCE
            ================================================== --}}

            @can('reviewer.performance.view')

                <a href="{{ route('admin.reviewers.performance') }}"
                   class="{{ request()->routeIs('admin.reviewers.performance')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-bar-chart-line"></i>

                    <span>
                        Reviewer Performance
                    </span>

                </a>

            @endcan

        @endcanany



        {{-- =====================================================
             FINANCE
        ====================================================== --}}

        @canany([
            'payment.verify',
            'payment.view'
        ])

            <div class="sidebar-menu-title">
                Finance
            </div>


            {{-- Payment Verification --}}

            @can('payment.verify')

                <a href="{{ route('admin.payments.verification.index') }}"
                   class="{{ request()->routeIs('admin.payments.verification.*')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-credit-card-2-front"></i>

                    <span class="flex-grow-1">
                        Payment Verification
                    </span>


                    @if(($pendingPaymentVerificationCount ?? 0) > 0)

                        <span class="badge bg-danger rounded-pill"
                              title="Pending payment verification">

                            {{ $pendingPaymentVerificationCount }}

                        </span>

                    @endif

                </a>

            @endcan



            {{-- Verified Payments --}}

            @can('payment.view')

                <a href="{{ route('admin.payments.verified') }}"
                   class="{{ request()->routeIs('admin.payments.verified')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-check-circle"></i>

                    <span>
                        Verified Payments
                    </span>

                </a>

            @endcan

        @endcanany



        {{-- =====================================================
             PUBLICATION
        ====================================================== --}}

        @canany([
            'copyediting.view',
            'proofreading.view',
            'production.view'
        ])

            <div class="sidebar-menu-title">
                Publication
            </div>


            @can('copyediting.view')

                <a href="#">

                    <i class="bi bi-pencil-square"></i>

                    <span>
                        Copy Editing
                    </span>

                </a>

            @endcan


            @can('proofreading.view')

                <a href="#">

                    <i class="bi bi-check2-square"></i>

                    <span>
                        Proofreading
                    </span>

                </a>

            @endcan


            @can('production.view')

                <a href="#">

                    <i class="bi bi-printer"></i>

                    <span>
                        Production
                    </span>

                </a>

            @endcan

        @endcanany



        {{-- =====================================================
             REPORTS
        ====================================================== --}}

        @can('report.view')

            <div class="sidebar-menu-title">
                Reports
            </div>


            <a href="#">

                <i class="bi bi-bar-chart"></i>

                <span>
                    Reports
                </span>

            </a>

        @endcan



        {{-- =====================================================
             AUDIT LOGS
        ====================================================== --}}

        @can('audit.view')

            <a href="#">

                <i class="bi bi-clock-history"></i>

                <span>
                    Audit Logs
                </span>

            </a>

        @endcan



        {{-- =====================================================
             SYSTEM SETTINGS
        ====================================================== --}}

        @can('settings.view')

            <div class="sidebar-menu-title">
                System
            </div>


            <a href="#">

                <i class="bi bi-gear"></i>

                <span>
                    Settings
                </span>

            </a>

        @endcan


    </div>

</aside>


{{-- =========================================================
     SIDEBAR OVERLAY
========================================================= --}}

<div id="sidebarOverlay"
     class="sidebar-overlay">
</div>








































    <div id="sidebarOverlay"
         class="sidebar-overlay">
    </div>


    <!-- =========================================================
         MAIN
    ========================================================== -->

    <main class="admin-main">


        <!-- =====================================================
             NAVBAR
        ====================================================== -->

        <nav class="admin-navbar">


            <div class="d-flex align-items-center">


                <!-- Mobile Toggle -->

                <button
                    type="button"
                    id="sidebarToggle"
                    class="sidebar-toggle d-lg-none me-2">

                    <i class="bi bi-list"></i>

                </button>


                <!-- Page Title -->

                <div>

                    <h6 class="mb-0 fw-semibold">

                        @yield(
                            'page_title',
                            'Dashboard'
                        )

                    </h6>

                </div>

            </div>


            <!-- User -->

            @auth

                <div class="admin-user">


                    <div class="admin-user-icon">

                        <i class="bi bi-person"></i>

                    </div>


                    <div class="admin-user-details">

                        <div class="admin-user-name">

                            {{ auth()->user()->name }}

                        </div>


                        <div class="admin-user-role">

                            {{ auth()->user()->getRoleNames()->first() ?? 'User' }}

                        </div>

                    </div>


                    <!-- User Dropdown -->

                    <div class="dropdown">


                        <button
                            class="btn btn-sm btn-light"
                            type="button"
                            data-bs-toggle="dropdown">

                            <i class="bi bi-chevron-down"></i>

                        </button>


                        <ul class="dropdown-menu dropdown-menu-end">


                            <li>

                                <a
                                    class="dropdown-item"
                                    href="#">

                                    <i class="bi bi-person me-2"></i>

                                    My Profile

                                </a>

                            </li>


                            <li>

                                <hr class="dropdown-divider">

                            </li>


                            <li>

                                <form
                                    method="POST"
                                    action="{{ route('logout') }}">

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

            @endauth


        </nav>


        <!-- =====================================================
             CONTENT
        ====================================================== -->

        <section class="admin-content">


            <!-- Success -->

            @if(session('success'))

                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <!-- Info -->

            @if(session('info'))

                <div
                    class="alert alert-info alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-info-circle me-2"></i>

                    {{ session('info') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <!-- Warning -->

            @if(session('warning'))

                <div
                    class="alert alert-warning alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-exclamation-triangle me-2"></i>

                    {{ session('warning') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <!-- Error -->

            @if(session('error'))

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    <i class="bi bi-x-circle me-2"></i>

                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <!-- Validation Errors -->

            @if($errors->any())

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">


                    <strong>
                        Please correct the following:
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
                        data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            <!-- Page Content -->

            @yield('content')


        </section>


        <!-- =====================================================
             FOOTER
        ====================================================== -->

        <footer class="admin-footer">

            <div class="d-flex justify-content-between flex-wrap">


                <div>

                    © {{ date('Y') }}

                    {{ config('app.name', 'BMRC Journal') }}

                </div>


                <div>

                    BMRC Journal Management System

                </div>


            </div>

        </footer>


    </main>


</div>


<!-- =============================================================
     BOOTSTRAP JS
============================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const sidebar =
                document.getElementById(
                    'adminSidebar'
                );

            const toggle =
                document.getElementById(
                    'sidebarToggle'
                );

            const overlay =
                document.getElementById(
                    'sidebarOverlay'
                );


            if (toggle) {

                toggle.addEventListener(
                    'click',
                    function () {

                        sidebar.classList.toggle(
                            'show'
                        );

                        overlay.classList.toggle(
                            'show'
                        );

                    }
                );

            }


            if (overlay) {

                overlay.addEventListener(
                    'click',
                    function () {

                        sidebar.classList.remove(
                            'show'
                        );

                        overlay.classList.remove(
                            'show'
                        );

                    }
                );

            }


            /* Close sidebar after clicking menu on mobile */

            document
                .querySelectorAll(
                    '.admin-sidebar a'
                )
                .forEach(function (link) {

                    link.addEventListener(
                        'click',
                        function () {

                            if (
                                window.innerWidth < 992
                            ) {

                                sidebar.classList.remove(
                                    'show'
                                );

                                overlay.classList.remove(
                                    'show'
                                );

                            }

                        }
                    );

                });

        }
    );

</script>


@stack('scripts')


</body>

</html>