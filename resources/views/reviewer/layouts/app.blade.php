<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Reviewer Portal | BMRC Journal')
    </title>


    {{-- Favicon --}}
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('favicon.png') }}"
    >


    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >


    <style>

        :root {

            --reviewer-navbar-height: 64px;

            --reviewer-sidebar-width: 260px;

            --reviewer-primary: #145a86;

            --reviewer-primary-dark: #0d405f;

            --reviewer-bg: #f5f7fa;

            --reviewer-border: #e4e9ef;

            --reviewer-text: #1f2937;

            --reviewer-muted: #6b7280;
        }


        * {
            box-sizing: border-box;
        }


        html,
        body {
            min-height: 100%;
        }


        body {

            margin: 0;

            padding-top: var(--reviewer-navbar-height);

            background: var(--reviewer-bg);

            font-family:
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            color: var(--reviewer-text);
        }


        /*
        |--------------------------------------------------------------------------
        | TOP NAVBAR
        |--------------------------------------------------------------------------
        */

        .reviewer-navbar {

            height: var(--reviewer-navbar-height);

            background:
                linear-gradient(
                    135deg,
                    #173f5f,
                    #102f48
                );

            border-bottom:
                1px solid rgba(255,255,255,.08);

            z-index: 1040;
        }


        .reviewer-navbar .container-fluid {
            height: 100%;
        }


        .reviewer-brand {

            color: #ffffff !important;

            font-size: 16px;

            font-weight: 700;

            line-height: 1.15;

            text-decoration: none;
        }


        .reviewer-brand small {

            display: block;

            margin-top: 2px;

            font-size: 10px;

            font-weight: 400;

            opacity: .75;
        }


        .reviewer-top-link {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            color: rgba(255,255,255,.84);

            text-decoration: none;

            font-size: 13px;

            padding: 7px 10px;

            border-radius: 6px;

            transition: .2s;
        }


        .reviewer-top-link:hover {

            background:
                rgba(255,255,255,.10);

            color: #ffffff;
        }


        .reviewer-user {

            color: #ffffff;

            font-size: 13px;

            white-space: nowrap;
        }


        /*
        |--------------------------------------------------------------------------
        | SIDEBAR
        |--------------------------------------------------------------------------
        */

        .reviewer-sidebar {

            position: fixed;

            top: var(--reviewer-navbar-height);

            left: 0;

            bottom: 0;

            width: var(--reviewer-sidebar-width);

            background: #ffffff;

            border-right:
                1px solid var(--reviewer-border);

            overflow-y: auto;

            overflow-x: hidden;

            z-index: 1030;
        }


        .reviewer-sidebar-inner {

            padding:
                14px 0
                25px;
        }


        .sidebar-title {

            padding:
                14px 20px
                6px;

            color: #98a2b3;

            font-size: 10px;

            font-weight: 700;

            letter-spacing: .08em;

            text-transform: uppercase;
        }


        .reviewer-sidebar .nav-link {

            display: flex;

            align-items: center;

            position: relative;

            gap: 8px;

            margin:
                2px 10px;

            padding:
                10px 12px;

            border-radius: 7px;

            color: #475467;

            font-size: 13px;

            font-weight: 500;

            text-decoration: none;

            transition: all .15s ease;
        }


        .reviewer-sidebar .nav-link i {

            flex:
                0 0
                22px;

            width: 22px;

            font-size: 15px;

            text-align: center;
        }


        .reviewer-sidebar .nav-link:hover {

            background: #f2f6fa;

            color:
                var(--reviewer-primary);
        }


        .reviewer-sidebar .nav-link.active {

            background: #eaf3f9;

            color:
                var(--reviewer-primary);

            font-weight: 600;
        }


        .reviewer-sidebar .nav-link.active::before {

            content: "";

            position: absolute;

            left: -10px;

            top: 5px;

            bottom: 5px;

            width: 3px;

            background:
                var(--reviewer-primary);

            border-radius:
                0 3px 3px 0;
        }


        .reviewer-sidebar .nav-link.disabled-link {

            color: #a7afb8;

            cursor: not-allowed;

            background: transparent;
        }


        .reviewer-sidebar .nav-link.disabled-link:hover {

            color: #a7afb8;

            background: transparent;
        }


        .sidebar-lock {

            margin-left: auto;

            font-size: 10px !important;

            color: #adb5bd;
        }


        /*
        |--------------------------------------------------------------------------
        | SIDEBAR USER CARD
        |--------------------------------------------------------------------------
        */

        .sidebar-user-card {

            margin:
                4px 12px
                10px;

            padding: 12px;

            background: #f8fafc;

            border:
                1px solid #e5eaf0;

            border-radius: 8px;
        }


        .sidebar-user-name {

            color: #173f5f;

            font-size: 13px;

            font-weight: 700;
        }


        .sidebar-user-email {

            color: #7c8794;

            font-size: 10px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;
        }


        /*
        |--------------------------------------------------------------------------
        | MAIN CONTENT
        |--------------------------------------------------------------------------
        */

        .reviewer-main {

            width:
                calc(
                    100% -
                    var(--reviewer-sidebar-width)
                );

            margin-left:
                var(--reviewer-sidebar-width);

            min-height:
                calc(
                    100vh -
                    var(--reviewer-navbar-height) -
                    54px
                );

            padding:
                24px
                28px;
        }


        .reviewer-content-container {

            width: 100%;

            max-width: 1440px;

            margin:
                0 auto;
        }


        /*
        |--------------------------------------------------------------------------
        | PAGE HEADER
        |--------------------------------------------------------------------------
        */

        .page-header {

            margin-bottom: 20px;

            padding:
                20px 22px;

            background: #ffffff;

            border:
                1px solid var(--reviewer-border);

            border-radius: 10px;

            box-shadow:
                0 1px 2px
                rgba(16,24,40,.03);
        }


        .page-header h1,
        .page-header h2 {

            margin-bottom: 4px;

            color: #173f5f;

            font-size: 22px;

            font-weight: 700;
        }


        .page-header p {

            margin-bottom: 0;

            color:
                var(--reviewer-muted);

            font-size: 13px;
        }


        /*
        |--------------------------------------------------------------------------
        | CARDS
        |--------------------------------------------------------------------------
        */

        .reviewer-card {

            background: #ffffff;

            border:
                1px solid var(--reviewer-border);

            border-radius: 10px;

            box-shadow:
                0 1px 2px
                rgba(16,24,40,.03);

            overflow: hidden;
        }


        .reviewer-card .card-header {

            padding:
                14px 18px;

            background: #ffffff;

            border-bottom:
                1px solid var(--reviewer-border);

            color: #344054;

            font-size: 13px;

            font-weight: 600;
        }


        .reviewer-card .card-body {

            padding:
                18px;
        }


        /*
        |--------------------------------------------------------------------------
        | FORM
        |--------------------------------------------------------------------------
        */

        .form-label {

            color: #344054;

            font-size: 12px;

            font-weight: 600;
        }


        .form-control,
        .form-select {

            border-color: #d0d7de;

            border-radius: 6px;

            font-size: 13px;
        }


        .form-control:focus,
        .form-select:focus {

            border-color:
                var(--reviewer-primary);

            box-shadow:
                0 0 0 .2rem
                rgba(20,90,134,.10);
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .reviewer-footer {

            width:
                calc(
                    100% -
                    var(--reviewer-sidebar-width)
                );

            margin-left:
                var(--reviewer-sidebar-width);

            padding:
                14px 28px;

            background: #ffffff;

            border-top:
                1px solid var(--reviewer-border);

            color:
                var(--reviewer-muted);

            font-size: 11px;
        }


        .reviewer-footer-inner {

            width: 100%;

            max-width: 1440px;

            margin:
                0 auto;
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE SIDEBAR OVERLAY
        |--------------------------------------------------------------------------
        */

        .reviewer-sidebar-overlay {

            display: none;

            position: fixed;

            top:
                var(--reviewer-navbar-height);

            right: 0;

            bottom: 0;

            left: 0;

            background:
                rgba(15,23,42,.45);

            z-index: 1025;
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media
        (max-width: 991.98px) {

            .reviewer-sidebar {

                transform:
                    translateX(-100%);

                transition:
                    transform .25s ease;

                box-shadow:
                    4px 0 18px
                    rgba(0,0,0,.08);
            }


            body.sidebar-open
            .reviewer-sidebar {

                transform:
                    translateX(0);
            }


            body.sidebar-open
            .reviewer-sidebar-overlay {

                display: block;
            }


            .reviewer-main {

                width: 100%;

                margin-left: 0;

                padding:
                    20px 15px;
            }


            .reviewer-footer {

                width: 100%;

                margin-left: 0;

                padding:
                    14px 15px;
            }

        }


        @media
        (max-width: 575.98px) {

            .reviewer-main {

                padding:
                    15px 10px;
            }


            .page-header {

                padding:
                    16px;
            }


            .page-header h1,
            .page-header h2 {

                font-size: 19px;
            }


            .reviewer-navbar {

                padding-left: 4px;

                padding-right: 4px;
            }

        }

    </style>


    @stack('styles')

</head>


<body>


@php

    $sidebarReviewer =
        Auth::guard('reviewer')->user();

    if ($sidebarReviewer) {
        $sidebarReviewer->loadMissing('profile');
    }

    $sidebarProfile =
        $sidebarReviewer?->profile;

    /*
    |--------------------------------------------------------------------------
    | Reviewer Approval
    |--------------------------------------------------------------------------
    |
    | BOTH records must be approved:
    |
    | reviewers.status = approved
    | reviewer_profiles.approval_status = approved
    |
    */

    $reviewerAccountApproved =
        $sidebarReviewer
        && $sidebarReviewer->status === 'approved';

    $reviewerProfileApproved =
        $sidebarProfile
        && $sidebarProfile->approval_status === 'approved';

    $reviewerApproved =
        $reviewerAccountApproved
        && $reviewerProfileApproved;

@endphp



{{-- ================================================================
    TOP NAVBAR
================================================================ --}}

<nav
    class="navbar reviewer-navbar navbar-dark fixed-top"
>

    <div class="container-fluid px-lg-4">


        {{-- Brand --}}

        <a
            class="navbar-brand reviewer-brand"
            href="{{ route('reviewer.dashboard') }}"
        >

            BMRC Journal

            <small>
                Reviewer Portal
            </small>

        </a>



        {{-- Mobile Menu --}}

        <button
            type="button"
            id="reviewerSidebarToggle"
            class="navbar-toggler d-lg-none border-0"
            aria-label="Open navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>



        {{-- Desktop Navigation --}}

        <div
            class="
                d-none
                d-lg-flex
                align-items-center
                ms-auto
                gap-1
            "
        >


            <a
                href="{{ route('reviewer.dashboard') }}"
                class="reviewer-top-link"
            >

                <i class="bi bi-grid"></i>

                Dashboard

            </a>


            @if(Route::has('reviewer.profile.show'))

                <a
                    href="{{ route('reviewer.profile.show') }}"
                    class="reviewer-top-link"
                >

                    <i class="bi bi-person"></i>

                    My Profile

                </a>

            @endif


            @if(Route::has('reviewer.application.status'))

                <a
                    href="{{ route('reviewer.application.status') }}"
                    class="reviewer-top-link"
                >

                    <i class="bi bi-clipboard-check"></i>

                    Application Status

                </a>

            @endif



            @auth('reviewer')

                <div
                    class="
                        vr
                        bg-light
                        opacity-25
                        mx-2
                    "
                ></div>


                <span class="reviewer-user me-2">

                    <i class="bi bi-person-circle me-1"></i>

                    {{
                        Auth::guard('reviewer')
                            ->user()
                            ->name
                    }}

                </span>


                <form
                    method="POST"
                    action="{{ route('reviewer.logout') }}"
                    class="m-0"
                >

                    @csrf

                    <button
                        type="submit"
                        class="
                            btn
                            btn-sm
                            btn-outline-light
                        "
                    >

                        <i class="bi bi-box-arrow-right me-1"></i>

                        Logout

                    </button>

                </form>

            @endauth

        </div>

    </div>

</nav>



{{-- ================================================================
    MOBILE OVERLAY
================================================================ --}}

<div
    id="reviewerSidebarOverlay"
    class="reviewer-sidebar-overlay"
></div>



{{-- ================================================================
    SIDEBAR
================================================================ --}}

<aside
    id="reviewerSidebar"
    class="reviewer-sidebar"
>

    <div class="reviewer-sidebar-inner">


        @auth('reviewer')

            <div class="sidebar-user-card">

                <div class="sidebar-user-name">

                    <i class="bi bi-person-circle me-1"></i>

                    {{
                        Auth::guard('reviewer')
                            ->user()
                            ->name
                    }}

                </div>


                <div class="sidebar-user-email">

                    {{
                        Auth::guard('reviewer')
                            ->user()
                            ->email
                    }}

                </div>

                    @if($sidebarProfile)

                        <div class="mt-2">

                            @if($reviewerApproved)

                                <span
                                    class="
                                        badge
                                        bg-success-subtle
                                        text-success
                                        border
                                    "
                                >
                                    <i class="bi bi-check-circle me-1"></i>
                                    Approved Reviewer
                                </span>

                            @elseif(
                                $sidebarReviewer?->status === 'rejected'
                                ||
                                $sidebarProfile?->approval_status === 'rejected'
                            )

                                <span
                                    class="
                                        badge
                                        bg-danger-subtle
                                        text-danger
                                        border
                                    "
                                >
                                    Not Approved
                                </span>

                            @elseif(
                                $sidebarProfile?->approval_status === 'update_requested'
                            )

                                <span
                                    class="
                                        badge
                                        bg-warning-subtle
                                        text-warning-emphasis
                                        border
                                    "
                                >
                                    Update Requested
                                </span>

                            @elseif(
                                $sidebarProfile?->approval_status === 'pending_approval'
                            )

                                <span
                                    class="
                                        badge
                                        bg-info-subtle
                                        text-info-emphasis
                                        border
                                    "
                                >
                                    Pending Approval
                                </span>

                            @else

                                <span
                                    class="
                                        badge
                                        bg-secondary-subtle
                                        text-secondary
                                        border
                                    "
                                >
                                    Draft Profile
                                </span>

                            @endif

                        </div>


                        {{-- Development/debug information --}}
                        <div class="mt-2 small text-muted">

                            Account:
                            <strong>
                                {{ $sidebarReviewer?->status ?? '-' }}
                            </strong>

                            |

                            Profile:
                            <strong>
                                {{ $sidebarProfile?->approval_status ?? '-' }}
                            </strong>

                        </div>

                    @endif

            </div>

        @endauth



        {{-- ============================================================
            MAIN
        ============================================================ --}}

        <div class="sidebar-title">
            Main
        </div>


        <nav class="nav flex-column">


            <a
                href="{{ route('reviewer.dashboard') }}"
                class="
                    nav-link
                    {{
                        request()->routeIs(
                            'reviewer.dashboard'
                        )
                            ? 'active'
                            : ''
                    }}
                "
            >

                <i class="bi bi-grid-1x2"></i>

                Dashboard

            </a>



            {{-- ========================================================
                PROFILE
            ======================================================== --}}

            <div class="sidebar-title">
                My Profile
            </div>


            @if(Route::has('reviewer.profile.show'))

                <a
                    href="{{ route('reviewer.profile.show') }}"
                    class="
                        nav-link
                        {{
                            request()->routeIs(
                                'reviewer.profile.show'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-person-vcard"></i>

                    View Profile

                </a>

            @endif



            @if(Route::has('reviewer.application.edit'))

                <a
                    href="{{ route('reviewer.application.edit') }}"
                    class="
                        nav-link
                        {{
                            request()->routeIs(
                                'reviewer.application.edit'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-pencil-square"></i>

                    Update Information

                </a>

            @endif



            @if(Route::has('reviewer.application.status'))

                <a
                    href="{{ route('reviewer.application.status') }}"
                    class="
                        nav-link
                        {{
                            request()->routeIs(
                                'reviewer.application.status'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-clipboard-check"></i>

                    Application Status

                </a>

            @endif



            {{-- ========================================================
                PEER REVIEW
            ======================================================== --}}

            <div class="sidebar-title">
                Peer Review
            </div>


            @if($reviewerApproved)

                @if(Route::has('reviewer.invitations.index'))

                    <a
                        href="{{ route('reviewer.invitations.index') }}"
                        class="
                            nav-link
                            {{
                                request()->routeIs(
                                    'reviewer.invitations.*'
                                )
                                    ? 'active'
                                    : ''
                            }}
                        "
                    >

                        <i class="bi bi-envelope"></i>

                        Review Invitations

                    </a>

                @else

                    <span
                        class="
                            nav-link
                            disabled-link
                        "
                    >

                        <i class="bi bi-envelope"></i>

                        Review Invitations

                        <i
                            class="
                                bi
                                bi-tools
                                sidebar-lock
                            "
                        ></i>

                    </span>

                @endif


                @if(Route::has('reviewer.reviews.active'))

                    <a
                        href="{{ route('reviewer.reviews.active') }}"
                        class="
                            nav-link
                            {{
                                request()->routeIs(
                                    'reviewer.reviews.active'
                                )
                                    ? 'active'
                                    : ''
                            }}
                        "
                    >

                        <i class="bi bi-hourglass-split"></i>

                        Active Reviews

                    </a>

                @else

                    <span class="nav-link disabled-link">

                        <i class="bi bi-hourglass-split"></i>

                        Active Reviews

                        <i
                            class="
                                bi
                                bi-tools
                                sidebar-lock
                            "
                        ></i>

                    </span>

                @endif


                @if(Route::has('reviewer.reviews.completed'))

                    <a
                        href="{{ route('reviewer.reviews.completed') }}"
                        class="
                            nav-link
                            {{
                                request()->routeIs(
                                    'reviewer.reviews.completed'
                                )
                                    ? 'active'
                                    : ''
                            }}
                        "
                    >

                        <i class="bi bi-check2-circle"></i>

                        Completed Reviews

                    </a>

                @else

                    <span class="nav-link disabled-link">

                        <i class="bi bi-check2-circle"></i>

                        Completed Reviews

                        <i
                            class="
                                bi
                                bi-tools
                                sidebar-lock
                            "
                        ></i>

                    </span>

                @endif


                @if(Route::has('reviewer.reviews.history'))

                    <a
                        href="{{ route('reviewer.reviews.history') }}"
                        class="
                            nav-link
                            {{
                                request()->routeIs(
                                    'reviewer.reviews.history'
                                )
                                    ? 'active'
                                    : ''
                            }}
                        "
                    >

                        <i class="bi bi-clock-history"></i>

                        Review History

                    </a>

                @else

                    <span class="nav-link disabled-link">

                        <i class="bi bi-clock-history"></i>

                        Review History

                        <i
                            class="
                                bi
                                bi-tools
                                sidebar-lock
                            "
                        ></i>

                    </span>

                @endif

            @else

                <span
                    class="
                        nav-link
                        disabled-link
                    "
                    title="
                        Available after reviewer approval
                    "
                >

                    <i class="bi bi-envelope"></i>

                    Review Invitations

                    <i
                        class="
                            bi
                            bi-lock
                            sidebar-lock
                        "
                    ></i>

                </span>


                <span class="nav-link disabled-link">

                    <i class="bi bi-hourglass-split"></i>

                    Active Reviews

                    <i
                        class="
                            bi
                            bi-lock
                            sidebar-lock
                        "
                    ></i>

                </span>


                <span class="nav-link disabled-link">

                    <i class="bi bi-check2-circle"></i>

                    Completed Reviews

                    <i
                        class="
                            bi
                            bi-lock
                            sidebar-lock
                        "
                    ></i>

                </span>

            @endif



            {{-- ========================================================
                FINANCE
            ======================================================== --}}

            <div class="sidebar-title">
                Finance
            </div>


            @if(
                $reviewerApproved
                &&
                Route::has(
                    'reviewer.payments.index'
                )
            )

                <a
                    href="{{ route('reviewer.payments.index') }}"
                    class="
                        nav-link
                        {{
                            request()->routeIs(
                                'reviewer.payments.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-wallet2"></i>

                    Reviewer Payments

                </a>

            @else

                <span class="nav-link disabled-link">

                    <i class="bi bi-wallet2"></i>

                    Reviewer Payments

                    <i
                        class="
                            bi
                            {{
                                $reviewerApproved
                                    ? 'bi-tools'
                                    : 'bi-lock'
                            }}
                            sidebar-lock
                        "
                    ></i>

                </span>

            @endif



            {{-- ========================================================
                ACCOUNT
            ======================================================== --}}

            <div class="sidebar-title">
                Account
            </div>


            @if(Route::has('reviewer.password.change'))

                <a
                    href="{{ route('reviewer.password.change') }}"
                    class="
                        nav-link
                        {{
                            request()->routeIs(
                                'reviewer.password.*'
                            )
                                ? 'active'
                                : ''
                        }}
                    "
                >

                    <i class="bi bi-key"></i>

                    Change Password

                </a>

            @endif



            {{-- Mobile Logout --}}

            <div class="d-lg-none mt-3 px-2">

                <form
                    method="POST"
                    action="{{ route('reviewer.logout') }}"
                >

                    @csrf


                    <button
                        type="submit"
                        class="
                            btn
                            btn-outline-danger
                            btn-sm
                            w-100
                        "
                    >

                        <i class="bi bi-box-arrow-right me-1"></i>

                        Logout

                    </button>

                </form>

            </div>


        </nav>

    </div>

</aside>



{{-- ================================================================
    MAIN CONTENT
================================================================ --}}

<main class="reviewer-main">

    <div class="reviewer-content-container">


        {{-- Success --}}

        @if(session('success'))

            <div
                class="
                    alert
                    alert-success
                    alert-dismissible
                    fade
                    show
                "
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



        {{-- Error --}}

        @if(session('error'))

            <div
                class="
                    alert
                    alert-danger
                    alert-dismissible
                    fade
                    show
                "
            >

                <i class="bi bi-exclamation-triangle me-2"></i>

                {{ session('error') }}


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif



        {{-- Warning --}}

        @if(session('warning'))

            <div
                class="
                    alert
                    alert-warning
                    alert-dismissible
                    fade
                    show
                "
            >

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('warning') }}


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif



        {{-- Validation Errors --}}

        @if($errors->any())

            <div
                class="
                    alert
                    alert-danger
                    alert-dismissible
                    fade
                    show
                "
            >

                <div class="fw-semibold mb-1">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Please correct the following:

                </div>


                <ul class="mb-0 ps-3">

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


    </div>

</main>



{{-- ================================================================
    FOOTER
================================================================ --}}

<footer class="reviewer-footer">

    <div
        class="
            reviewer-footer-inner
            d-flex
            flex-wrap
            justify-content-between
            gap-2
        "
    >

        <span>
            © {{ date('Y') }}
            Bangladesh Medical Research Council
        </span>

        <span>
            BMRC Journal Reviewer Portal
        </span>

    </div>

</footer>



{{-- Bootstrap --}}
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>



{{-- ================================================================
    MOBILE SIDEBAR
================================================================ --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const toggle =
            document.getElementById(
                'reviewerSidebarToggle'
            );

        const overlay =
            document.getElementById(
                'reviewerSidebarOverlay'
            );


        function openSidebar() {

            document.body
                .classList
                .add('sidebar-open');
        }


        function closeSidebar() {

            document.body
                .classList
                .remove('sidebar-open');
        }


        if (toggle) {

            toggle.addEventListener(
                'click',
                function () {

                    document.body
                        .classList
                        .toggle(
                            'sidebar-open'
                        );
                }
            );
        }


        if (overlay) {

            overlay.addEventListener(
                'click',
                closeSidebar
            );
        }


        document
            .querySelectorAll(
                '.reviewer-sidebar a.nav-link'
            )
            .forEach(
                function (link) {

                    link.addEventListener(
                        'click',
                        closeSidebar
                    );
                }
            );


        window.addEventListener(
            'resize',
            function () {

                if (
                    window.innerWidth >= 992
                ) {

                    closeSidebar();
                }
            }
        );

    }
);

</script>


@stack('scripts')


</body>

</html>