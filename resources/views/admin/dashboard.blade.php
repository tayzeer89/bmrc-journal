@extends('admin.layouts.app')

@section('title', 'System Administrator Dashboard')

@section('content')

<div class="container-fluid py-4">

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<div class="dashboard-header mb-4">

    <div>

        <div class="dashboard-label">
            BMRC JOURNAL MANAGEMENT SYSTEM
        </div>

        <h2 class="dashboard-title">
            System Administrator Dashboard
        </h2>

        <p class="dashboard-subtitle mb-0">
            Welcome, {{ auth()->user()->name }}
        </p>

    </div>

    <div class="dashboard-user">

        <div class="user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div>

            <strong>
                {{ auth()->user()->name }}
            </strong>

            <small>
                {{ auth()->user()->designation ?? 'Journal Administrator' }}
            </small>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- SUMMARY CARDS --}}
{{-- ========================================================= --}}

<div class="row g-4 mb-4">

    {{-- Available Modules --}}

    <div class="col-xl-3 col-md-6">

        <div class="summary-card">

            <div class="summary-icon">
                <i class="bi bi-grid"></i>
            </div>

            <div>

                <span>
                    Available Modules
                </span>

                <strong>
                    {{ $visibleModules->count() }}
                </strong>

            </div>

        </div>

    </div>


    {{-- Role --}}

    <div class="col-xl-3 col-md-6">

        <div class="summary-card">

            <div class="summary-icon">
                <i class="bi bi-person-badge"></i>
            </div>

            <div>

                <span>
                    Current Role
                </span>

                <strong class="text-capitalize">
                    {{ str_replace('_', ' ', auth()->user()->roles->first()?->name ?? 'User') }}
                </strong>

            </div>

        </div>

    </div>


    {{-- Account --}}

    <div class="col-xl-3 col-md-6">

        <div class="summary-card">

            <div class="summary-icon">
                <i class="bi bi-person-check"></i>
            </div>

            <div>

                <span>
                    Account Status
                </span>

                <strong>
                    Active
                </strong>

            </div>

        </div>

    </div>


    {{-- Journal --}}

    <div class="col-xl-3 col-md-6">

        <div class="summary-card">

            <div class="summary-icon">
                <i class="bi bi-journal-medical"></i>
            </div>

            <div>

                <span>
                    Journal
                </span>

                <strong>
                    BMRC
                </strong>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- WORKSPACE --}}
{{-- ========================================================= --}}

<div class="section-heading">

    <div>

        <h4>
            Journal Administration
        </h4>

        <p>
            Access the modules available to your account.
        </p>

    </div>

</div>


{{-- ========================================================= --}}
{{-- PERMISSION BASED MODULES --}}
{{-- ========================================================= --}}

<div class="row g-4">

    @forelse($visibleModules as $key => $module)

        <div class="col-xl-3 col-lg-4 col-md-6">

            <div class="module-card h-100">

                {{-- Icon --}}

                <div class="module-icon">

                    <i class="bi {{ $module['icon'] }}"></i>

                </div>


                {{-- Content --}}

                <div class="module-content">

                    <h5>
                        {{ $module['title'] }}
                    </h5>

                    <p>
                        Access and manage
                        {{ strtolower($module['title']) }}
                        according to your assigned permissions.
                    </p>


                    {{-- Route Exists --}}

                    @if(Route::has($module['route']))

                        <a
                            href="{{ route($module['route']) }}"
                            class="module-button"
                        >

                            Open Module

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    @else

                        <span class="module-coming">

                            Module coming soon

                        </span>

                    @endif

                </div>

            </div>

        </div>

    @empty

        {{-- No Permission --}}

        <div class="col-12">

            <div class="empty-dashboard">

                <div class="empty-icon">

                    <i class="bi bi-lock"></i>

                </div>

                <h5>
                    No Module Access
                </h5>

                <p>
                    Your account does not currently have
                    access to any Journal Management module.
                </p>

                <small>
                    Please contact the System Administrator.
                </small>

            </div>

        </div>

    @endforelse

</div>

</div>

{{-- ============================================================= --}}
{{-- CSS --}}
{{-- ============================================================= --}}

<style>

/* =========================================================
   GENERAL
   ========================================================= */

.dashboard-header {

    background:
        linear-gradient(
            135deg,
            #172033 0%,
            #243b55 100%
        );

    color: #ffffff;

    border-radius: 18px;

    padding: 30px 35px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 20px;

    box-shadow:
        0 10px 30px rgba(23, 32, 51, .15);
}


/* =========================================================
   HEADER
   ========================================================= */

.dashboard-label {

    font-size: 11px;

    letter-spacing: 2px;

    font-weight: 700;

    color: rgba(255,255,255,.65);
}


.dashboard-title {

    margin: 8px 0 5px;

    font-size: 29px;

    font-weight: 700;
}


.dashboard-subtitle {

    color: rgba(255,255,255,.72);
}


/* =========================================================
   USER
   ========================================================= */

.dashboard-user {

    display: flex;

    align-items: center;

    gap: 12px;

    background:
        rgba(255,255,255,.08);

    padding: 10px 15px;

    border-radius: 12px;
}


.dashboard-user strong {

    display: block;

    font-size: 14px;
}


.dashboard-user small {

    display: block;

    margin-top: 3px;

    color:
        rgba(255,255,255,.65);
}


.user-avatar {

    width: 44px;

    height: 44px;

    border-radius: 50%;

    background: #ffffff;

    color: #243b55;

    display: flex;

    align-items: center;

    justify-content: center;

    font-weight: 700;

    font-size: 17px;
}


/* =========================================================
   SUMMARY CARDS
   ========================================================= */

.summary-card {

    background: #ffffff;

    border: 1px solid #e7ebf0;

    border-radius: 15px;

    padding: 20px;

    min-height: 105px;

    display: flex;

    align-items: center;

    gap: 15px;

    transition: all .25s ease;
}


.summary-card:hover {

    transform: translateY(-3px);

    box-shadow:
        0 12px 28px rgba(0,0,0,.07);
}


.summary-icon {

    width: 50px;

    height: 50px;

    border-radius: 13px;

    background: #eef3f8;

    color: #243b55;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 21px;

    flex-shrink: 0;
}


.summary-card span {

    display: block;

    color: #7b8492;

    font-size: 12px;

    margin-bottom: 5px;
}


.summary-card strong {

    display: block;

    color: #172033;

    font-size: 17px;
}


/* =========================================================
   SECTION
   ========================================================= */

.section-heading {

    margin: 30px 0 18px;
}


.section-heading h4 {

    margin: 0;

    color: #172033;

    font-weight: 700;
}


.section-heading p {

    margin: 5px 0 0;

    color: #7b8492;

    font-size: 14px;
}


/* =========================================================
   MODULE CARD
   ========================================================= */

.module-card {

    background: #ffffff;

    border: 1px solid #e7ebf0;

    border-radius: 16px;

    padding: 23px;

    transition:
        transform .25s ease,
        box-shadow .25s ease;

    position: relative;

    overflow: hidden;
}


.module-card::before {

    content: "";

    position: absolute;

    left: 0;

    top: 0;

    width: 4px;

    height: 100%;

    background:
        linear-gradient(
            to bottom,
            #243b55,
            #506b87
        );

    opacity: 0;

    transition: .25s;
}


.module-card:hover {

    transform: translateY(-5px);

    box-shadow:
        0 15px 35px rgba(23,32,51,.09);
}


.module-card:hover::before {

    opacity: 1;
}


/* =========================================================
   MODULE ICON
   ========================================================= */

.module-icon {

    width: 52px;

    height: 52px;

    border-radius: 13px;

    background: #eef3f8;

    color: #243b55;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 22px;

    margin-bottom: 18px;
}


/* =========================================================
   MODULE CONTENT
   ========================================================= */

.module-content h5 {

    color: #172033;

    font-weight: 700;

    margin-bottom: 8px;
}


.module-content p {

    color: #7b8492;

    font-size: 13px;

    line-height: 1.6;

    min-height: 42px;

    margin-bottom: 5px;
}


/* =========================================================
   BUTTON
   ========================================================= */

.module-button {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    margin-top: 10px;

    color: #243b55;

    font-size: 13px;

    font-weight: 700;

    text-decoration: none;

    transition: .2s;
}


.module-button:hover {

    color: #172033;

    gap: 10px;
}


.module-coming {

    display: inline-block;

    margin-top: 12px;

    color: #9aa3af;

    font-size: 12px;
}


/* =========================================================
   EMPTY
   ========================================================= */

.empty-dashboard {

    text-align: center;

    padding: 70px 20px;

    background: #ffffff;

    border: 1px dashed #d8dee7;

    border-radius: 16px;
}


.empty-icon {

    width: 65px;

    height: 65px;

    border-radius: 50%;

    background: #f1f3f6;

    color: #7b8492;

    display: flex;

    align-items: center;

    justify-content: center;

    margin: 0 auto 18px;

    font-size: 27px;
}


.empty-dashboard h5 {

    color: #172033;

    font-weight: 700;

    margin-bottom: 8px;
}


.empty-dashboard p {

    color: #7b8492;

    margin-bottom: 5px;
}


.empty-dashboard small {

    color: #9aa3af;
}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 768px) {

    .dashboard-header {

        padding: 25px;

        flex-direction: column;

        align-items: flex-start;
    }


    .dashboard-title {

        font-size: 23px;
    }


    .dashboard-user {

        width: 100%;
    }

}

</style>

@endsection
