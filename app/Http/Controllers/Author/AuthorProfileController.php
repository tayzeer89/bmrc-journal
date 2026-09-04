<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\AuthorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthorProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW PROFILE
    |--------------------------------------------------------------------------
    */

    public function edit()
    {
        $user = Auth::user();

        $authorProfile = AuthorProfile::where(
            'user_id',
            $user->id
        )->firstOrFail();

        return view(
            'author.profile.edit',
            compact('authorProfile', 'user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE COMPLETE PROFILE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $user = Auth::user();

        $authorProfile = AuthorProfile::where(
            'user_id',
            $user->id
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // Minimum Information
            'title' => [
                'nullable',
                'string',
                'max:50'
            ],

            'first_name' => [
                'required',
                'string',
                'max:100'
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:100'
            ],

            'last_name' => [
                'required',
                'string',
                'max:100'
            ],

            'display_name' => [
                'required',
                'string',
                'max:255'
            ],

            'alternative_email' => [
                'nullable',
                'email',
                'max:255'
            ],

            'mobile' => [
                'required',
                'string',
                'max:30'
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id)
            ],


            // Personal Information
            'gender' => [
                'nullable',
                'string',
                'max:30'
            ],

            'date_of_birth' => [
                'nullable',
                'date'
            ],

            'nationality' => [
                'nullable',
                'string',
                'max:100'
            ],

            'country' => [
                'required',
                'string',
                'max:100'
            ],

            'division_state' => [
                'nullable',
                'string',
                'max:100'
            ],

            'city_district' => [
                'nullable',
                'string',
                'max:100'
            ],

            'postal_address' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'office_address' => [
                'nullable',
                'string',
                'max:1000'
            ],


            // Professional Information
            'institution' => [
                'nullable',
                'string',
                'max:255'
            ],

            'department' => [
                'nullable',
                'string',
                'max:255'
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255'
            ],

            'academic_degree' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'professional_registration_no' => [
                'nullable',
                'string',
                'max:255'
            ],

            'research_interest' => [
                'nullable',
                'string',
                'max:3000'
            ],


            // Research IDs
            'orcid' => [
                'nullable',
                'string',
                'max:100'
            ],

            'researcher_id' => [
                'nullable',
                'string',
                'max:100'
            ],

            'scopus_author_id' => [
                'nullable',
                'string',
                'max:100'
            ],

            'web_of_science_id' => [
                'nullable',
                'string',
                'max:100'
            ],

            'google_scholar_profile' => [
                'nullable',
                'string',
                'max:1000'
            ],


            // Communication
            'preferred_communication_method' => [
                'nullable',
                'string',
                'max:50'
            ],

            'available_for_editorial_communication' => [
                'nullable',
                'boolean'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE AUTHOR PROFILE TABLE
        |--------------------------------------------------------------------------
        */

        $authorProfile->update([

            // Minimum Information
            'title' => $validated['title'] ?? null,

            'first_name' => $validated['first_name'],

            'middle_name' => $validated['middle_name'] ?? null,

            'last_name' => $validated['last_name'],

            'display_name' => $validated['display_name'],

            'alternative_email' =>
                $validated['alternative_email'] ?? null,

            'mobile' => $validated['mobile'],


            // Personal Information
            'gender' =>
                $validated['gender'] ?? null,

            'date_of_birth' =>
                $validated['date_of_birth'] ?? null,

            'nationality' =>
                $validated['nationality'] ?? null,

            'country' =>
                $validated['country'],

            'division_state' =>
                $validated['division_state'] ?? null,

            'city_district' =>
                $validated['city_district'] ?? null,

            'postal_address' =>
                $validated['postal_address'] ?? null,

            'office_address' =>
                $validated['office_address'] ?? null,


            // Professional Information
            'institution' =>
                $validated['institution'] ?? null,

            'department' =>
                $validated['department'] ?? null,

            'designation' =>
                $validated['designation'] ?? null,

            'academic_degree' =>
                $validated['academic_degree'] ?? null,

            'specialization' =>
                $validated['specialization'] ?? null,

            'professional_registration_no' =>
                $validated['professional_registration_no'] ?? null,

            'research_interest' =>
                $validated['research_interest'] ?? null,


            // Research IDs
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


            // Communication
            'preferred_communication_method' =>
                $validated['preferred_communication_method'] ?? null,

            'available_for_editorial_communication' =>
                $request->boolean(
                    'available_for_editorial_communication'
                ),

        ]);


        /*
        |--------------------------------------------------------------------------
        | CALCULATE PROFILE COMPLETION
        |--------------------------------------------------------------------------
        |
        | These fields determine the Profile Completion percentage.
        |
        */

        $completionFields = [

            'first_name',
            'last_name',
            'display_name',
            'mobile',
            'country',
            'institution',
            'department',
            'designation',
            'orcid',
            'research_interest',

        ];


        /*
        |--------------------------------------------------------------------------
        | COUNT COMPLETED FIELDS
        |--------------------------------------------------------------------------
        */

        $completedFields = 0;

        foreach ($completionFields as $field) {

            if (
                filled(
                    $authorProfile->getAttribute($field)
                )
            ) {
                $completedFields++;
            }

        }


        /*
        |--------------------------------------------------------------------------
        | CALCULATE PERCENTAGE
        |--------------------------------------------------------------------------
        */

        $profileCompletion = count($completionFields) > 0
            ? round(
                ($completedFields / count($completionFields)) * 100
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | SAVE PROFILE COMPLETION
        |--------------------------------------------------------------------------
        */

        $authorProfile->update([
            'profile_completed' => $profileCompletion,
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE USERS TABLE
        |--------------------------------------------------------------------------
        */

        $user->update([

            'name' => $validated['display_name'],

            'email' => $validated['email'],

        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('author.profile.edit')
            ->with(
                'success',
                'Author profile updated successfully.'
            );
    }
}
