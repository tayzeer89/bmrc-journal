<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'BMRC Journal') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        html, body { height: 100%; }
        body {
            background-color: #f5f6f8;
            font-family: Arial, Helvetica, sans-serif;
        }

        .admin-wrapper {
            min-height: 100vh;
            display: flex;
        }

        .admin-sidebar {
            width: 280px;
            min-width: 280px;
            background: #343a40;
            color: #fff;
            min-height: 100vh;
            transition: all .3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            min-height: 70px;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            background: #2f3439;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-brand h5 {
            margin: 0;
            font-weight: 600;
        }

        .sidebar-menu { padding: 15px 10px 25px; }

        .sidebar-menu-title {
            color: #adb5bd;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .8px;
            padding: 14px 12px 6px;
        }

        .sidebar-menu a {
            color: #dee2e6;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            margin-bottom: 3px;
            border-radius: 6px;
            font-size: 14px;
            transition: .2s;
        }

        .sidebar-menu a i {
            width: 20px;
            min-width: 20px;
            text-align: center;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        .sidebar-menu a.active {
            background: #0d6efd;
            color: #fff;
        }

        .sidebar-menu a.disabled-link {
            opacity: .55;
            cursor: default;
        }

        .admin-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

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

        .admin-content {
            flex: 1;
            padding: 25px;
        }

        .admin-footer {
            background: #fff;
            border-top: 1px solid #dee2e6;
            padding: 15px 25px;
            font-size: 13px;
            color: #6c757d;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                z-index: 1050;
            }

            .admin-sidebar.show { left: 0; }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.45);
                z-index: 1040;
            }

            .sidebar-overlay.show { display: block; }
            .admin-navbar { padding: 0 15px; }
            .admin-content { padding: 15px; }
            .admin-user-details { display: none; }
        }

        @media (max-width: 575.98px) {
            .admin-navbar { height: 60px; }
            .admin-content { padding: 12px; }
            .sidebar-brand { min-height: 60px; }
            .admin-footer { text-align: center; padding: 12px; }
        }
    </style>

    @stack('styles')
</head>

<body>
<div class="admin-wrapper">

    <aside id="adminSidebar" class="admin-sidebar">
        <div class="sidebar-brand">
            <div>
                <h5>BMRC Journal</h5>
                <small class="text-secondary">Administration</small>
            </div>
        </div>

        <div class="sidebar-menu">

            @php
                $currentUser = auth()->user();

                $normalizedRoles = $currentUser
                    ? $currentUser->getRoleNames()->map(
                        fn ($role) => strtolower(str_replace([' ', '-'], '_', trim($role)))
                    )
                    : collect();

                $isAdmin = $normalizedRoles->contains('system_administrator');
                $isEic = $normalizedRoles->contains('editor_in_chief');
                $isHandlingEditor = $normalizedRoles->contains('handling_editor');
                $isEditorialOfficer = $normalizedRoles->contains('editorial_officer');
                $isFinanceOfficer = $normalizedRoles->contains('finance_officer');
                $isCopyEditor = $normalizedRoles->contains('copy_editor');
                $isProofreader = $normalizedRoles->contains('proofreader');
                $isProductionAdmin = $normalizedRoles->contains('production_web_admin');
                $isJournalManager = $normalizedRoles->contains('journal_manager');

                $dashboardUrl = url('/dashboard');

                if ($isAdmin) {
                    $dashboardUrl = url('/admin/dashboard');
                } elseif ($isEic) {
                    $dashboardUrl = url('/editor/dashboard');
                } elseif ($isHandlingEditor) {
                    $dashboardUrl = Route::has('handling-editor.dashboard')
                        ? route('handling-editor.dashboard')
                        : (Route::has('handling-editor.assignments.index')
                            ? route('handling-editor.assignments.index')
                            : url('/dashboard'));
                } elseif ($isEditorialOfficer) {
                    $dashboardUrl = url('/editorial/dashboard');
                } elseif ($isFinanceOfficer) {
                    $dashboardUrl = url('/finance/dashboard');
                } elseif ($isCopyEditor) {
                    $dashboardUrl = url('/copy-editor/dashboard');
                } elseif ($isProofreader) {
                    $dashboardUrl = url('/proofreader/dashboard');
                } elseif ($isProductionAdmin) {
                    $dashboardUrl = url('/production/dashboard');
                } elseif ($isJournalManager) {
                    $dashboardUrl = url('/journal-manager/dashboard');
                }

                $safeRoute = function ($name, $fallback = '#') {
                    return Route::has($name) ? route($name) : $fallback;
                };
            @endphp

            {{-- =========================================================
                 MAIN
            ========================================================== --}}
            <div class="sidebar-menu-title">Main</div>

            <a href="{{ $dashboardUrl }}"
               class="{{ request()->is('admin/dashboard')
                    || request()->is('editor/dashboard')
                    || request()->is('editorial/dashboard')
                    || request()->is('finance/dashboard')
                    || request()->is('copy-editor/dashboard')
                    || request()->is('proofreader/dashboard')
                    || request()->is('production/dashboard')
                    || request()->is('journal-manager/dashboard')
                    || request()->routeIs('handling-editor.dashboard')
                    ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>


            {{-- =========================================================
                 EDITORIAL OFFICER
                 Admin sees all. Editorial Officer sees own workflow.
            ========================================================== --}}
            @if($isAdmin || $isEditorialOfficer)

                <div class="sidebar-menu-title">Editorial Officer</div>

                @if($isAdmin || $currentUser?->can('manuscript.view'))
                    <a href="{{ $safeRoute('admin.manuscripts.index') }}"
                       class="{{ request()->routeIs('admin.manuscripts.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>All Manuscripts</span>
                    </a>
                @endif

                @if($isAdmin || $currentUser?->can('technical_check.view'))
                    <a href="{{ $safeRoute('admin.technical-check.index') }}"
                       class="{{ request()->routeIs('admin.technical-check.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Technical Check</span>
                    </a>

                    <a href="{{ $safeRoute('admin.technical-check.returned') }}"
                       class="{{ request()->routeIs('admin.technical-check.returned') ? 'active' : '' }}">
                        <i class="bi bi-arrow-return-left"></i>
                        <span>Returned to Author</span>
                    </a>
                @endif

                @if($isAdmin || $currentUser?->can('payment.view'))
                    <a href="{{ $safeRoute('admin.payments.index') }}"
                       class="{{ request()->routeIs('admin.payments.index') ? 'active' : '' }}">
                        <i class="bi bi-credit-card"></i>
                        <span>Payment Status</span>
                    </a>
                @endif

                {{-- Similarity Check --}}
                @if($isAdmin || $currentUser?->can('similarity.view'))

                    <a href="{{ $safeRoute('admin.similarity-checks.index') }}"
                    class="{{ request()->routeIs('admin.similarity-checks.*') ? 'active' : '' }}">

                        <i class="bi bi-files"></i>

                        <span>Similarity Check</span>

                    </a>

                @endif

            @endif

            
        {{-- =========================================================
            EDITOR-IN-CHIEF
            System Administrator sees all.
            Editor-in-Chief sees permitted EIC workflow.
        ========================================================== --}}

        @if($isAdmin || $isEic)

            <div class="sidebar-menu-title">
                Editor-in-Chief
            </div>


            {{-- =====================================================
                EDITOR ASSIGNMENT QUEUE
            ====================================================== --}}

            @if($isAdmin || $currentUser?->can('editor.assign'))

                <a
                    href="{{ route('eic.editor-assignment.index') }}"
                    class="{{
                        request()->routeIs('eic.editor-assignment.index')
                        || request()->routeIs('eic.editor-assignment.show')
                            ? 'active'
                            : ''
                    }}">

                    <i class="bi bi-person-plus-fill"></i>

                    <span class="flex-grow-1">
                        Editor Assignment Queue
                    </span>

                    @if(($awaitingEditorAssignmentCount ?? 0) > 0)

                        <span class="badge bg-danger rounded-pill">
                            {{ $awaitingEditorAssignmentCount }}
                        </span>

                    @endif

                </a>

            @endif


            {{-- =====================================================
                ASSIGNMENT TRACKING
                Shows which manuscript is assigned to which editor
            ====================================================== --}}

            @if(
                ($isAdmin || $currentUser?->can('editor.assign'))
                && Route::has('eic.editor-assignment.tracking')
            )

                <a
                    href="{{ route('eic.editor-assignment.tracking') }}"
                    class="{{
                        request()->routeIs('eic.editor-assignment.tracking')
                            ? 'active'
                            : ''
                    }}">

                    <i class="bi bi-diagram-3-fill"></i>

                    <span class="flex-grow-1">
                        Assignment Tracking
                    </span>

                </a>

            @endif


            {{-- =====================================================
                ASSIGNED MANUSCRIPTS
                Only show when route exists
            ====================================================== --}}

            @if(
                ($isAdmin || $currentUser?->can('editor.assign'))
                && Route::has('eic.assigned-manuscripts.index')
            )

                <a
                    href="{{ route('eic.assigned-manuscripts.index') }}"
                    class="{{
                        request()->routeIs('eic.assigned-manuscripts.*')
                            ? 'active'
                            : ''
                    }}">

                    <i class="bi bi-person-check"></i>

                    <span>
                        Assigned Manuscripts
                    </span>

                </a>

            @endif


            {{-- =====================================================
                REASSIGNMENT REQUIRED
                Only show when route exists
            ====================================================== --}}

            @if(
                ($isAdmin || $currentUser?->can('editor.assign'))
                && Route::has('eic.reassignment.index')
            )

                <a
                    href="{{ route('eic.reassignment.index') }}"
                    class="{{
                        request()->routeIs('eic.reassignment.*')
                            ? 'active'
                            : ''
                    }}">

                    <i class="bi bi-arrow-repeat"></i>

                    <span>
                        Reassignment Required
                    </span>

                </a>

            @endif


            {{-- =====================================================
                ASSIGNMENT HISTORY
                Only show when route exists
            ====================================================== --}}

            @if(
                ($isAdmin || $currentUser?->can('editor.assign'))
                && Route::has('eic.assignment-history.index')
            )

                <a
                    href="{{ route('eic.assignment-history.index') }}"
                    class="{{
                        request()->routeIs('eic.assignment-history.*')
                            ? 'active'
                            : ''
                    }}">

                    <i class="bi bi-clock-history"></i>

                    <span>
                        Assignment History
                    </span>

                </a>

            @endif


            {{-- =====================================================
                EDITORIAL RECOMMENDATIONS
            ====================================================== --}}

            @if(
                ($isAdmin || $currentUser?->can('editor.recommend'))
                && Route::has('editor.recommendations.index')
            )

                <a
                    href="{{ route('editor.recommendations.index') }}"
                    class="{{
                        request()->routeIs('editor.recommendations.*')
                            ? 'active'
                            : ''
                    }}">

                    <i class="bi bi-send-check"></i>

                    <span>
                        Editorial Recommendations
                    </span>

                </a>

            @endif


            {{-- =====================================================
                FINAL DECISION QUEUE
            ====================================================== --}}

            @if(
                (
                    $isAdmin
                    || $currentUser?->can('editor.decision')
                    || $currentUser?->can('editor.final_decision')
                )
                && Route::has('editor.decisions.index')
            )

                <a
                    href="{{ route('editor.decisions.index') }}"
                    class="{{
                        request()->routeIs('editor.decisions.*')
                            ? 'active'
                            : ''
                    }}">

                    <i class="bi bi-check2-square"></i>

                    <span>
                        Decision Queue
                    </span>

                </a>

            @endif

        @endif



            {{-- =========================================================
                 HANDLING EDITOR
            ========================================================== --}}
            @if($isAdmin || $isHandlingEditor)

                <div class="sidebar-menu-title">Handling Editor</div>

                <a href="{{ $safeRoute('handling-editor.assignments.index') }}"
                   class="{{ request()->routeIs('handling-editor.assignments.*') ? 'active' : '' }}">
                    <i class="bi bi-inbox"></i>
                    <span>My Assignments</span>
                </a>

                @if($isAdmin || $currentUser?->can('editor.assessment'))
                    <a href="{{ $safeRoute(
                            'handling-editor.assessment.index',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                       class="{{ request()->routeIs('handling-editor.assessment.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Editorial Assessment</span>
                    </a>
                @endif





                {{-- =========================================================
                    PEER REVIEW
                ========================================================== --}}

                <div class="sidebar-menu-title">Peer Review</div>


               {{-- =========================================================
                    REVIEWER SELECTION
                ========================================================== --}}

                @if($isAdmin || $currentUser?->can('reviewer.search'))

                    <a
                        href="{{ $safeRoute(
                            'handling-editor.reviewer-selection.index',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                        class="{{ request()->routeIs(
                            'handling-editor.reviewer-selection.*'
                        ) ? 'active' : '' }}"
                    >

                        <i class="bi bi-people-fill"></i>

                        <span>
                            Reviewer Selection
                        </span>

                    </a>

                @endif

                {{-- =========================================================
                    REVIEWER INVITATIONS
                ========================================================== --}}

                @if($isAdmin || $currentUser?->can('reviewer.invite'))

                    <a href="{{ $safeRoute(
                            'handling-editor.reviewer-invitations.index',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                    class="{{ request()->routeIs('handling-editor.reviewer-invitations.*')
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

                @endif


                {{-- =========================================================
                    ASSIGNED REVIEWERS
                ========================================================== --}}

                @if($isAdmin || $currentUser?->can('reviewer.assign'))

                    <a href="{{ $safeRoute(
                            'handling-editor.assigned-reviewers.index',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                    class="{{ request()->routeIs('handling-editor.assigned-reviewers.*')
                            ? 'active'
                            : '' }}">

                        <i class="bi bi-person-check-fill"></i>

                        <span>
                            Assigned Reviewers
                        </span>

                    </a>

                @endif


                {{-- =========================================================
                    UNDER REVIEW
                ========================================================== --}}

                @if($isAdmin || $currentUser?->can('review.view'))

                    <a href="{{ $safeRoute(
                            'handling-editor.peer-review.index',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                    class="{{ request()->routeIs('handling-editor.peer-review.*')
                            ? 'active'
                            : '' }}">

                        <i class="bi bi-journal-text"></i>

                        <span>
                            Under Review
                        </span>

                    </a>


                    {{-- =====================================================
                        COMPLETED REVIEWS
                    ====================================================== --}}

                    <a href="{{ $safeRoute(
                            'handling-editor.reviews-completed.index',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                    class="{{ request()->routeIs('handling-editor.reviews-completed.*')
                            ? 'active'
                            : '' }}">

                        <i class="bi bi-check2-circle"></i>

                        <span>
                            Completed Reviews
                        </span>

                    </a>

                @endif









                {{-- REVISION --}}
                @if($isAdmin || $currentUser?->can('revision.view'))
                    <div class="sidebar-menu-title">Revision</div>

                    <a href="{{ $safeRoute(
                            'handling-editor.revisions.minor',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                       class="{{ request()->routeIs('handling-editor.revisions.minor*') ? 'active' : '' }}">
                        <i class="bi bi-pencil"></i>
                        <span>Minor Revision</span>
                    </a>

                    <a href="{{ $safeRoute(
                            'handling-editor.revisions.major',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                       class="{{ request()->routeIs('handling-editor.revisions.major*') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square"></i>
                        <span>Major Revision</span>
                    </a>

                    <a href="{{ $safeRoute(
                            'handling-editor.revisions.submitted',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                       class="{{ request()->routeIs('handling-editor.revisions.submitted*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Revision Submitted</span>
                    </a>

                    <a href="{{ $safeRoute(
                            'handling-editor.re-review.index',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                       class="{{ request()->routeIs('handling-editor.re-review.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span>Re-review</span>
                    </a>
                @endif


                {{-- RECOMMENDATION --}}
                @if($isAdmin || $currentUser?->can('editor.recommend'))
                    <div class="sidebar-menu-title">Recommendation</div>

                    <a href="{{ $safeRoute(
                            'handling-editor.recommendation.pending',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                       class="{{ request()->routeIs('handling-editor.recommendation.pending') ? 'active' : '' }}">
                        <i class="bi bi-hourglass-split"></i>
                        <span>Pending Recommendation</span>
                    </a>

                    <a href="{{ $safeRoute(
                            'handling-editor.recommendation.index',
                            $safeRoute('handling-editor.assignments.index')
                        ) }}"
                       class="{{ request()->routeIs('handling-editor.recommendation.index') ? 'active' : '' }}">
                        <i class="bi bi-send-check"></i>
                        <span>Recommendations</span>
                    </a>
                @endif


                {{-- LIMITED REVIEWER DATABASE:
                     show to Handling Editor only.
                     Admin has full Reviewer Management below. --}}
                @if(!$isAdmin)
                    <div class="sidebar-menu-title">Reviewer Database</div>

                    @if($currentUser?->can('reviewer.search'))
                        <a href="{{ $safeRoute('admin.reviewers.search') }}"
                           class="{{ request()->routeIs('admin.reviewers.search') ? 'active' : '' }}">
                            <i class="bi bi-search"></i>
                            <span>Search Reviewer</span>
                        </a>
                    @endif

                    @if($currentUser?->can('reviewer.view'))
                        <a href="{{ $safeRoute('admin.reviewers.approved') }}"
                           class="{{ request()->routeIs('admin.reviewers.approved') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Approved Reviewers</span>
                        </a>
                    @endif

                    @if($currentUser?->can('reviewer.request'))
                        <a href="{{ $safeRoute('admin.reviewers.requests.create') }}"
                           class="{{ request()->routeIs('admin.reviewers.requests.*') ? 'active' : '' }}">
                            <i class="bi bi-person-plus"></i>
                            <span>Request New Reviewer</span>
                        </a>
                    @endif

                    @if($currentUser?->can('reviewer.performance.view'))
                        <a href="{{ $safeRoute('admin.reviewers.workload') }}"
                           class="{{ request()->routeIs('admin.reviewers.workload') ? 'active' : '' }}">
                            <i class="bi bi-clipboard-data"></i>
                            <span>Reviewer Workload</span>
                        </a>

                        <a href="{{ $safeRoute('admin.reviewers.review-history') }}"
                           class="{{ request()->routeIs('admin.reviewers.review-history') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i>
                            <span>Review History</span>
                        </a>
                    @endif
                @endif

            @endif


            {{-- =========================================================
                 USER MANAGEMENT
                 Normally System Administrator.
            ========================================================== --}}
            @if(
                $isAdmin
                || $currentUser?->can('user.view')
                || $currentUser?->can('role.view')
            )
                <div class="sidebar-menu-title">User Management</div>

                @if($isAdmin || $currentUser?->can('user.view'))
                    <a href="{{ $safeRoute('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                @endif

                @if($isAdmin || $currentUser?->can('role.view'))
                    <a href="{{ $safeRoute('admin.roles.index') }}"
                       class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock"></i>
                        <span>Roles & Permissions</span>
                    </a>
                @endif
            @endif


            {{-- =========================================================
                 REVIEWER MANAGEMENT
                 Full administrative reviewer management.
                 Admin and authorized Editorial Officer/Journal Manager.
            ========================================================== --}}
            @if(
                $isAdmin
                || $isEditorialOfficer
                || $isJournalManager
            )

                @if(
                    $isAdmin
                    || $currentUser?->can('reviewer.view')
                    || $currentUser?->can('reviewer.search')
                    || $currentUser?->can('reviewer.request')
                    || $currentUser?->can('reviewer.create')
                    || $currentUser?->can('reviewer.approve')
                    || $currentUser?->can('reviewer.invite')
                    || $currentUser?->can('reviewer.assign')
                    || $currentUser?->can('reviewer.performance.view')
                )

                    <div class="sidebar-menu-title">Reviewer Management</div>

                    @if($isAdmin || $currentUser?->can('reviewer.view'))
                        <a href="{{ $safeRoute('admin.reviewers.index') }}"
                           class="{{ request()->routeIs('admin.reviewers.index')
                                || request()->routeIs('admin.reviewers.show')
                                ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Reviewer Pool</span>
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.search'))
                        <a href="{{ $safeRoute('admin.reviewers.search') }}"
                           class="{{ request()->routeIs('admin.reviewers.search') ? 'active' : '' }}">
                            <i class="bi bi-search"></i>
                            <span>Search Reviewer</span>
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.request'))
                        <a href="{{ $safeRoute('admin.reviewers.requests.create') }}"
                           class="{{ request()->routeIs('admin.reviewers.requests.*') ? 'active' : '' }}">
                            <i class="bi bi-person-plus"></i>
                            <span>Request New Reviewer</span>
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.create'))
                        <a href="{{ $safeRoute('admin.reviewers.create') }}"
                           class="{{ request()->routeIs('admin.reviewers.create') ? 'active' : '' }}">
                            <i class="bi bi-person-plus-fill"></i>
                            <span>Add Reviewer</span>
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.view'))
                        <a href="{{ $safeRoute('admin.reviewers.profile-incomplete') }}"
                           class="{{ request()->routeIs('admin.reviewers.profile-incomplete') ? 'active' : '' }}">
                            <i class="bi bi-person-exclamation"></i>
                            <span class="flex-grow-1">Profile Incomplete</span>
                            @if(($incompleteReviewerCount ?? 0) > 0)
                                <span class="badge bg-warning text-dark rounded-pill">
                                    {{ $incompleteReviewerCount }}
                                </span>
                            @endif
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.approve'))
                        <a href="{{ $safeRoute('admin.reviewers.pending') }}"
                           class="{{ request()->routeIs('admin.reviewers.pending') ? 'active' : '' }}">
                            <i class="bi bi-hourglass-split"></i>
                            <span class="flex-grow-1">Pending Approval</span>
                            @if(($pendingReviewerApprovalCount ?? 0) > 0)
                                <span class="badge bg-danger rounded-pill">
                                    {{ $pendingReviewerApprovalCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ $safeRoute('admin.reviewers.update-requested') }}"
                           class="{{ request()->routeIs('admin.reviewers.update-requested') ? 'active' : '' }}">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Update Requested</span>
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.view'))
                        <a href="{{ $safeRoute('admin.reviewers.approved') }}"
                           class="{{ request()->routeIs('admin.reviewers.approved') ? 'active' : '' }}">
                            <i class="bi bi-person-check-fill"></i>
                            <span>Approved Reviewers</span>
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.reject') || $currentUser?->can('reviewer.approve'))
                        <a href="{{ $safeRoute('admin.reviewers.rejected') }}"
                           class="{{ request()->routeIs('admin.reviewers.rejected') ? 'active' : '' }}">
                            <i class="bi bi-person-x"></i>
                            <span>Rejected Reviewers</span>
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.suspend'))
                        <a href="{{ $safeRoute('admin.reviewers.suspended') }}"
                           class="{{ request()->routeIs('admin.reviewers.suspended') ? 'active' : '' }}">
                            <i class="bi bi-person-dash"></i>
                            <span>Suspended Reviewers</span>
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.invite'))
                        <a href="{{ $safeRoute('admin.reviewer-invitations.index') }}"
                           class="{{ request()->routeIs('admin.reviewer-invitations.*') ? 'active' : '' }}">
                            <i class="bi bi-envelope"></i>
                            <span class="flex-grow-1">Reviewer Invitations</span>
                            @if(($pendingReviewerInvitationCount ?? 0) > 0)
                                <span class="badge bg-primary rounded-pill">
                                    {{ $pendingReviewerInvitationCount }}
                                </span>
                            @endif
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.assign'))
                        <a href="{{ $safeRoute('admin.reviewer-assignments.index') }}"
                           class="{{ request()->routeIs('admin.reviewer-assignments.*') ? 'active' : '' }}">
                            <i class="bi bi-person-check"></i>
                            <span>Assigned Reviewers</span>
                        </a>
                    @endif

                    @if($isAdmin || $currentUser?->can('reviewer.performance.view'))
                        <a href="{{ $safeRoute('admin.reviewers.workload') }}"
                           class="{{ request()->routeIs('admin.reviewers.workload') ? 'active' : '' }}">
                            <i class="bi bi-clipboard-data"></i>
                            <span>Reviewer Workload</span>
                        </a>

                        <a href="{{ $safeRoute('admin.reviewers.review-history') }}"
                           class="{{ request()->routeIs('admin.reviewers.review-history') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i>
                            <span>Review History</span>
                        </a>

                        <a href="{{ $safeRoute('admin.reviewers.performance') }}"
                           class="{{ request()->routeIs('admin.reviewers.performance') ? 'active' : '' }}">
                            <i class="bi bi-bar-chart-line"></i>
                            <span>Reviewer Performance</span>
                        </a>
                    @endif

                @endif
            @endif


            {{-- =========================================================
                 FINANCE
            ========================================================== --}}
            @if($isAdmin || $isFinanceOfficer)

                <div class="sidebar-menu-title">Finance</div>

                @if($isAdmin || $currentUser?->can('payment.verify'))
                    <a href="{{ $safeRoute('admin.payments.verification.index') }}"
                       class="{{ request()->routeIs('admin.payments.verification.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span class="flex-grow-1">Payment Verification</span>
                        @if(($pendingPaymentVerificationCount ?? 0) > 0)
                            <span class="badge bg-danger rounded-pill">
                                {{ $pendingPaymentVerificationCount }}
                            </span>
                        @endif
                    </a>
                @endif

                @if($isAdmin || $currentUser?->can('payment.view'))
                    <a href="{{ $safeRoute('admin.payments.verified') }}"
                       class="{{ request()->routeIs('admin.payments.verified') ? 'active' : '' }}">
                        <i class="bi bi-check-circle"></i>
                        <span>Verified Payments</span>
                    </a>
                @endif

                @if($isAdmin || $currentUser?->can('finance.view'))
                    <a href="{{ $safeRoute('admin.invoices.index') }}"
                       class="{{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i>
                        <span>Invoices</span>
                    </a>
                @endif

                @if($isAdmin || $currentUser?->can('reviewer_payment.view'))
                    <a href="{{ $safeRoute('admin.reviewer-payments.index') }}"
                       class="{{ request()->routeIs('admin.reviewer-payments.*') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack"></i>
                        <span>Reviewer / Editor Payments</span>
                    </a>
                @endif

            @endif


            {{-- =========================================================
                 COPY EDITOR
            ========================================================== --}}
            @if($isAdmin || $isCopyEditor)
                @if($isAdmin || $currentUser?->can('copyediting.view'))
                    <div class="sidebar-menu-title">Copy Editing</div>

                    <a href="{{ $safeRoute('copyediting.index') }}"
                       class="{{ request()->routeIs('copyediting.*') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square"></i>
                        <span>Copy Editing Queue</span>
                    </a>
                @endif
            @endif


            {{-- =========================================================
                 PROOFREADER
            ========================================================== --}}
            @if($isAdmin || $isProofreader)
                @if($isAdmin || $currentUser?->can('proofreading.view'))
                    <div class="sidebar-menu-title">Proofreading</div>

                    <a href="{{ $safeRoute('proofreading.index') }}"
                       class="{{ request()->routeIs('proofreading.*') ? 'active' : '' }}">
                        <i class="bi bi-check2-square"></i>
                        <span>Proofreading Queue</span>
                    </a>
                @endif
            @endif


            {{-- =========================================================
                 PRODUCTION / WEB ADMIN
            ========================================================== --}}
            @if($isAdmin || $isProductionAdmin)

                <div class="sidebar-menu-title">Production</div>

                @if($isAdmin || $currentUser?->can('production.view'))
                    <a href="{{ $safeRoute('production.index') }}"
                       class="{{ request()->routeIs('production.*') ? 'active' : '' }}">
                        <i class="bi bi-printer"></i>
                        <span>Production Queue</span>
                    </a>
                @endif

                @if($isAdmin || $currentUser?->can('issue.view'))
                    <a href="{{ $safeRoute('admin.issues.index') }}"
                       class="{{ request()->routeIs('admin.issues.*') ? 'active' : '' }}">
                        <i class="bi bi-journals"></i>
                        <span>Issues</span>
                    </a>
                @endif

                @if($isAdmin || $currentUser?->can('doi.view'))
                    <a href="{{ $safeRoute('admin.doi.index') }}"
                       class="{{ request()->routeIs('admin.doi.*') ? 'active' : '' }}">
                        <i class="bi bi-link-45deg"></i>
                        <span>DOI Management</span>
                    </a>
                @endif

                <div class="sidebar-menu-title">Journal Website</div>

                <a href="{{ $safeRoute('admin.journal-pages.index') }}"
                   class="{{ request()->routeIs('admin.journal-pages.*') ? 'active' : '' }}">
                    <i class="bi bi-globe2"></i>
                    <span>Website Content</span>
                </a>

            @endif


            {{-- =========================================================
                 JOURNAL MANAGER
            ========================================================== --}}
            @if($isAdmin || $isJournalManager)

                <div class="sidebar-menu-title">Journal Manager</div>

                <a href="{{ $safeRoute('journal-manager.manuscripts.index') }}"
                   class="{{ request()->routeIs('journal-manager.manuscripts.*') ? 'active' : '' }}">
                    <i class="bi bi-kanban"></i>
                    <span>Manuscript Monitoring</span>
                </a>

                <a href="{{ $safeRoute('journal-manager.issues.index') }}"
                   class="{{ request()->routeIs('journal-manager.issues.*') ? 'active' : '' }}">
                    <i class="bi bi-journals"></i>
                    <span>Issue Management</span>
                </a>

                <a href="{{ $safeRoute('journal-manager.production.index') }}"
                   class="{{ request()->routeIs('journal-manager.production.*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>Production Monitoring</span>
                </a>

            @endif


            {{-- =========================================================
                 REPORTS / AUDIT / SETTINGS
                 Admin has unconditional visibility.
            ========================================================== --}}
            @if($isAdmin || $currentUser?->can('report.view'))
                <div class="sidebar-menu-title">Reports</div>

                <a href="{{ $safeRoute('admin.reports.index') }}"
                   class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i>
                    <span>Reports</span>
                </a>
            @endif

            @if($isAdmin || $currentUser?->can('audit.view'))
                <a href="{{ $safeRoute('admin.audit.index') }}"
                   class="{{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Audit Logs</span>
                </a>
            @endif

            @if($isAdmin || $currentUser?->can('settings.view'))
                <div class="sidebar-menu-title">System</div>

                <a href="{{ $safeRoute('admin.settings.index') }}"
                   class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            @endif

        </div>
    </aside>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <main class="admin-main">

        <nav class="admin-navbar">
            <div class="d-flex align-items-center">
                <button type="button"
                        id="sidebarToggle"
                        class="sidebar-toggle d-lg-none me-2">
                    <i class="bi bi-list"></i>
                </button>

                <div>
                    <h6 class="mb-0 fw-semibold">
                        @yield('page_title', 'Dashboard')
                    </h6>
                </div>
            </div>

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

                    <div class="dropdown">
                        <button class="btn btn-sm btn-light"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}">
                                    <i class="bi bi-person me-2"></i>
                                    My Profile
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
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

        <section class="admin-content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Please correct the following:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </section>

        <footer class="admin-footer">
            <div class="d-flex justify-content-between flex-wrap">
                <div>
                    © {{ date('Y') }} {{ config('app.name', 'BMRC Journal') }}
                </div>

                <div>
                    BMRC Journal Management System
                </div>
            </div>
        </footer>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('adminSidebar');
        const toggle = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');

        if (toggle && sidebar && overlay) {
            toggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }

        if (overlay && sidebar) {
            overlay.addEventListener('click', function () {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        document.querySelectorAll('.admin-sidebar a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    if (sidebar) {
                        sidebar.classList.remove('show');
                    }

                    if (overlay) {
                        overlay.classList.remove('show');
                    }
                }
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>
