@extends('admin.layouts.app')

@section('title', 'Finance Officer Dashboard')

@section('page_title', 'Finance Officer Dashboard')


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
                Finance Officer Dashboard
            </h2>

            <p class="dashboard-subtitle mb-0">

                Welcome,

                {{ auth()->user()->name }}

            </p>

        </div>


        <div class="dashboard-user">

            <div class="user-avatar">

                {{
                    strtoupper(
                        substr(
                            auth()->user()->name,
                            0,
                            1
                        )
                    )
                }}

            </div>


            <div>

                <strong>

                    {{ auth()->user()->name }}

                </strong>

                <small>

                    {{
                        auth()->user()->designation
                        ?? 'Finance Officer'
                    }}

                </small>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">


        {{-- AVAILABLE MODULES --}}

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



        {{-- ROLE --}}

        <div class="col-xl-3 col-md-6">

            <div class="summary-card">

                <div class="summary-icon">

                    <i class="bi bi-person-badge"></i>

                </div>

                <div>

                    <span>
                        Current Role
                    </span>

                    <strong>
                        Finance Officer
                    </strong>

                </div>

            </div>

        </div>



        {{-- STATUS --}}

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



        {{-- DEPARTMENT --}}

        <div class="col-xl-3 col-md-6">

            <div class="summary-card">

                <div class="summary-icon">

                    <i class="bi bi-cash-stack"></i>

                </div>

                <div>

                    <span>
                        Department
                    </span>

                    <strong>
                        Finance
                    </strong>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- FINANCE WORKSPACE --}}
    {{-- ========================================================= --}}

    <div class="section-heading">

        <h4>
            Finance & Payment Management
        </h4>

        <p>
            Verify article payments and review
            financial records for submitted manuscripts.
        </p>

    </div>



    {{-- ========================================================= --}}
    {{-- MODULES --}}
    {{-- ========================================================= --}}

    <div class="row g-4">

        @forelse($visibleModules as $module)

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="module-card h-100">

                    <div class="module-icon">

                        <i class="bi {{ $module['icon'] }}"></i>

                    </div>


                    <div class="module-content">

                        <h5>

                            {{ $module['title'] }}

                        </h5>


                        <p>

                            @if(
                                $module['title']
                                === 'Payment Verification'
                            )

                                Review and verify article
                                payments submitted by authors.

                            @elseif(
                                $module['title']
                                === 'Verified Payments'
                            )

                                Review successfully verified
                                article payments.

                            @else

                                Access Finance Management
                                functions.

                            @endif

                        </p>


                        @if(
                            !empty($module['route'])
                            &&
                            Route::has($module['route'])
                        )

                            <a
                                href="{{
                                    route(
                                        $module['route']
                                    )
                                }}"
                                class="module-button"
                            >

                                Open Module

                                <i
                                    class="
                                        bi
                                        bi-arrow-right
                                    "
                                ></i>

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


            <div class="col-12">

                <div class="empty-dashboard">

                    <div class="empty-icon">

                        <i class="bi bi-lock"></i>

                    </div>

                    <h5>
                        No Finance Module Access
                    </h5>

                    <p>

                        Your account does not currently
                        have access to a Finance module.

                    </p>

                    <small>

                        Please contact the
                        System Administrator.

                    </small>

                </div>

            </div>

        @endforelse

    </div>


</div>


<style>

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
        0 10px 30px
        rgba(23,32,51,.15);
}


.dashboard-label {

    font-size: 11px;

    letter-spacing: 2px;

    font-weight: 700;

    color:
        rgba(255,255,255,.65);
}


.dashboard-title {

    margin:
        8px 0 5px;

    font-size: 29px;

    font-weight: 700;
}


.dashboard-subtitle {

    color:
        rgba(255,255,255,.72);
}


.dashboard-user {

    display: flex;

    align-items: center;

    gap: 12px;

    background:
        rgba(255,255,255,.08);

    padding:
        10px 15px;

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


.summary-card {

    background: #ffffff;

    border:
        1px solid #e7ebf0;

    border-radius: 15px;

    padding: 20px;

    min-height: 105px;

    display: flex;

    align-items: center;

    gap: 15px;

    transition:
        all .25s ease;
}


.summary-card:hover {

    transform:
        translateY(-3px);

    box-shadow:
        0 12px 28px
        rgba(0,0,0,.07);
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


.section-heading {

    margin:
        30px 0 18px;
}


.section-heading h4 {

    margin: 0;

    color: #172033;

    font-weight: 700;
}


.section-heading p {

    margin:
        5px 0 0;

    color: #7b8492;

    font-size: 14px;
}


.module-card {

    background: #ffffff;

    border:
        1px solid #e7ebf0;

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

    transform:
        translateY(-5px);

    box-shadow:
        0 15px 35px
        rgba(23,32,51,.09);
}


.module-card:hover::before {

    opacity: 1;
}


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


.module-content h5 {

    color: #172033;

    font-weight: 700;

    margin-bottom: 8px;
}


.module-content p {

    color: #7b8492;

    font-size: 13px;

    line-height: 1.6;

    min-height: 63px;

    margin-bottom: 5px;
}


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


.empty-dashboard {

    text-align: center;

    padding:
        70px 20px;

    background: #ffffff;

    border:
        1px dashed #d8dee7;

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

    margin:
        0 auto 18px;

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