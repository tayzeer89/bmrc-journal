<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReviewerAccountCreated;
use App\Models\Reviewer;
use App\Models\ReviewerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ReviewerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REVIEWER LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Reviewer::query()
            ->with([
                'profile',
                'creator',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($query) use ($search) {

                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($profileQuery) use ($search) {

                        $profileQuery
                            ->where('reviewer_code', 'like', "%{$search}%")
                            ->orWhere('application_id', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('display_name', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%")
                            ->orWhere('institution', 'like', "%{$search}%")
                            ->orWhere('department', 'like', "%{$search}%")
                            ->orWhere('designation', 'like', "%{$search}%")
                            ->orWhere('speciality', 'like', "%{$search}%")
                            ->orWhere('sub_speciality', 'like', "%{$search}%")
                            ->orWhere('specialization', 'like', "%{$search}%")
                            ->orWhere('research_interests', 'like', "%{$search}%")
                            ->orWhere('primary_expertise', 'like', "%{$search}%")
                            ->orWhere('areas_of_expertise', 'like', "%{$search}%")
                            ->orWhere('expertise_keywords', 'like', "%{$search}%")
                            ->orWhere('orcid', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Account Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status')
            && in_array(
                $request->status,
                [
                    'pending',
                    'approved',
                    'rejected',
                    'suspended',
                ],
                true
            )
        ) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Profile Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('approval_status')
            && in_array(
                $request->approval_status,
                [
                    'draft',
                    'pending_approval',
                    'update_requested',
                    'approved',
                    'rejected',
                ],
                true
            )
        ) {
            $query->whereHas(
                'profile',
                fn ($q) =>
                    $q->where(
                        'approval_status',
                        $request->approval_status
                    )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Profile Completion Filter
        |--------------------------------------------------------------------------
        */

        if ($request->profile === 'complete') {

            $query->whereHas(
                'profile',
                fn ($q) =>
                    $q->where(
                        'profile_completed',
                        true
                    )
            );
        }

        if ($request->profile === 'incomplete') {

            $query->where(function ($q) {

                $q->whereDoesntHave('profile')
                    ->orWhereHas(
                        'profile',
                        fn ($profileQuery) =>
                            $profileQuery->where(
                                'profile_completed',
                                false
                            )
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [
            'total' => Reviewer::count(),

            'pending' => Reviewer::where(
                'status',
                'pending'
            )->count(),

            'approved' => Reviewer::where(
                'status',
                'approved'
            )->count(),

            'rejected' => Reviewer::where(
                'status',
                'rejected'
            )->count(),

            'suspended' => Reviewer::where(
                'status',
                'suspended'
            )->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Sort
        |--------------------------------------------------------------------------
        */

        switch ($request->input('sort')) {

            case 'oldest':

                $query->oldest();

                break;

            case 'name_asc':

                $query->orderBy(
                    'name',
                    'asc'
                );

                break;

            case 'name_desc':

                $query->orderBy(
                    'name',
                    'desc'
                );

                break;

            default:

                $query->latest();

                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = (int) $request->input(
            'per_page',
            20
        );

        if (
            !in_array(
                $perPage,
                [
                    10,
                    20,
                    50,
                    100,
                ],
                true
            )
        ) {
            $perPage = 20;
        }

        $reviewers = $query
            ->paginate($perPage)
            ->withQueryString();

        return view(
            'admin.reviewers.index',
            compact(
                'reviewers',
                'statistics'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADVANCED REVIEWER SEARCH
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Dropdown Data
        |--------------------------------------------------------------------------
        */

        $designations = $this->distinctProfileValues(
            'designation'
        );

        $departments = $this->distinctProfileValues(
            'department'
        );

        $institutions = $this->distinctProfileValues(
            'institution'
        );

        $qualifications = $this->distinctProfileValues(
            'highest_degree'
        );

        $specializations = $this->distinctProfileValues(
            'specialization'
        );

        $researchInterests = $this->distinctProfileValues(
            'research_interests'
        );

        $countries = $this->distinctProfileValues(
            'country'
        );

        /*
        |--------------------------------------------------------------------------
        | Division / State
        |--------------------------------------------------------------------------
        */

        $divisionQuery = ReviewerProfile::query()
            ->whereNotNull('division_state')
            ->where(
                'division_state',
                '<>',
                ''
            );

        if ($request->filled('country')) {

            $divisionQuery->where(
                'country',
                $request->country
            );
        }

        $divisions = $divisionQuery
            ->distinct()
            ->orderBy('division_state')
            ->pluck('division_state');

        /*
        |--------------------------------------------------------------------------
        | City / District
        |--------------------------------------------------------------------------
        */

        $districtQuery = ReviewerProfile::query()
            ->whereNotNull('city_district')
            ->where(
                'city_district',
                '<>',
                ''
            );

        if ($request->filled('country')) {

            $districtQuery->where(
                'country',
                $request->country
            );
        }

        if ($request->filled('division_state')) {

            $districtQuery->where(
                'division_state',
                $request->division_state
            );
        }

        $districts = $districtQuery
            ->distinct()
            ->orderBy('city_district')
            ->pluck('city_district');

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = Reviewer::query()
            ->with([
                'profile',
                'creator',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Only Approved Accounts
        |--------------------------------------------------------------------------
        */

        $query->where(
            'status',
            'approved'
        );

        /*
        |--------------------------------------------------------------------------
        | Only Approved Profiles
        |--------------------------------------------------------------------------
        */

        $query->whereHas(
            'profile',
            function ($q) {

                $q->where(
                    'approval_status',
                    'approved'
                )
                ->where(
                    'profile_completed',
                    true
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | General Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword = trim(
                $request->keyword
            );

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'name',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhereHas(
                    'profile',
                    function ($profileQuery) use ($keyword) {

                        $profileQuery
                            ->where(
                                'reviewer_code',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'display_name',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'institution',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'department',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'designation',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'highest_degree',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'speciality',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'sub_speciality',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'specialization',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'research_interests',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'areas_of_expertise',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'primary_expertise',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'expertise_keywords',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'orcid',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'country',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'division_state',
                                'like',
                                "%{$keyword}%"
                            )
                            ->orWhere(
                                'city_district',
                                'like',
                                "%{$keyword}%"
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Exact Filters
        |--------------------------------------------------------------------------
        */

        $this->applyProfileExactFilter(
            $query,
            $request,
            'designation'
        );

        $this->applyProfileExactFilter(
            $query,
            $request,
            'department'
        );

        $this->applyProfileExactFilter(
            $query,
            $request,
            'institution'
        );

        $this->applyProfileExactFilter(
            $query,
            $request,
            'country'
        );

        $this->applyProfileExactFilter(
            $query,
            $request,
            'division_state'
        );

        $this->applyProfileExactFilter(
            $query,
            $request,
            'city_district'
        );

        /*
        |--------------------------------------------------------------------------
        | Highest Degree Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('qualification')) {

            $qualification =
                $request->qualification;

            $query->whereHas(
                'profile',
                fn ($q) =>
                    $q->where(
                        'highest_degree',
                        $qualification
                    )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Specialization Search
        |--------------------------------------------------------------------------
        |
        | Specialization can contain multiple values, so LIKE is used.
        |
        */

        if ($request->filled('specialization')) {

            $value =
                trim(
                    $request->specialization
                );

            $query->whereHas(
                'profile',
                fn ($q) =>
                    $q->where(
                        'specialization',
                        'like',
                        "%{$value}%"
                    )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Research Interest
        |--------------------------------------------------------------------------
        */

        if ($request->filled('research_interest')) {

            $value =
                trim(
                    $request->research_interest
                );

            $query->whereHas(
                'profile',
                fn ($q) =>
                    $q->where(
                        'research_interests',
                        'like',
                        "%{$value}%"
                    )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Availability
        |--------------------------------------------------------------------------
        */

        $query->whereHas(
            'profile',
            function ($q) {

                $q->where(
                    'available_for_review',
                    true
                )
                ->where(
                    'receive_review_invitations',
                    true
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Sort
        |--------------------------------------------------------------------------
        */

        switch (
            $request->input(
                'sort',
                'name'
            )
        ) {

            case 'newest':

                $query->latest();

                break;

            case 'oldest':

                $query->oldest();

                break;

            case 'name_desc':

                $query->orderBy(
                    'name',
                    'desc'
                );

                break;

            default:

                $query->orderBy(
                    'name',
                    'asc'
                );

                break;
        }

        $perPage = (int) $request->input(
            'per_page',
            20
        );

        if (
            !in_array(
                $perPage,
                [
                    10,
                    20,
                    50,
                    100,
                ],
                true
            )
        ) {
            $perPage = 20;
        }

        $reviewers = $query
            ->paginate($perPage)
            ->withQueryString();

        return view(
            'admin.reviewers.search',
            compact(
                'reviewers',
                'designations',
                'departments',
                'institutions',
                'qualifications',
                'specializations',
                'researchInterests',
                'countries',
                'divisions',
                'districts'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE REVIEWER
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.reviewers.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE REVIEWER
    |--------------------------------------------------------------------------
    |
    | Editorial Officer creates basic account only.
    |
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:reviewers,email',
            ],
        ]);

        $temporaryPassword = Str::password(
            length: 12,
            letters: true,
            numbers: true,
            symbols: true
        );

        try {

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Reviewer Account
            |--------------------------------------------------------------------------
            */

            $reviewer = Reviewer::create([

                'name' =>
                    $validated['name'],

                'email' =>
                    $validated['email'],

                /*
                | Reviewer model uses:
                |
                | 'password' => 'hashed'
                |
                | so do NOT Hash::make() here.
                */

                'password' =>
                    $temporaryPassword,

                'status' =>
                    'pending',

                'created_source' =>
                    'editorial_officer',

                'created_by' =>
                    auth()->id(),

                'must_change_password' =>
                    true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Draft Profile
            |--------------------------------------------------------------------------
            */

            ReviewerProfile::create([

                'reviewer_id' =>
                    $reviewer->id,

                'profile_completed' =>
                    false,

                'profile_completion_percentage' =>
                    0,

                'approval_status' =>
                    'draft',

                'available_for_review' =>
                    false,

                'receive_review_invitations' =>
                    true,

                'receive_reminders' =>
                    true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Send Login Email
            |--------------------------------------------------------------------------
            */

            Mail::to(
                $reviewer->email
            )->send(
                new ReviewerAccountCreated(
                    $reviewer,
                    $temporaryPassword
                )
            );

            $reviewer->update([
                'temporary_password_sent_at' =>
                    now(),
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'admin.reviewers.show',
                    $reviewer
                )
                ->with([
                    'success' =>
                        'Reviewer account created successfully. Login information has been emailed to the reviewer.',

                    'temporary_password' =>
                        $temporaryPassword,
                ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create reviewer account. Please check the configuration and try again.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW REVIEWER
    |--------------------------------------------------------------------------
    */

    public function show(Reviewer $reviewer)
    {
        $reviewer->load([
            'creator',
            'profile.approvedBy',
        ]);

        return view(
            'admin.reviewers.show',
            compact(
                'reviewer'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT REVIEWER
    |--------------------------------------------------------------------------
    */

    public function edit(Reviewer $reviewer)
    {
        $reviewer->load([
            'profile',
            'creator',
        ]);

        return view(
            'admin.reviewers.edit',
            compact(
                'reviewer'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE BASIC ACCOUNT INFORMATION
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Reviewer $reviewer
    ) {
        $validated =
            $request->validate([

                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',

                    Rule::unique(
                        'reviewers',
                        'email'
                    )->ignore(
                        $reviewer->id
                    ),
                ],
            ]);

        $reviewer->update([

            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],
        ]);

        return redirect()
            ->route(
                'admin.reviewers.show',
                $reviewer
            )
            ->with(
                'success',
                'Reviewer information updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE INCOMPLETE
    |--------------------------------------------------------------------------
    */

    public function profileIncomplete()
    {
        $reviewers = Reviewer::query()
            ->with('profile')
            ->where(function ($query) {

                $query
                    ->whereDoesntHave('profile')

                    ->orWhereHas(
                        'profile',
                        function ($profileQuery) {

                            $profileQuery->where(
                                'profile_completed',
                                false
                            );
                        }
                    );
            })
            ->latest()
            ->paginate(20);

        return view(
            'admin.reviewers.profile-incomplete',
            compact(
                'reviewers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PENDING ACCOUNTS
    |--------------------------------------------------------------------------
    */

    public function pending()
    {
        return $this->statusList(
            'pending',
            'admin.reviewers.pending'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVED ACCOUNTS
    |--------------------------------------------------------------------------
    */

    public function approved()
    {
        return $this->statusList(
            'approved',
            'admin.reviewers.approved'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECTED ACCOUNTS
    |--------------------------------------------------------------------------
    */

    public function rejected()
    {
        return $this->statusList(
            'rejected',
            'admin.reviewers.rejected'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUSPENDED ACCOUNTS
    |--------------------------------------------------------------------------
    */

    public function suspended()
    {
        return $this->statusList(
            'suspended',
            'admin.reviewers.suspended'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE UPDATE REQUESTED
    |--------------------------------------------------------------------------
    */

    public function updateRequested()
    {
        $reviewers = Reviewer::query()
            ->with([
                'profile',
                'creator',
            ])
            ->whereHas(
                'profile',
                function ($query) {

                    $query->where(
                        'approval_status',
                        'update_requested'
                    );
                }
            )
            ->latest()
            ->paginate(20);

        return view(
            'admin.reviewers.update-requested',
            compact(
                'reviewers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE REVIEWER
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | reviewers.status = approved
    | reviewer_profiles.approval_status = approved
    |
    */

    public function approve(Reviewer $reviewer)
    {
        $reviewer->load('profile');

        $profile =
            $reviewer->profile;

        if (!$profile) {

            return back()->with(
                'error',
                'Reviewer profile does not exist.'
            );
        }

        if (!$profile->profile_completed) {

            return back()->with(
                'error',
                'Reviewer profile is not complete. The reviewer must complete the profile before approval.'
            );
        }

        if (
            $profile->approval_status
            !== 'pending_approval'
            &&
            $profile->approval_status
            !== 'update_requested'
        ) {

            if (
                $profile->approval_status
                === 'approved'
            ) {

                return back()->with(
                    'warning',
                    'This reviewer is already approved.'
                );
            }

            return back()->with(
                'error',
                'This reviewer profile has not been submitted for approval.'
            );
        }

        DB::transaction(
            function () use (
                $reviewer,
                $profile
            ) {

                /*
                |--------------------------------------------------------------------------
                | Account
                |--------------------------------------------------------------------------
                */

                $reviewer->update([

                    'status' =>
                        'approved',

                    'activated_at' =>
                        $reviewer->activated_at
                        ?: now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Profile
                |--------------------------------------------------------------------------
                */

                $profile->update([

                    'approval_status' =>
                        'approved',

                    'approved_at' =>
                        now(),

                    'approved_by' =>
                        auth()->id(),

                    'rejected_at' =>
                        null,

                    'rejection_reason' =>
                        null,

                    'update_requested_at' =>
                        null,

                    'profile_update_request' =>
                        null,

                    'requires_reverification' =>
                        false,

                    /*
                    | Reviewer becomes eligible for
                    | invitations after approval.
                    */

                    'available_for_review' =>
                        true,
                ]);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Reload Models
        |--------------------------------------------------------------------------
        */

        $reviewer->refresh();
        $reviewer->load('profile');

        return redirect()
            ->route(
                'admin.reviewers.show',
                $reviewer
            )
            ->with(
                'success',
                'Reviewer application approved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT REVIEWER
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Reviewer $reviewer
    ) {
        $validated =
            $request->validate([

                'rejection_reason' => [
                    'required',
                    'string',
                    'max:3000',
                ],
            ]);

        $reviewer->load('profile');

        $profile =
            $reviewer->profile;

        if (!$profile) {

            return back()->with(
                'error',
                'Reviewer profile does not exist.'
            );
        }

        DB::transaction(
            function () use (
                $reviewer,
                $profile,
                $validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | Reviewer Account
                |--------------------------------------------------------------------------
                */

                $reviewer->update([
                    'status' =>
                        'rejected',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Reviewer Profile
                |--------------------------------------------------------------------------
                */

                $profile->update([

                    'approval_status' =>
                        'rejected',

                    'rejected_at' =>
                        now(),

                    'rejection_reason' =>
                        $validated[
                            'rejection_reason'
                        ],

                    'approved_at' =>
                        null,

                    'approved_by' =>
                        null,

                    'update_requested_at' =>
                        null,

                    'profile_update_request' =>
                        null,

                    'available_for_review' =>
                        false,
                ]);
            }
        );

        return redirect()
            ->route(
                'admin.reviewers.show',
                $reviewer
            )
            ->with(
                'success',
                'Reviewer application rejected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REQUEST PROFILE UPDATE
    |--------------------------------------------------------------------------
    */

    public function requestUpdate(
        Request $request,
        Reviewer $reviewer
    ) {
        /*
        | This name matches the admin modal:
        |
        | name="profile_update_request"
        */

        $validated =
            $request->validate([

                'profile_update_request' => [
                    'required',
                    'string',
                    'max:3000',
                ],
            ]);

        $reviewer->load('profile');

        $profile =
            $reviewer->profile;

        if (!$profile) {

            return back()->with(
                'error',
                'Reviewer profile does not exist.'
            );
        }

        DB::transaction(
            function () use (
                $reviewer,
                $profile,
                $validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | Keep Account Pending
                |--------------------------------------------------------------------------
                */

                $reviewer->update([
                    'status' =>
                        'pending',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Profile Status
                |--------------------------------------------------------------------------
                */

                $profile->update([

                    'approval_status' =>
                        'update_requested',

                    'profile_update_request' =>
                        $validated[
                            'profile_update_request'
                        ],

                    'update_requested_at' =>
                        now(),

                    'approved_at' =>
                        null,

                    'approved_by' =>
                        null,

                    'rejected_at' =>
                        null,

                    'rejection_reason' =>
                        null,

                    'available_for_review' =>
                        false,
                ]);
            }
        );

        return redirect()
            ->route(
                'admin.reviewers.show',
                $reviewer
            )
            ->with(
                'success',
                'Profile update request sent successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUSPEND REVIEWER
    |--------------------------------------------------------------------------
    |
    | Do not change profile approval_status.
    |
    | The profile may remain professionally approved while
    | the account itself is temporarily suspended.
    |
    */

    public function suspend(
        Reviewer $reviewer
    ) {
        if (
            $reviewer->status
            !== 'approved'
        ) {

            return back()->with(
                'error',
                'Only approved reviewers can be suspended.'
            );
        }

        $reviewer->update([
            'status' =>
                'suspended',
        ]);

        return redirect()
            ->route(
                'admin.reviewers.show',
                $reviewer
            )
            ->with(
                'success',
                'Reviewer account suspended successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVATE SUSPENDED REVIEWER
    |--------------------------------------------------------------------------
    */

    public function activate(
        Reviewer $reviewer
    ) {
        if (
            $reviewer->status
            !== 'suspended'
        ) {

            return back()->with(
                'error',
                'This reviewer account is not suspended.'
            );
        }

        $reviewer->load('profile');

        /*
        | Account should only be reactivated when
        | the underlying reviewer profile is approved.
        */

        if (
            !$reviewer->profile
            ||
            $reviewer->profile->approval_status
            !== 'approved'
        ) {

            return back()->with(
                'error',
                'Reviewer profile must be approved before account activation.'
            );
        }

        $reviewer->update([

            'status' =>
                'approved',

            'activated_at' =>
                now(),
        ]);

        return redirect()
            ->route(
                'admin.reviewers.show',
                $reviewer
            )
            ->with(
                'success',
                'Reviewer account activated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | WORKLOAD
    |--------------------------------------------------------------------------
    */

    public function workload()
    {
        $reviewers = Reviewer::query()
            ->with('profile')
            ->where(
                'status',
                'approved'
            )
            ->whereHas(
                'profile',
                function ($query) {

                    $query
                        ->where(
                            'approval_status',
                            'approved'
                        )
                        ->where(
                            'available_for_review',
                            true
                        );
                }
            )
            ->orderBy('name')
            ->paginate(20);

        return view(
            'admin.reviewers.workload',
            compact(
                'reviewers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REVIEW HISTORY
    |--------------------------------------------------------------------------
    */

    public function reviewHistory()
    {
        $reviewers = Reviewer::query()
            ->with('profile')
            ->orderBy('name')
            ->paginate(20);

        return view(
            'admin.reviewers.review-history',
            compact(
                'reviewers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PERFORMANCE
    |--------------------------------------------------------------------------
    */

    public function performance()
    {
        $reviewers = Reviewer::query()
            ->with('profile')
            ->where(
                'status',
                'approved'
            )
            ->whereHas(
                'profile',
                fn ($query) =>
                    $query->where(
                        'approval_status',
                        'approved'
                    )
            )
            ->orderBy('name')
            ->paginate(20);

        return view(
            'admin.reviewers.performance',
            compact(
                'reviewers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRIVATE: ACCOUNT STATUS LIST
    |--------------------------------------------------------------------------
    */

    private function statusList(
        string $status,
        string $view
    ) {
        $reviewers =
            Reviewer::query()
                ->with([
                    'profile',
                    'creator',
                ])
                ->where(
                    'status',
                    $status
                )
                ->latest()
                ->paginate(20);

        return view(
            $view,
            compact(
                'reviewers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRIVATE: DISTINCT PROFILE VALUES
    |--------------------------------------------------------------------------
    */

    private function distinctProfileValues(
        string $column
    ) {
        return ReviewerProfile::query()
            ->whereNotNull(
                $column
            )
            ->where(
                $column,
                '<>',
                ''
            )
            ->distinct()
            ->orderBy(
                $column
            )
            ->pluck(
                $column
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PRIVATE: APPLY EXACT PROFILE FILTER
    |--------------------------------------------------------------------------
    */

    private function applyProfileExactFilter(
        $query,
        Request $request,
        string $field
    ): void {
        if (
            !$request->filled(
                $field
            )
        ) {
            return;
        }

        $value =
            $request->input(
                $field
            );

        $query->whereHas(
            'profile',
            function ($profileQuery) use (
                $field,
                $value
            ) {

                $profileQuery->where(
                    $field,
                    $value
                );
            }
        );
    }
}