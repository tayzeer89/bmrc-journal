<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ReviewerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewerApplicationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Reviewer Application
    |--------------------------------------------------------------------------
    */

    public function edit()
    {
        /*
        |--------------------------------------------------------------------------
        | Get Logged-in Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer = Auth::guard('reviewer')->user();

        if (!$reviewer) {
            abort(403, 'Reviewer authentication required.');
        }


        /*
        |--------------------------------------------------------------------------
        | Get Reviewer Profile
        |--------------------------------------------------------------------------
        */

        $profile = ReviewerProfile::where(
            'reviewer_id',
            $reviewer->id
        )->firstOrFail();


        return view(
            'reviewer.application.edit',
            compact('profile')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update / Submit Reviewer Application
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Logged-in Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer = Auth::guard('reviewer')->user();

        if (!$reviewer) {
            abort(403, 'Reviewer authentication required.');
        }


        /*
        |--------------------------------------------------------------------------
        | Get Reviewer Profile
        |--------------------------------------------------------------------------
        */

        $profile = ReviewerProfile::where(
            'reviewer_id',
            $reviewer->id
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            /*
            |------------------------------------------------------------------
            | Personal Information
            |------------------------------------------------------------------
            */

            'title' => [
                'nullable',
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

            'display_name' => [
                'required',
                'string',
                'max:200',
            ],


            /*
            |------------------------------------------------------------------
            | Contact Information
            |------------------------------------------------------------------
            */

            'alternative_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'mobile' => [
                'required',
                'string',
                'max:30',
            ],

            'preferred_communication_method' => [
                'nullable',
                'string',
                'max:100',
            ],


            /*
            |------------------------------------------------------------------
            | Personal Details
            |------------------------------------------------------------------
            */

            'gender' => [
                'nullable',
                'string',
                'max:30',
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
            |------------------------------------------------------------------
            | Location
            |------------------------------------------------------------------
            */

            'country' => [
                'required',
                'string',
                'max:100',
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

            'postal_address' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'office_address' => [
                'nullable',
                'string',
                'max:5000',
            ],


            /*
            |------------------------------------------------------------------
            | Professional Information
            |------------------------------------------------------------------
            */

            'institution' => [
                'required',
                'string',
                'max:255',
            ],

            'department' => [
                'required',
                'string',
                'max:255',
            ],

            'designation' => [
                'required',
                'string',
                'max:255',
            ],

            'academic_degree' => [
                'required',
                'string',
                'max:2000',
            ],

            'specialization' => [
                'required',
                'string',
                'max:3000',
            ],

            'professional_registration_no' => [
                'nullable',
                'string',
                'max:255',
            ],

            'research_interest' => [
                'required',
                'string',
                'max:5000',
            ],


            /*
            |------------------------------------------------------------------
            | Reviewer Expertise
            |------------------------------------------------------------------
            */

            'reviewer_expertise' => [
                'required',
                'string',
                'max:5000',
            ],

            'keywords' => [
                'required',
                'string',
                'max:3000',
            ],


            /*
            |------------------------------------------------------------------
            | Research Identifiers
            |------------------------------------------------------------------
            */

            'orcid' => [
                'nullable',
                'string',
                'max:100',
            ],

            'researcher_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'scopus_author_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'web_of_science_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'google_scholar_profile' => [
                'nullable',
                'url',
                'max:500',
            ],


            /*
            |------------------------------------------------------------------
            | CV
            |------------------------------------------------------------------
            */

            'cv_file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],


            /*
            |------------------------------------------------------------------
            | Availability
            |------------------------------------------------------------------
            */

            'available_for_review' => [
                'nullable',
                'boolean',
            ],


            /*
            |------------------------------------------------------------------
            | Declaration
            |------------------------------------------------------------------
            */

            'declaration' => [
                'required',
                'accepted',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Application ID
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | BMRC-REV-2026-000001
        |
        */

        if (empty($profile->application_id)) {

            do {

                $applicationId =
                    'BMRC-REV-' .
                    now()->format('Y') .
                    '-' .
                    str_pad(
                        (string) random_int(1, 999999),
                        6,
                        '0',
                        STR_PAD_LEFT
                    );

            } while (
                ReviewerProfile::where(
                    'application_id',
                    $applicationId
                )->exists()
            );


            $validated['application_id'] =
                $applicationId;
        }


        /*
        |--------------------------------------------------------------------------
        | CV Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('cv_file')) {

            /*
            |--------------------------------------------------------------------------
            | Delete Previous CV
            |--------------------------------------------------------------------------
            */

            if (
                !empty($profile->cv_file) &&
                Storage::disk('public')->exists(
                    $profile->cv_file
                )
            ) {

                Storage::disk('public')->delete(
                    $profile->cv_file
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Store New CV
            |--------------------------------------------------------------------------
            */

            $validated['cv_file'] =
                $request
                    ->file('cv_file')
                    ->store(
                        'reviewers/cv',
                        'public'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Reviewer Availability
        |--------------------------------------------------------------------------
        */

        $validated['available_for_review'] =
            $request->boolean(
                'available_for_review'
            );


        /*
        |--------------------------------------------------------------------------
        | Application Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] =
            'pending';

        $validated['profile_completed'] =
            true;

        $validated['applied_at'] =
            now();


        /*
        |--------------------------------------------------------------------------
        | Clear Previous Rejection
        |--------------------------------------------------------------------------
        */

        $validated['rejected_at'] =
            null;

        $validated['rejection_reason'] =
            null;


        /*
        |--------------------------------------------------------------------------
        | Save Reviewer Profile
        |--------------------------------------------------------------------------
        */

        $profile->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'reviewer.application.status'
            )
            ->with(
                'success',
                'Your reviewer application has been submitted successfully and is now awaiting BMRC approval.'
            );
    }


   /*
|--------------------------------------------------------------------------
| Application Status
|--------------------------------------------------------------------------
*/

public function status()
{
    /*
    |--------------------------------------------------------------------------
    | Get Logged-in Reviewer
    |--------------------------------------------------------------------------
    */

    $reviewer = Auth::guard('reviewer')->user();

    if (!$reviewer) {
        return redirect()
            ->route('reviewer.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Get Reviewer Profile
    |--------------------------------------------------------------------------
    */

    $reviewerProfile = ReviewerProfile::where(
        'reviewer_id',
        $reviewer->id
    )->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | Application Status View
    |--------------------------------------------------------------------------
    */

    return view(
        'reviewer.application.status',
        compact('reviewerProfile')
    );
}
}
