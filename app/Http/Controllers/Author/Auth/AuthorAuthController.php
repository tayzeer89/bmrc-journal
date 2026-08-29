<?php

namespace App\Http\Controllers\Author\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthorProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthorAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | AUTHOR REGISTRATION
    |--------------------------------------------------------------------------
    */

    /**
     * Show Author Registration Form
     */
    public function showRegister()
    {
        return view('author.auth.register');
    }


    /**
     * Register Author Account
     */
    public function register(Request $request)
    {
        $validated = $request->validate([

            'first_name' => [
                'required',
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
                'unique:users,email',
            ],

            'mobile' => [
                'required',
                'string',
                'max:30',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Create User
            |--------------------------------------------------------------------------
            */

            $user = User::create([

                'name' => trim(
                    $validated['first_name'] . ' ' .
                    $validated['last_name']
                ),

                'email' => $validated['email'],

                'user_type' => 'external',

                'password' => Hash::make(
                    $validated['password']
                ),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Generate Unique Author ID
            |--------------------------------------------------------------------------
            */

            do {

                $authorId = 'BMRC-AUTH-' .
                    strtoupper(Str::random(8));

            } while (
                AuthorProfile::where(
                    'author_id',
                    $authorId
                )->exists()
            );


            /*
            |--------------------------------------------------------------------------
            | Create Author Profile
            |--------------------------------------------------------------------------
            */

            AuthorProfile::create([

                'user_id' => $user->id,

                'author_id' => $authorId,

                'title' => null,

                'first_name' =>
                    $validated['first_name'],

                'middle_name' => null,

                'last_name' =>
                    $validated['last_name'],

                'display_name' =>
                    trim(
                        $validated['first_name'] .
                        ' ' .
                        $validated['last_name']
                    ),

                'alternative_email' => null,

                'mobile' =>
                    $validated['mobile'],

                'country' => 'Bangladesh',

                'institution' => null,

                'department' => null,

                'designation' => null,

                'profile_completed' => false,

            ]);


            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Login
            |--------------------------------------------------------------------------
            */

            Auth::login($user);

            $request->session()->regenerate();


            return redirect()
                ->route('author.dashboard')
                ->with(
                    'success',
                    'Author account created successfully. Please complete your profile.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'register' =>
                        'Unable to create author account. ' .
                        $e->getMessage(),
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHOR LOGIN
    |--------------------------------------------------------------------------
    */

    /**
     * Show Login Form
     */
    public function showLogin()
    {
        return view('author.auth.login');
    }


    /**
     * Login Author
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
            ],

        ]);


        $credentials['user_type'] = 'external';


        if (
            Auth::attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {

            $request->session()->regenerate();


            /*
            |--------------------------------------------------------------------------
            | Check Author Profile
            |--------------------------------------------------------------------------
            */

            $authorProfile = AuthorProfile::where(
                'user_id',
                Auth::id()
            )->first();


            if (!$authorProfile) {

                Auth::logout();

                return back()
                    ->withErrors([
                        'email' =>
                            'Author profile was not found.',
                    ])
                    ->onlyInput('email');
            }


            return redirect()
                ->intended(
                    route('author.dashboard')
                );
        }


        return back()
            ->withErrors([
                'email' =>
                    'The email or password is incorrect.',
            ])
            ->onlyInput('email');
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHOR LOGOUT
    |--------------------------------------------------------------------------
    */

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        return redirect()
            ->route('author.login')
            ->with(
                'success',
                'You have been logged out successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE STEP 1
    |--------------------------------------------------------------------------
    */

    /**
     * Show Minimum Information
     */
    public function step1()
    {
        $authorProfile = AuthorProfile::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        return view(
            'author.profile.step1',
            compact('authorProfile')
        );
    }


    /**
     * Update Minimum Information
     */
    public function step1Update(Request $request)
    {
        $authorProfile = AuthorProfile::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'title' => [
                'required',
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
                'required',
                'string',
                'max:200',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . Auth::id(),
            ],

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

            'country' => [
                'required',
                'string',
                'max:100',
            ],

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

        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Update Author Profile
            |--------------------------------------------------------------------------
            */

            $authorProfile->update([

                'title' =>
                    $validated['title'],

                'first_name' =>
                    $validated['first_name'],

                'middle_name' =>
                    $validated['middle_name'] ?? null,

                'last_name' =>
                    $validated['last_name'],

                'display_name' =>
                    $validated['display_name'],

                'alternative_email' =>
                    $validated['alternative_email'] ?? null,

                'mobile' =>
                    $validated['mobile'],

                'country' =>
                    $validated['country'],

                'institution' =>
                    $validated['institution'],

                'department' =>
                    $validated['department'],

                'designation' =>
                    $validated['designation'],

                'orcid' =>
                    $validated['orcid'] ?? null,

                'researcher_id' =>
                    $validated['researcher_id'] ?? null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Update User
            |--------------------------------------------------------------------------
            */

            $user = User::findOrFail(
                Auth::id()
            );


            $user->update([

                'name' =>
                    $validated['display_name'],

                'email' =>
                    $validated['email'],

            ]);


            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Continue Step 2
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('author.profile.step2')
                ->with(
                    'success',
                    'Minimum information saved successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'profile' =>
                        'Unable to save minimum information. ' .
                        $e->getMessage(),
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE STEP 2
    |--------------------------------------------------------------------------
    */

    /**
     * Show Personal Information
     */
    public function step2()
    {
        $authorProfile = AuthorProfile::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        return view(
            'author.profile.step2',
            compact('authorProfile')
        );
    }


    /**
     * Update Personal Information
     */
    public function step2Update(Request $request)
    {
        $authorProfile = AuthorProfile::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $validated = $request->validate([

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

            'division_state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'city_district' => [
                'nullable',
                'string',
                'max:100',
            ],

            'postal_address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'office_address' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);


        $authorProfile->update($validated);


        return redirect()
            ->route('author.profile.step3')
            ->with(
                'success',
                'Personal information saved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE STEP 3
    |--------------------------------------------------------------------------
    */

    /**
     * Show Professional Information
     */
    public function step3()
    {
        $authorProfile = AuthorProfile::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        return view(
            'author.profile.step3',
            compact('authorProfile')
        );
    }


    /**
     * Update Professional Information
     */
    public function step3Update(Request $request)
    {
        $authorProfile = AuthorProfile::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $validated = $request->validate([

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
                'nullable',
                'string',
                'max:1000',
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'professional_registration_no' => [
                'nullable',
                'string',
                'max:255',
            ],

            'research_interest' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'orcid' => [
                'nullable',
                'string',
                'max:100',
            ],

            'researcher_id' => [
                'nullable',
                'string',
                'max:255',
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
                'string',
                'max:2000',
            ],

            'profile_declaration' => [
                'required',
                'accepted',
            ],

            'action' => [
                'required',
                'in:save,complete',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Professional Information
        |--------------------------------------------------------------------------
        */

        $authorProfile->update([

            'institution' =>
                $validated['institution'],

            'department' =>
                $validated['department'],

            'designation' =>
                $validated['designation'],

            'academic_degree' =>
                $validated['academic_degree'] ?? null,

            'specialization' =>
                $validated['specialization'] ?? null,

            'professional_registration_no' =>
                $validated['professional_registration_no'] ?? null,

            'research_interest' =>
                $validated['research_interest'] ?? null,

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

        ]);


        /*
        |--------------------------------------------------------------------------
        | Save Only
        |--------------------------------------------------------------------------
        */

        if ($validated['action'] === 'save') {

            return redirect()
                ->route('author.profile.step3')
                ->with(
                    'success',
                    'Professional information saved successfully.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Complete Profile
        |--------------------------------------------------------------------------
        */

        $authorProfile->update([

            'profile_completed' => true,

        ]);


        return redirect()
            ->route('author.dashboard')
            ->with(
                'success',
                'Your Author Profile has been completed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERAL PROFILE
    |--------------------------------------------------------------------------
    */

    /**
     * Show General Profile
     */
    public function editProfile()
    {
        $authorProfile = AuthorProfile::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        return view(
            'author.profile.edit',
            compact('authorProfile')
        );
    }


    /**
     * Update General Profile
     */
    public function updateProfile(Request $request)
    {
        $authorProfile = AuthorProfile::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $validated = $request->validate([

            'title' => [
                'required',
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
                'required',
                'string',
                'max:200',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . Auth::id(),
            ],

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

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'division_state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'city_district' => [
                'nullable',
                'string',
                'max:100',
            ],

            'postal_address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'office_address' => [
                'nullable',
                'string',
                'max:1000',
            ],

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

            'academic_degree' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'professional_registration_no' => [
                'nullable',
                'string',
                'max:255',
            ],

            'research_interest' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'orcid' => [
                'nullable',
                'string',
                'max:100',
            ],

            'researcher_id' => [
                'nullable',
                'string',
                'max:255',
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
                'string',
                'max:2000',
            ],

        ]);


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Update Author Profile
            |--------------------------------------------------------------------------
            */

            $authorProfile->update([

                'title' =>
                    $validated['title'],

                'first_name' =>
                    $validated['first_name'],

                'middle_name' =>
                    $validated['middle_name'] ?? null,

                'last_name' =>
                    $validated['last_name'],

                'display_name' =>
                    $validated['display_name'],

                'alternative_email' =>
                    $validated['alternative_email'] ?? null,

                'mobile' =>
                    $validated['mobile'],

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

            ]);


            /*
            |--------------------------------------------------------------------------
            | Update User
            |--------------------------------------------------------------------------
            */

            $user = User::findOrFail(
                Auth::id()
            );


            $user->update([

                'name' =>
                    $validated['display_name'],

                'email' =>
                    $validated['email'],

            ]);


            DB::commit();


            return redirect()
                ->route('author.profile.edit')
                ->with(
                    'success',
                    'Author profile updated successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'profile' =>
                        'Unable to update profile. ' .
                        $e->getMessage(),
                ]);
        }
    }
}