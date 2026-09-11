<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use App\Models\ReviewerLookupOption;
use App\Models\ReviewerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ReviewerAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTRATION FORM
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        if (Auth::guard('reviewer')->check()) {
            return redirect()->route('reviewer.dashboard');
        }

        $lookup = function (string $type) {
            return ReviewerLookupOption::query()
                ->where('type', $type)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('value')
                ->pluck('value');
        };

        $divisions = $lookup('division_state');

        $districts = ReviewerLookupOption::query()
            ->where('type', 'city_district')
            ->where('is_active', true)
            ->orderBy('parent_value')
            ->orderBy('value')
            ->get([
                'value',
                'parent_value',
            ]);

        $institutions = $lookup('institution');
        $departments = $lookup('department');
        $designations = $lookup('designation');
        $highestDegrees = $lookup('highest_degree');
        $specializations = $lookup('specialization');
        $researchInterests = $lookup('research_interest');
        $reviewKeywords = $lookup('review_keyword');

        return view(
            'reviewer.auth.register',
            compact(
                'divisions',
                'districts',
                'institutions',
                'departments',
                'designations',
                'highestDegrees',
                'specializations',
                'researchInterests',
                'reviewKeywords'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER REVIEWER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Personal Information
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:30',
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:reviewers,email',
            ],

            'mobile' => [
                'required',
                'string',
                'max:30',
            ],


            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            'division_state' => [
                'required',
                'string',
                'max:255',
            ],

            'division_state_other' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city_district' => [
                'required',
                'string',
                'max:255',
            ],

            'city_district_other' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Institution / Professional Information
            |--------------------------------------------------------------------------
            */

            'institution' => [
                'required',
                'string',
                'max:255',
            ],

            'institution_other' => [
                'nullable',
                'string',
                'max:255',
            ],

            'department' => [
                'required',
                'string',
                'max:255',
            ],

            'department_other' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'required',
                'string',
                'max:255',
            ],

            'designation_other' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Highest Degree
            |--------------------------------------------------------------------------
            */

            'highest_degree' => [
                'required',
                'string',
                'max:255',
            ],

            'highest_degree_other' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Specialization - Multiple
            |--------------------------------------------------------------------------
            */

            'specialization' => [
                'required',
                'array',
                'min:1',
            ],

            'specialization.*' => [
                'string',
                'max:255',
            ],

            'specialization_other' => [
                'nullable',
                'string',
                'max:1000',
            ],


            /*
            |--------------------------------------------------------------------------
            | Research Interests - Multiple
            |--------------------------------------------------------------------------
            */

            'research_interests' => [
                'required',
                'array',
                'min:1',
            ],

            'research_interests.*' => [
                'string',
                'max:255',
            ],

            'research_interest_other' => [
                'nullable',
                'string',
                'max:1000',
            ],


            /*
            |--------------------------------------------------------------------------
            | Research / Review Keywords - Multiple
            |--------------------------------------------------------------------------
            */

            'review_keywords' => [
                'required',
                'array',
                'min:1',
            ],

            'review_keywords.*' => [
                'string',
                'max:255',
            ],

            'review_keyword_other' => [
                'nullable',
                'string',
                'max:1000',
            ],


            /*
            |--------------------------------------------------------------------------
            | CV - PDF Only
            |--------------------------------------------------------------------------
            */

            'cv_file' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120',
            ],


            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */

            'password' => [
                'required',
                'confirmed',

                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Resolve Single Dropdown Values
        |--------------------------------------------------------------------------
        */

        $divisionState = $this->resolveSingleOption(
            $validated,
            'division_state',
            'division_state_other'
        );

        $cityDistrict = $this->resolveSingleOption(
            $validated,
            'city_district',
            'city_district_other'
        );

        $institution = $this->resolveSingleOption(
            $validated,
            'institution',
            'institution_other'
        );

        $department = $this->resolveSingleOption(
            $validated,
            'department',
            'department_other'
        );

        $designation = $this->resolveSingleOption(
            $validated,
            'designation',
            'designation_other'
        );

        $highestDegree = $this->resolveSingleOption(
            $validated,
            'highest_degree',
            'highest_degree_other'
        );


        /*
        |--------------------------------------------------------------------------
        | Validate "Other" fields
        |--------------------------------------------------------------------------
        */

        $this->ensureResolvedValue(
            $divisionState,
            'division_state',
            'Division / State'
        );

        $this->ensureResolvedValue(
            $cityDistrict,
            'city_district',
            'City / District'
        );

        $this->ensureResolvedValue(
            $institution,
            'institution',
            'Institution / Organization'
        );

        $this->ensureResolvedValue(
            $department,
            'department',
            'Department'
        );

        $this->ensureResolvedValue(
            $designation,
            'designation',
            'Current Designation'
        );

        $this->ensureResolvedValue(
            $highestDegree,
            'highest_degree',
            'Highest Academic Degree'
        );


        /*
        |--------------------------------------------------------------------------
        | Resolve Multiple Values
        |--------------------------------------------------------------------------
        */

        $specializations = $this->resolveMultipleOptions(
            $validated['specialization'],
            $validated['specialization_other'] ?? null
        );

        $researchInterests = $this->resolveMultipleOptions(
            $validated['research_interests'],
            $validated['research_interest_other'] ?? null
        );

        $reviewKeywords = $this->resolveMultipleOptions(
            $validated['review_keywords'],
            $validated['review_keyword_other'] ?? null
        );


        if (empty($specializations)) {
            throw ValidationException::withMessages([
                'specialization' =>
                    'Please select or enter at least one specialization.',
            ]);
        }

        if (empty($researchInterests)) {
            throw ValidationException::withMessages([
                'research_interests' =>
                    'Please select or enter at least one research interest.',
            ]);
        }

        if (empty($reviewKeywords)) {
            throw ValidationException::withMessages([
                'review_keywords' =>
                    'Please select or enter at least one research/review keyword.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Store CV
        |--------------------------------------------------------------------------
        */

        $cvPath = $request
            ->file('cv_file')
            ->store(
                'reviewers/cv',
                'public'
            );


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Build Reviewer Name
            |--------------------------------------------------------------------------
            */

            $nameParts = array_filter([
                trim($validated['first_name']),

                filled($validated['middle_name'] ?? null)
                    ? trim($validated['middle_name'])
                    : null,

                trim($validated['last_name']),
            ]);

            $fullName = implode(
                ' ',
                $nameParts
            );


            /*
            |--------------------------------------------------------------------------
            | Create Reviewer Authentication Account
            |--------------------------------------------------------------------------
            |
            | Reviewer model already has:
            |
            | 'password' => 'hashed'
            |
            | Therefore DO NOT use Hash::make().
            |--------------------------------------------------------------------------
            */

            $reviewer = Reviewer::create([

                'name' =>
                    $fullName,

                'email' =>
                    strtolower(
                        trim($validated['email'])
                    ),

                'password' =>
                    $validated['password'],

                'status' =>
                    'pending',

                'created_source' =>
                    'self_registration',

                'created_by' =>
                    null,

                /*
                | User chose their own password.
                */

                'must_change_password' =>
                    false,

                'password_changed_at' =>
                    now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Generate Application ID
            |--------------------------------------------------------------------------
            */

            $applicationId =
                'BMRC-REV-'
                . now()->format('Y')
                . '-'
                . str_pad(
                    $reviewer->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                );


            /*
            |--------------------------------------------------------------------------
            | Generate Reviewer Code
            |--------------------------------------------------------------------------
            |
            | You may later decide to generate reviewer_code only after approval.
            | This keeps your existing behaviour for now.
            |--------------------------------------------------------------------------
            */

            do {

                $reviewerCode =
                    'BMRC-REV-'
                    . strtoupper(
                        Str::random(8)
                    );

            } while (
                ReviewerProfile::query()
                    ->where(
                        'reviewer_code',
                        $reviewerCode
                    )
                    ->exists()
            );


            /*
            |--------------------------------------------------------------------------
            | Create Reviewer Profile
            |--------------------------------------------------------------------------
            */

            $profile = ReviewerProfile::create([

                'reviewer_id' =>
                    $reviewer->id,

                'application_id' =>
                    $applicationId,

                'reviewer_code' =>
                    $reviewerCode,


                /*
                |--------------------------------------------------------------------------
                | Personal
                |--------------------------------------------------------------------------
                */

                'title' =>
                    trim($validated['title']),

                'first_name' =>
                    trim($validated['first_name']),

                'middle_name' =>
                    filled($validated['middle_name'] ?? null)
                        ? trim($validated['middle_name'])
                        : null,

                'last_name' =>
                    trim($validated['last_name']),

                'display_name' =>
                    trim(
                        $validated['title']
                        . ' '
                        . $fullName
                    ),

                'mobile' =>
                    trim($validated['mobile']),


                /*
                |--------------------------------------------------------------------------
                | Location
                |--------------------------------------------------------------------------
                */

                'country' =>
                    'Bangladesh',

                'division_state' =>
                    $divisionState,

                'city_district' =>
                    $cityDistrict,


                /*
                |--------------------------------------------------------------------------
                | Professional
                |--------------------------------------------------------------------------
                */

                'institution' =>
                    $institution,

                'department' =>
                    $department,

                'designation' =>
                    $designation,


                /*
                |--------------------------------------------------------------------------
                | Academic
                |--------------------------------------------------------------------------
                */

                'highest_degree' =>
                    $highestDegree,


                /*
                |--------------------------------------------------------------------------
                | Expertise / Research
                |--------------------------------------------------------------------------
                |
                | Current database columns are TEXT.
                |--------------------------------------------------------------------------
                */

                'specialization' =>
                    implode(
                        ', ',
                        $specializations
                    ),

                'research_interests' =>
                    implode(
                        ', ',
                        $researchInterests
                    ),

                'expertise_keywords' =>
                    implode(
                        ', ',
                        $reviewKeywords
                    ),


                /*
                |--------------------------------------------------------------------------
                | CV
                |--------------------------------------------------------------------------
                */

                'cv_file' =>
                    $cvPath,


                /*
                |--------------------------------------------------------------------------
                | Profile Lifecycle
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | Your ReviewerProfile model uses approval_status,
                | NOT status.
                |--------------------------------------------------------------------------
                */

                'profile_completed' =>
                    false,

                'profile_completion_percentage' =>
                    60,

                'profile_completed_at' =>
                    null,

                'approval_status' =>
                    'draft',

                'submitted_for_approval_at' =>
                    null,

                'available_for_review' =>
                    false,

                'maximum_active_reviews' =>
                    3,

                'preferred_communication_method' =>
                    'email',

                'receive_review_invitations' =>
                    true,

                'receive_reminders' =>
                    true,

                'requires_reverification' =>
                    false,

                'last_profile_updated_at' =>
                    now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Add Dynamic Values to Lookup Master
            |--------------------------------------------------------------------------
            */

            $this->saveLookup(
                'division_state',
                $divisionState
            );

            $this->saveLookup(
                'city_district',
                $cityDistrict,
                $divisionState
            );

            $this->saveLookup(
                'institution',
                $institution
            );

            $this->saveLookup(
                'department',
                $department
            );

            $this->saveLookup(
                'designation',
                $designation
            );

            $this->saveLookup(
                'highest_degree',
                $highestDegree
            );


            foreach ($specializations as $item) {
                $this->saveLookup(
                    'specialization',
                    $item
                );
            }


            foreach ($researchInterests as $item) {
                $this->saveLookup(
                    'research_interest',
                    $item
                );
            }


            foreach ($reviewKeywords as $item) {
                $this->saveLookup(
                    'review_keyword',
                    $item
                );
            }


            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Auto Login
            |--------------------------------------------------------------------------
            */

            Auth::guard('reviewer')
                ->login($reviewer);

            $request
                ->session()
                ->regenerate();


            /*
            |--------------------------------------------------------------------------
            | Registration goes to Dashboard
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('reviewer.dashboard')
                ->with(
                    'success',
                    'Your reviewer account has been created successfully. Please complete the remaining reviewer profile information before submitting your application for editorial approval.'
                );


        } catch (\Throwable $e) {

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Delete CV if registration failed
            |--------------------------------------------------------------------------
            */

            if (
                filled($cvPath)
                &&
                Storage::disk('public')
                    ->exists($cvPath)
            ) {

                Storage::disk('public')
                    ->delete($cvPath);
            }


            report($e);


            return back()
                ->withInput()
                ->withErrors([
                    'register' =>
                        'Unable to create reviewer account. '
                        . $e->getMessage(),
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN FORM
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        if (
            Auth::guard('reviewer')
                ->check()
        ) {
            return redirect()
                ->route('reviewer.dashboard');
        }

        return view(
            'reviewer.auth.login'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Authenticate Reviewer
        |--------------------------------------------------------------------------
        */

        if (
            ! Auth::guard('reviewer')
                ->attempt(
                    [
                        'email' =>
                            strtolower(
                                trim(
                                    $credentials['email']
                                )
                            ),

                        'password' =>
                            $credentials['password'],
                    ],

                    $request->boolean(
                        'remember'
                    )
                )
        ) {

            return back()
                ->withErrors([
                    'email' =>
                        'The email or password is incorrect.',
                ])
                ->onlyInput('email');
        }


        $request
            ->session()
            ->regenerate();


        $reviewer =
            Auth::guard('reviewer')
                ->user();


        /*
        |--------------------------------------------------------------------------
        | Update Login Time
        |--------------------------------------------------------------------------
        */

        $reviewer->update([
            'last_login_at' => now(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Suspended Reviewer
        |--------------------------------------------------------------------------
        */

        if ($reviewer->isSuspended()) {

            Auth::guard('reviewer')
                ->logout();

            $request
                ->session()
                ->invalidate();

            $request
                ->session()
                ->regenerateToken();


            return redirect()
                ->route('reviewer.login')
                ->withErrors([
                    'email' =>
                        'Your reviewer account is currently suspended.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Forced Password Change
        |--------------------------------------------------------------------------
        |
        | Used for reviewers created by Editorial Officer.
        |--------------------------------------------------------------------------
        */

        if ($reviewer->mustChangePassword()) {

            return redirect()
                ->route(
                    'reviewer.password.change'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Profile Check
        |--------------------------------------------------------------------------
        */

        $profile =
            $reviewer->profile;


        if (!$profile) {

            Auth::guard('reviewer')
                ->logout();

            $request
                ->session()
                ->invalidate();

            $request
                ->session()
                ->regenerateToken();


            return redirect()
                ->route('reviewer.login')
                ->withErrors([
                    'email' =>
                        'Reviewer profile was not found. Please contact the BMRC Editorial Office.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Every Valid Reviewer Can Enter Dashboard
        |--------------------------------------------------------------------------
        |
        | Dashboard handles:
        |
        | draft
        | pending_approval
        | update_requested
        | approved
        | rejected
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->intended(
                route(
                    'reviewer.dashboard'
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::guard('reviewer')
            ->logout();

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();


        return redirect()
            ->route(
                'reviewer.login'
            )
            ->with(
                'success',
                'You have been logged out successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPLICATION
    |--------------------------------------------------------------------------
    */

    public function application()
    {
        $reviewer =
            Auth::guard('reviewer')
                ->user();


        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }


        $profile =
            $reviewer->profile;


        if (!$profile) {

            abort(
                404,
                'Reviewer profile not found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Approved profile should normally be read-only.
        |--------------------------------------------------------------------------
        */

        if ($profile->isApproved()) {

            return redirect()
                ->route(
                    'reviewer.dashboard'
                )
                ->with(
                    'info',
                    'Your reviewer profile has already been approved.'
                );
        }


        return view(
            'reviewer.application.edit',
            compact(
                'reviewer',
                'profile'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $reviewer =
            Auth::guard('reviewer')
                ->user();


        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }


        $reviewer->load(
            'profile'
        );


        $profile =
            $reviewer->profile;


        if (!$profile) {

            abort(
                404,
                'Reviewer profile not found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Do NOT block pending reviewers from dashboard.
        |
        | Registration → Dashboard
        |
        | The dashboard tells the reviewer what action is required.
        |--------------------------------------------------------------------------
        */

        return view(
            'reviewer.dashboard',
            compact(
                'reviewer',
                'profile'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE SINGLE DROPDOWN
    |--------------------------------------------------------------------------
    */

    private function resolveSingleOption(
        array $validated,
        string $field,
        string $otherField
    ): string {

        if (
            ($validated[$field] ?? null)
            === '__other__'
        ) {

            return trim(
                $validated[$otherField]
                    ?? ''
            );
        }


        return trim(
            $validated[$field]
                ?? ''
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE MULTIPLE DROPDOWN
    |--------------------------------------------------------------------------
    */

    private function resolveMultipleOptions(
        array $values,
        ?string $otherValue = null
    ): array {

        $items = collect($values)

            ->reject(
                fn ($value) =>
                    $value === '__other__'
            )

            ->map(
                fn ($value) =>
                    trim($value)
            )

            ->filter();


        /*
        |--------------------------------------------------------------------------
        | Add Custom "Other" Values
        |--------------------------------------------------------------------------
        |
        | Supports:
        |
        | Cardiac Surgery, Thoracic Surgery
        |
        | or
        |
        | Cardiac Surgery; Thoracic Surgery
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                '__other__',
                $values,
                true
            )
            &&
            filled($otherValue)
        ) {

            $customValues =
                preg_split(
                    '/[,;]+/',
                    $otherValue
                );


            foreach ($customValues as $value) {

                $value =
                    trim($value);


                if ($value !== '') {

                    $items->push(
                        $value
                    );
                }
            }
        }


        return $items

            ->unique(
                fn ($value) =>
                    mb_strtolower(
                        $value
                    )
            )

            ->values()

            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | SAVE LOOKUP OPTION
    |--------------------------------------------------------------------------
    */

    private function saveLookup(
        string $type,
        ?string $value,
        ?string $parentValue = null
    ): void {

        if (!filled($value)) {
            return;
        }


        ReviewerLookupOption::firstOrCreate(
            [
                'type' =>
                    $type,

                'value' =>
                    trim($value),

                'parent_value' =>
                    $parentValue,
            ],
            [
                'is_active' =>
                    true,

                'sort_order' =>
                    0,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ENSURE OTHER VALUE
    |--------------------------------------------------------------------------
    */

    private function ensureResolvedValue(
        string $value,
        string $field,
        string $label
    ): void {

        if ($value === '') {

            throw ValidationException::withMessages([
                $field =>
                    $label
                    . ' is required.',
            ]);
        }
    }
}