<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ReviewerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReviewerApplicationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Edit Profile
    |--------------------------------------------------------------------------
    */

    public function edit()
    {
        $reviewer = Auth::guard('reviewer')->user();

        if (!$reviewer) {
            return redirect()
                ->route('reviewer.login');
        }

        $reviewer->load('profile');

        $profile = $reviewer->profile;

        /*
        |--------------------------------------------------------------------------
        | Fallback Profile
        |--------------------------------------------------------------------------
        */

        if (!$profile) {

            $profile = ReviewerProfile::create([

                'reviewer_id' =>
                    $reviewer->id,

                'first_name' =>
                    $reviewer->name,

                'last_name' =>
                    '-',

                'country' =>
                    'Bangladesh',

                'approval_status' =>
                    'draft',

                'profile_completed' =>
                    false,

                'profile_completion_percentage' =>
                    0,

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
            ]);
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
    | Save Profile
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $reviewer = Auth::guard('reviewer')->user();

        abort_unless($reviewer, 401);

        $reviewer->load('profile');

        $profile = $reviewer->profile;

        if (!$profile) {

            return redirect()
                ->route('reviewer.application.edit')
                ->with(
                    'error',
                    'Reviewer profile not found.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Editing Rules
        |--------------------------------------------------------------------------
        */

        if ($profile->isPendingApproval()) {

            return redirect()
                ->route('reviewer.application.status')
                ->with(
                    'warning',
                    'Your application is currently under editorial review.'
                );
        }


        if ($profile->isApproved()) {

            return redirect()
                ->route('reviewer.profile.show')
                ->with(
                    'warning',
                    'Your approved professional profile cannot be directly edited.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Personal
            |--------------------------------------------------------------------------
            */

            'title' => [
                'nullable',
                'string',
                'max:50',
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

            'display_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:50',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
            ],

            'nationality' => [
                'nullable',
                'string',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            'alternative_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'mobile' => [
                'nullable',
                'string',
                'max:50',
            ],

            'alternative_mobile' => [
                'nullable',
                'string',
                'max:50',
            ],


            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'country' => [
                'nullable',
                'string',
                'max:150',
            ],

            'division_state' => [
                'nullable',
                'string',
                'max:150',
            ],

            'city_district' => [
                'nullable',
                'string',
                'max:150',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'postal_address' => [
                'nullable',
                'string',
            ],

            'office_address' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Professional
            |--------------------------------------------------------------------------
            */

            'institution' => [
                'nullable',
                'string',
                'max:255',
            ],

            'department' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'organization_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'professional_experience' => [
                'nullable',
                'string',
            ],

            'years_of_experience' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],

            'professional_registration_no' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Qualifications
            |--------------------------------------------------------------------------
            */

            'academic_qualifications' => [
                'nullable',
                'string',
            ],

            'professional_qualifications' => [
                'nullable',
                'string',
            ],

            'highest_degree' => [
                'nullable',
                'string',
                'max:255',
            ],

            'highest_degree_institution' => [
                'nullable',
                'string',
                'max:255',
            ],

            'year_of_highest_degree' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],


            /*
            |--------------------------------------------------------------------------
            | Speciality
            |--------------------------------------------------------------------------
            */

            'speciality' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sub_speciality' => [
                'nullable',
                'string',
                'max:255',
            ],

            'specialization' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Research
            |--------------------------------------------------------------------------
            */

            'research_interests' => [
                'nullable',
                'string',
            ],

            'publication_count' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'first_author_publications' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'corresponding_author_publications' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'research_experience' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Research Identifiers
            |--------------------------------------------------------------------------
            */

            'orcid' => [
                'nullable',
                'string',
                'max:255',
            ],

            'researcher_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'scopus_author_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'web_of_science_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'google_scholar_profile' => [
                'nullable',
                'url',
                'max:2000',
            ],


            /*
            |--------------------------------------------------------------------------
            | Expertise
            |--------------------------------------------------------------------------
            */

            'areas_of_expertise' => [
                'nullable',
                'string',
            ],

            'primary_expertise' => [
                'nullable',
                'string',
                'max:255',
            ],

            'secondary_expertise' => [
                'nullable',
                'string',
            ],

            'methodological_expertise' => [
                'nullable',
                'string',
            ],

            'expertise_keywords' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Reviewing Experience
            |--------------------------------------------------------------------------
            */

            'reviewing_experience' => [
                'nullable',
                'string',
            ],

            'previous_journal_experience' => [
                'nullable',
                'string',
            ],

            'external_reviews_completed' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'professional_memberships' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | CV
            |--------------------------------------------------------------------------
            */

            'cv_file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120',
            ],


            /*
            |--------------------------------------------------------------------------
            | Availability
            |--------------------------------------------------------------------------
            */

            'available_for_review' => [
                'nullable',
                'boolean',
            ],

            'unavailable_from' => [
                'nullable',
                'date',
            ],

            'unavailable_until' => [
                'nullable',
                'date',
                'after_or_equal:unavailable_from',
            ],

            'maximum_active_reviews' => [
                'nullable',
                'integer',
                'min:1',
                'max:20',
            ],


            /*
            |--------------------------------------------------------------------------
            | Communication
            |--------------------------------------------------------------------------
            */

            'preferred_communication_method' => [
                'nullable',
                Rule::in([
                    'email',
                    'mobile',
                    'both',
                ]),
            ],

            'receive_review_invitations' => [
                'nullable',
                'boolean',
            ],

            'receive_reminders' => [
                'nullable',
                'boolean',
            ],


            /*
            |--------------------------------------------------------------------------
            | Declarations
            |--------------------------------------------------------------------------
            */

            'conflict_of_interest_declaration' => [
                'nullable',
                'boolean',
            ],

            'reviewer_ethics_declaration' => [
                'nullable',
                'boolean',
            ],

            'confidentiality_declaration' => [
                'nullable',
                'boolean',
            ],
        ]);


        try {

            DB::beginTransaction();


            /*
            |--------------------------------------------------------------------------
            | CV
            |--------------------------------------------------------------------------
            */

            $cvPath = $profile->cv_file;


            if ($request->hasFile('cv_file')) {

                $newPath = $request
                    ->file('cv_file')
                    ->store(
                        'reviewers/cv',
                        'public'
                    );


                if (
                    $profile->cv_file
                    &&
                    Storage::disk('public')
                        ->exists($profile->cv_file)
                ) {

                    Storage::disk('public')
                        ->delete($profile->cv_file);
                }


                $cvPath = $newPath;
            }


            /*
            |--------------------------------------------------------------------------
            | Save all profile fields
            |--------------------------------------------------------------------------
            */

            $profile->fill([

                /*
                | Personal
                */

                'title' =>
                    $validated['title'] ?? null,

                'first_name' =>
                    $validated['first_name'],

                'middle_name' =>
                    $validated['middle_name'] ?? null,

                'last_name' =>
                    $validated['last_name'],

                'display_name' =>
                    $validated['display_name'] ?? null,

                'gender' =>
                    $validated['gender'] ?? null,

                'date_of_birth' =>
                    $validated['date_of_birth'] ?? null,

                'nationality' =>
                    $validated['nationality'] ?? null,


                /*
                | Contact
                */

                'alternative_email' =>
                    $validated['alternative_email'] ?? null,

                'mobile' =>
                    $validated['mobile'] ?? null,

                'alternative_mobile' =>
                    $validated['alternative_mobile'] ?? null,


                /*
                | Location
                */

                'country' =>
                    $validated['country'] ?? 'Bangladesh',

                'division_state' =>
                    $validated['division_state'] ?? null,

                'city_district' =>
                    $validated['city_district'] ?? null,

                'postal_code' =>
                    $validated['postal_code'] ?? null,

                'postal_address' =>
                    $validated['postal_address'] ?? null,

                'office_address' =>
                    $validated['office_address'] ?? null,


                /*
                | Professional
                */

                'institution' =>
                    $validated['institution'] ?? null,

                'department' =>
                    $validated['department'] ?? null,

                'designation' =>
                    $validated['designation'] ?? null,

                'organization_type' =>
                    $validated['organization_type'] ?? null,

                'professional_experience' =>
                    $validated['professional_experience'] ?? null,

                'years_of_experience' =>
                    $validated['years_of_experience'] ?? null,

                'professional_registration_no' =>
                    $validated['professional_registration_no'] ?? null,


                /*
                | Qualifications
                */

                'academic_qualifications' =>
                    $validated['academic_qualifications'] ?? null,

                'professional_qualifications' =>
                    $validated['professional_qualifications'] ?? null,

                'highest_degree' =>
                    $validated['highest_degree'] ?? null,

                'highest_degree_institution' =>
                    $validated['highest_degree_institution'] ?? null,

                'year_of_highest_degree' =>
                    $validated['year_of_highest_degree'] ?? null,


                /*
                | Speciality
                */

                'speciality' =>
                    $validated['speciality'] ?? null,

                'sub_speciality' =>
                    $validated['sub_speciality'] ?? null,

                'specialization' =>
                    $this->normalizeTextList(
                        $validated['specialization'] ?? null
                    ),


                /*
                | Research
                */

                'research_interests' =>
                    $this->normalizeTextList(
                        $validated['research_interests'] ?? null
                    ),

                'publication_count' =>
                    $validated['publication_count'] ?? null,

                'first_author_publications' =>
                    $validated['first_author_publications'] ?? null,

                'corresponding_author_publications' =>
                    $validated['corresponding_author_publications'] ?? null,

                'research_experience' =>
                    $validated['research_experience'] ?? null,


                /*
                | IDs
                */

                'orcid' =>
                    $validated['orcid'] ?? null,

                'researcher_id' =>
                    $validated['researcher_id'] ?? null,

                'scopus_author_id' =>
                    $validated['scopus_author_id'] ?? null,

                'web_of_science_id' =>
                    $validated['web_of_science_id'] ?? null,

                'google_scholar_profile' =>
                    $validated['google_scholar_profile'] ?? null,


                /*
                | Expertise
                */

                'areas_of_expertise' =>
                    $validated['areas_of_expertise'] ?? null,

                'primary_expertise' =>
                    $validated['primary_expertise'] ?? null,

                'secondary_expertise' =>
                    $validated['secondary_expertise'] ?? null,

                'methodological_expertise' =>
                    $validated['methodological_expertise'] ?? null,

                'expertise_keywords' =>
                    $this->normalizeTextList(
                        $validated['expertise_keywords'] ?? null
                    ),


                /*
                | Reviewing
                */

                'reviewing_experience' =>
                    $validated['reviewing_experience'] ?? null,

                'previous_journal_experience' =>
                    $validated['previous_journal_experience'] ?? null,

                'external_reviews_completed' =>
                    $validated['external_reviews_completed'] ?? null,

                'professional_memberships' =>
                    $validated['professional_memberships'] ?? null,


                /*
                | CV
                */

                'cv_file' =>
                    $cvPath,


                /*
                | Availability
                */

                'available_for_review' =>
                    $request->boolean(
                        'available_for_review'
                    ),

                'unavailable_from' =>
                    $validated['unavailable_from'] ?? null,

                'unavailable_until' =>
                    $validated['unavailable_until'] ?? null,

                'maximum_active_reviews' =>
                    $validated['maximum_active_reviews'] ?? 3,


                /*
                | Communication
                */

                'preferred_communication_method' =>
                    $validated['preferred_communication_method']
                    ?? 'email',

                'receive_review_invitations' =>
                    $request->boolean(
                        'receive_review_invitations'
                    ),

                'receive_reminders' =>
                    $request->boolean(
                        'receive_reminders'
                    ),


                /*
                | Declarations
                */

                'conflict_of_interest_declaration' =>
                    $request->boolean(
                        'conflict_of_interest_declaration'
                    ),

                'reviewer_ethics_declaration' =>
                    $request->boolean(
                        'reviewer_ethics_declaration'
                    ),

                'confidentiality_declaration' =>
                    $request->boolean(
                        'confidentiality_declaration'
                    ),


                /*
                | Audit
                */

                'last_profile_updated_at' =>
                    now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Declaration Timestamps
            |--------------------------------------------------------------------------
            */

            if (
                $request->boolean(
                    'conflict_of_interest_declaration'
                )
                &&
                !$profile->conflict_of_interest_declared_at
            ) {

                $profile->conflict_of_interest_declared_at =
                    now();
            }


            if (
                $request->boolean(
                    'reviewer_ethics_declaration'
                )
                &&
                !$profile->reviewer_ethics_declared_at
            ) {

                $profile->reviewer_ethics_declared_at =
                    now();
            }


            if (
                $request->boolean(
                    'confidentiality_declaration'
                )
                &&
                !$profile->confidentiality_declared_at
            ) {

                $profile->confidentiality_declared_at =
                    now();
            }


            /*
            |--------------------------------------------------------------------------
            | Completion
            |--------------------------------------------------------------------------
            */

            $profile->profile_completion_percentage =
                $this->calculateCompletion($profile);


            /*
            |--------------------------------------------------------------------------
            | Keep Workflow Status
            |--------------------------------------------------------------------------
            */

            if (!$profile->isUpdateRequested()) {

                $profile->approval_status =
                    'draft';
            }


            $profile->profile_completed =
                $profile->profile_completion_percentage >= 100;


            if (
                $profile->profile_completed
                &&
                !$profile->profile_completed_at
            ) {

                $profile->profile_completed_at =
                    now();
            }


            $profile->save();


            /*
            |--------------------------------------------------------------------------
            | Update Reviewer Name
            |--------------------------------------------------------------------------
            */

            $reviewerName = collect([
                $profile->title,
                $profile->first_name,
                $profile->middle_name,
                $profile->last_name,
            ])
                ->filter()
                ->implode(' ');


            if ($reviewerName) {

                $reviewer->update([
                    'name' =>
                        $reviewerName,
                ]);
            }


            DB::commit();


            return redirect()
                ->route(
                    'reviewer.application.edit'
                )
                ->with(
                    'success',
                    'Reviewer profile information saved successfully.'
                );

        } catch (\Throwable $exception) {

            DB::rollBack();

            report($exception);


            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to save reviewer profile. Please check the information and try again.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Submit Profile
    |--------------------------------------------------------------------------
    */

    public function submit(Request $request)
    {
        $reviewer = Auth::guard('reviewer')->user();

        abort_unless($reviewer, 401);


        $reviewer->load('profile');

        $profile = $reviewer->profile;


        if (!$profile) {

            return redirect()
                ->route(
                    'reviewer.application.edit'
                );
        }


        if ($profile->isPendingApproval()) {

            return redirect()
                ->route(
                    'reviewer.application.status'
                )
                ->with(
                    'warning',
                    'Your application has already been submitted.'
                );
        }


        if ($profile->isApproved()) {

            return redirect()
                ->route(
                    'reviewer.profile.show'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Required Profile Fields
        |--------------------------------------------------------------------------
        */

        $requiredFields = [

            'first_name' =>
                'First Name',

            'last_name' =>
                'Last Name',

            'mobile' =>
                'Mobile Number',

            'institution' =>
                'Institution / Organization',

            'department' =>
                'Department',

            'designation' =>
                'Current Designation',

            'highest_degree' =>
                'Highest Academic Degree',

            'specialization' =>
                'Specialization',

            'research_interests' =>
                'Research Interests',

            'areas_of_expertise' =>
                'Areas of Expertise',

            'expertise_keywords' =>
                'Research / Review Keywords',

            'cv_file' =>
                'Curriculum Vitae',
        ];


        $missing = [];


        foreach (
            $requiredFields
            as $field => $label
        ) {

            if (blank($profile->{$field})) {

                $missing[] = $label;
            }
        }


        if (!empty($missing)) {

            throw ValidationException::withMessages([
                'profile' =>
                    'Please complete the following information before submission: '
                    . implode(', ', $missing)
                    . '.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Declarations Required
        |--------------------------------------------------------------------------
        */

        if (!$profile->conflict_of_interest_declaration) {

            throw ValidationException::withMessages([
                'conflict_of_interest_declaration' =>
                    'Conflict of Interest Declaration is required.',
            ]);
        }


        if (!$profile->reviewer_ethics_declaration) {

            throw ValidationException::withMessages([
                'reviewer_ethics_declaration' =>
                    'Reviewer Ethics Declaration is required.',
            ]);
        }


        if (!$profile->confidentiality_declaration) {

            throw ValidationException::withMessages([
                'confidentiality_declaration' =>
                    'Confidentiality Declaration is required.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Submit
        |--------------------------------------------------------------------------
        */

        $profile->update([

            'profile_completed' =>
                true,

            'profile_completion_percentage' =>
                100,

            'profile_completed_at' =>
                $profile->profile_completed_at
                ?: now(),

            'approval_status' =>
                'pending_approval',

            'submitted_for_approval_at' =>
                now(),

            'update_requested_at' =>
                null,

            'last_profile_updated_at' =>
                now(),
        ]);


        return redirect()
            ->route(
                'reviewer.application.status'
            )
            ->with(
                'success',
                'Your reviewer application has been submitted to the BMRC Journal Editorial Office.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Application Status
    |--------------------------------------------------------------------------
    */

    public function status()
    {
        $reviewer = Auth::guard('reviewer')->user();

        abort_unless($reviewer, 401);


        $reviewer->load('profile');

        $profile = $reviewer->profile;


        if (!$profile) {

            return redirect()
                ->route(
                    'reviewer.application.edit'
                );
        }


        return view(
            'reviewer.application.status',
            compact(
                'reviewer',
                'profile'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Comma-separated Values
    |--------------------------------------------------------------------------
    */

    private function normalizeTextList(
        ?string $value
    ): ?string {

        if (blank($value)) {
            return null;
        }


        return collect(
            preg_split(
                '/[,;|]+/',
                $value
            )
        )
            ->map(
                fn ($item) =>
                    trim($item)
            )
            ->filter()
            ->unique(
                fn ($item) =>
                    mb_strtolower($item)
            )
            ->implode(', ');
    }


    /*
    |--------------------------------------------------------------------------
    | Completion
    |--------------------------------------------------------------------------
    */

    private function calculateCompletion(
        ReviewerProfile $profile
    ): int {

        $fields = [

            'first_name',
            'last_name',

            'mobile',

            'country',

            'institution',
            'department',
            'designation',

            'highest_degree',

            'specialization',

            'research_interests',

            'areas_of_expertise',

            'expertise_keywords',

            'cv_file',
        ];


        $completed =
            collect($fields)
                ->filter(
                    fn ($field) =>
                        filled(
                            $profile->{$field}
                        )
                )
                ->count();


        return (int) round(
            (
                $completed /
                count($fields)
            )
            * 100
        );
    }
}