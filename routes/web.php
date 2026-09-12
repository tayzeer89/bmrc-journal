<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| COMMON / INTERNAL CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternalDashboardController;
use App\Http\Controllers\Auth\LoginController;


/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JournalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TechnicalCheckController;
use App\Http\Controllers\Admin\ManuscriptController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\ReviewerController;
use App\Http\Controllers\Admin\ReviewerRequestController;
use App\Http\Controllers\Admin\ReviewerInvitationController;
use App\Http\Controllers\Admin\ReviewerAssignmentController;

use App\Http\Controllers\ArticleTypeController;
use App\Http\Controllers\Admin\JournalPageController;

use App\Http\Controllers\Website\JournalPageController
    as PublicJournalPageController;
/*
|--------------------------------------------------------------------------
| Center Dashboard Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\DashboardRedirectController;

use App\Http\Controllers\Editor\DashboardController
    as EditorDashboardController;
use App\Http\Controllers\Finance\DashboardController
    as FinanceDashboardController;

/*
|--------------------------------------------------------------------------
| AUTHOR CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Author\Auth\AuthorForgotPasswordController;
use App\Http\Controllers\Author\Auth\AuthorResetPasswordController;

use App\Http\Controllers\Author\Auth\AuthorAuthController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\SubmissionController;
use App\Http\Controllers\Author\DraftSubmissionController;
use App\Http\Controllers\Author\MyManuscriptController;
use App\Http\Controllers\Author\SubmittedManuscriptController;
use App\Http\Controllers\Author\PaymentController as AuthorPaymentController;
use App\Http\Controllers\Author\TechnicalCorrectionController;


/*
|--------------------------------------------------------------------------
| REVIEWER CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Reviewer\Auth\ReviewerForgotPasswordController;
use App\Http\Controllers\Reviewer\Auth\ReviewerResetPasswordController;

use App\Http\Controllers\Reviewer\Auth\ReviewerAuthController;
use App\Http\Controllers\Reviewer\ReviewerDashboardController;
use App\Http\Controllers\Reviewer\ReviewerApplicationController;
use App\Http\Controllers\Reviewer\ReviewerPasswordController;
use App\Http\Controllers\Reviewer\ReviewerProfileController;

use App\Http\Controllers\Reviewer\ReviewerInvitationController
    as ReviewerPortalInvitationController;

use App\Http\Controllers\Reviewer\ReviewerReviewController
    as ReviewerPortalReviewController;

use App\Http\Controllers\Reviewer\ReviewerPaymentController
    as ReviewerPortalPaymentController;
/*
|--------------------------------------------------------------------------
| PUBLIC WEBSITE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');



/*
|--------------------------------------------------------------------------
| PUBLIC JOURNAL WEBSITE PAGES
|--------------------------------------------------------------------------
*/

Route::get(
    '/page/{slug}',
    [PublicJournalPageController::class, 'show']
)->name('journal.page');



/*
|--------------------------------------------------------------------------
| Public CMS Page
|--------------------------------------------------------------------------
*/

Route::get(
    '/page/{slug}',
    [
        PublicJournalPageController::class,
        'show',
    ]
)->name('journal.page');


/*
|--------------------------------------------------------------------------
| Journal Current Issue
|--------------------------------------------------------------------------
*/

Route::get(
    '/current-issue',
    function () {

        return view(
            'website.journal.current-issue'
        );

    }
)->name('journal.current-issue');


/*
|--------------------------------------------------------------------------
| Journal Archive
|--------------------------------------------------------------------------
*/

Route::get(
    '/journal-archive',
    function () {

        return view(
            'website.journal.archive'
        );

    }
)->name('journal.archive');





/*
|--------------------------------------------------------------------------
| AUTHOR PORTAL
|--------------------------------------------------------------------------
*/
Route::prefix('author')
    ->name('author.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Author Registration
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/register',
            [AuthorAuthController::class, 'showRegister']
        )->name('register');

        Route::post(
            '/register',
            [AuthorAuthController::class, 'register']
        )->name('register.submit');


        /*
        |--------------------------------------------------------------------------
        | Author Login
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/login',
            [AuthorAuthController::class, 'showLogin']
        )->name('login');

        Route::post(
            '/login',
            [AuthorAuthController::class, 'login']
        )->name('login.submit');


        /*
        |--------------------------------------------------------------------------
        | Author Forgot Password
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/forgot-password',
            [AuthorForgotPasswordController::class, 'create']
        )->name('password.request');

        Route::post(
            '/forgot-password',
            [AuthorForgotPasswordController::class, 'store']
        )->name('password.email');


        /*
        |--------------------------------------------------------------------------
        | Author Reset Password
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reset-password/{token}',
            [AuthorResetPasswordController::class, 'create']
        )->name('password.reset');

        Route::post(
            '/reset-password',
            [AuthorResetPasswordController::class, 'store']
        )->name('password.store');


        /*
        |--------------------------------------------------------------------------
        | Protected Author Area
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Logout
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/logout',
                    [AuthorAuthController::class, 'logout']
                )->name('logout');


                /*
                |--------------------------------------------------------------------------
                | Dashboard
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/dashboard',
                    [AuthorDashboardController::class, 'index']
                )->name('dashboard');


                /*
                |--------------------------------------------------------------------------
                | Author Profile - Step 1
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/profile/step-1',
                    [AuthorAuthController::class, 'step1']
                )->name('profile.step1');

                Route::patch(
                    '/profile/step-1',
                    [AuthorAuthController::class, 'step1Update']
                )->name('profile.step1.update');


                /*
                |--------------------------------------------------------------------------
                | Author Profile - Step 2
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/profile/step-2',
                    [AuthorAuthController::class, 'step2']
                )->name('profile.step2');

                Route::patch(
                    '/profile/step-2',
                    [AuthorAuthController::class, 'step2Update']
                )->name('profile.step2.update');


                /*
                |--------------------------------------------------------------------------
                | Author Profile - Step 3
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/profile/step-3',
                    [AuthorAuthController::class, 'step3']
                )->name('profile.step3');

                Route::patch(
                    '/profile/step-3',
                    [AuthorAuthController::class, 'step3Update']
                )->name('profile.step3.update');


                /*
                |--------------------------------------------------------------------------
                | General Author Profile
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/profile',
                    [AuthorAuthController::class, 'editProfile']
                )->name('profile.edit');

                Route::patch(
                    '/profile',
                    [AuthorAuthController::class, 'updateProfile']
                )->name('profile.update');
            });
    });

/*
|--------------------------------------------------------------------------
| AUTHOR - NEW ARTICLE SUBMISSION
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('author/submission')
    ->name('author.submission.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Step 1 - Basic Manuscript Information
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/create',
            [SubmissionController::class, 'create']
        )->name('create');

        Route::post(
            '/step1',
            [SubmissionController::class, 'storeStep1']
        )->name('step1.store');


        /*
        |--------------------------------------------------------------------------
        | Step 2
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step2/{manuscript}',
            [SubmissionController::class, 'step2']
        )
            ->whereNumber('manuscript')
            ->name('step2');

        Route::post(
            '/step2/{manuscript}',
            [SubmissionController::class, 'storeStep2']
        )
            ->whereNumber('manuscript')
            ->name('step2.store');


        /*
        |--------------------------------------------------------------------------
        | Step 3 - Authors
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step3/{manuscript}',
            [SubmissionController::class, 'step3']
        )
            ->whereNumber('manuscript')
            ->name('step3');

        Route::post(
            '/step3/{manuscript}',
            [SubmissionController::class, 'storeStep3']
        )
            ->whereNumber('manuscript')
            ->name('step3.store');


        /*
        |--------------------------------------------------------------------------
        | Step 4 - Affiliations
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step4/{manuscript}',
            [SubmissionController::class, 'step4']
        )
            ->whereNumber('manuscript')
            ->name('step4');

        Route::post(
            '/step4/{manuscript}',
            [SubmissionController::class, 'storeStep4']
        )
            ->whereNumber('manuscript')
            ->name('step4.store');


        /*
        |--------------------------------------------------------------------------
        | Step 5 - Corresponding Author Declaration
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step5/{manuscript}',
            [SubmissionController::class, 'step5']
        )
            ->whereNumber('manuscript')
            ->name('step5');

        Route::post(
            '/step5/{manuscript}',
            [SubmissionController::class, 'storeStep5']
        )
            ->whereNumber('manuscript')
            ->name('step5.store');


        /*
        |--------------------------------------------------------------------------
        | Step 6 - Manuscript File Upload
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step6/{manuscript}',
            [SubmissionController::class, 'step6']
        )
            ->whereNumber('manuscript')
            ->name('step6');

        Route::post(
            '/step6/{manuscript}',
            [SubmissionController::class, 'storeStep6']
        )
            ->whereNumber('manuscript')
            ->name('step6.store');


        /*
        |--------------------------------------------------------------------------
        | Step 7 - Ethical Information
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step7/{manuscript}',
            [SubmissionController::class, 'step7']
        )
            ->whereNumber('manuscript')
            ->name('step7');

        Route::post(
            '/step7/{manuscript}',
            [SubmissionController::class, 'storeStep7']
        )
            ->whereNumber('manuscript')
            ->name('step7.store');


        /*
        |--------------------------------------------------------------------------
        | Step 8 - Ethical Documents
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step8/{manuscript}',
            [SubmissionController::class, 'step8']
        )
            ->whereNumber('manuscript')
            ->name('step8');

        Route::post(
            '/step8/{manuscript}',
            [SubmissionController::class, 'storeStep8']
        )
            ->whereNumber('manuscript')
            ->name('step8.store');


        /*
        |--------------------------------------------------------------------------
        | Step 9 - Conflict of Interest
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step9/{manuscript}',
            [SubmissionController::class, 'step9']
        )
            ->whereNumber('manuscript')
            ->name('step9');

        Route::post(
            '/step9/{manuscript}',
            [SubmissionController::class, 'storeStep9']
        )
            ->whereNumber('manuscript')
            ->name('step9.store');


        /*
        |--------------------------------------------------------------------------
        | Step 10 - Data Availability
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step10/{manuscript}',
            [SubmissionController::class, 'step10']
        )
            ->whereNumber('manuscript')
            ->name('step10');

        Route::post(
            '/step10/{manuscript}',
            [SubmissionController::class, 'storeStep10']
        )
            ->whereNumber('manuscript')
            ->name('step10.store');


        /*
        |--------------------------------------------------------------------------
        | Step 11 - Acknowledgement
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step11/{manuscript}',
            [SubmissionController::class, 'step11']
        )
            ->whereNumber('manuscript')
            ->name('step11');

        Route::post(
            '/step11/{manuscript}',
            [SubmissionController::class, 'storeStep11']
        )
            ->whereNumber('manuscript')
            ->name('step11.store');


        /*
        |--------------------------------------------------------------------------
        | Step 12 - Declaration Checklist
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step12/{manuscript}',
            [SubmissionController::class, 'step12']
        )
            ->whereNumber('manuscript')
            ->name('step12');

        Route::post(
            '/step12/{manuscript}',
            [SubmissionController::class, 'storeStep12']
        )
            ->whereNumber('manuscript')
            ->name('step12.store');


        /*
        |--------------------------------------------------------------------------
        | Step 13 - Submission Confirmation
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/step13/{manuscript}',
            [SubmissionController::class, 'step13']
        )
            ->whereNumber('manuscript')
            ->name('step13');


        /*
        |--------------------------------------------------------------------------
        | Final Submit
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{manuscript}/final-submit',
            [SubmissionController::class, 'finalSubmit']
        )
            ->whereNumber('manuscript')
            ->name('finalSubmit');
    });


/*
|--------------------------------------------------------------------------
| AUTHOR - MANUSCRIPT MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('author')
    ->name('author.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | My Manuscripts
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manuscripts',
            [MyManuscriptController::class, 'index']
        )->name('manuscripts.index');

        Route::get(
            '/manuscripts/{manuscript}',
            [MyManuscriptController::class, 'show']
        )
            ->whereNumber('manuscript')
            ->name('manuscripts.show');


        /*
        |--------------------------------------------------------------------------
        | Technical Correction
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/manuscripts/{manuscript}/technical-correction',
            [TechnicalCorrectionController::class, 'submit']
        )
            ->whereNumber('manuscript')
            ->name('manuscripts.technical-correction.submit');


        /*
        |--------------------------------------------------------------------------
        | Draft Manuscripts
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/drafts',
            [DraftSubmissionController::class, 'index']
        )->name('drafts.index');

        Route::get(
            '/drafts/{id}/edit',
            [DraftSubmissionController::class, 'edit']
        )
            ->whereNumber('id')
            ->name('drafts.edit');

        Route::delete(
            '/drafts/{id}',
            [DraftSubmissionController::class, 'destroy']
        )
            ->whereNumber('id')
            ->name('drafts.destroy');


        /*
        |--------------------------------------------------------------------------
        | Submitted Manuscripts
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/submitted-manuscripts',
            [SubmittedManuscriptController::class, 'index']
        )->name('submitted.index');

        Route::get(
            '/submitted-manuscripts/{manuscript}',
            [SubmittedManuscriptController::class, 'show']
        )
            ->whereNumber('manuscript')
            ->name('submitted.show');


        /*
        |--------------------------------------------------------------------------
        | Author Payments
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/payments',
            [AuthorPaymentController::class, 'index']
        )->name('payments.index');

        Route::get(
            '/payments/{payment}',
            [AuthorPaymentController::class, 'show']
        )
            ->whereNumber('payment')
            ->name('payments.show');

        Route::post(
            '/payments/{payment}/submit',
            [AuthorPaymentController::class, 'submit']
        )
            ->whereNumber('payment')
            ->name('payments.submit');
    });


/*
|--------------------------------------------------------------------------
| REVIEWER PORTAL
|--------------------------------------------------------------------------
|
| Separate reviewer authentication system.
|
| Flow:
|
| Register
|    ↓
| Reviewer account created
|    ↓
| Complete Reviewer Application
|    ↓
| Pending Editorial Approval
|    ↓
| Approved
|    ↓
| Reviewer Dashboard
|
|--------------------------------------------------------------------------
*/

Route::prefix('reviewer')
    ->name('reviewer.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | GUEST REVIEWER ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware('guest:reviewer')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | REGISTRATION
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/register',
                    [ReviewerAuthController::class, 'showRegister']
                )->name('register');

                Route::post(
                    '/register',
                    [ReviewerAuthController::class, 'register']
                )->name('register.submit');


                /*
                |--------------------------------------------------------------------------
                | LOGIN
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/login',
                    [ReviewerAuthController::class, 'showLogin']
                )->name('login');

                Route::post(
                    '/login',
                    [ReviewerAuthController::class, 'login']
                )->name('login.submit');


                /*
                |--------------------------------------------------------------------------
                | FORGOT PASSWORD
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/forgot-password',
                    [ReviewerForgotPasswordController::class, 'create']
                )->name('password.request');

                Route::post(
                    '/forgot-password',
                    [ReviewerForgotPasswordController::class, 'store']
                )->name('password.email');


                /*
                |--------------------------------------------------------------------------
                | RESET PASSWORD
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/reset-password/{token}',
                    [ReviewerResetPasswordController::class, 'create']
                )->name('password.reset');

                Route::post(
                    '/reset-password',
                    [ReviewerResetPasswordController::class, 'store']
                )->name('password.store');

            });


        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATED REVIEWER ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth:reviewer')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | LOGOUT
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/logout',
                    [ReviewerAuthController::class, 'logout']
                )->name('logout');


                /*
                |--------------------------------------------------------------------------
                | PASSWORD CHANGE
                |--------------------------------------------------------------------------
                |
                | This is different from Forgot Password.
                |
                | Forgot Password:
                | guest reviewer -> email reset link
                |
                | Password Change:
                | authenticated reviewer -> change current password
                |
                | Keep this outside reviewer.approved middleware.
                |
                */

                Route::get(
                    '/password/change',
                    [ReviewerPasswordController::class, 'edit']
                )->name('password.change');

                Route::patch(
                    '/password/change',
                    [ReviewerPasswordController::class, 'update']
                )->name('password.update');


                /*
                |--------------------------------------------------------------------------
                | REVIEWER APPLICATION
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/application',
                    [ReviewerApplicationController::class, 'edit']
                )->name('application.edit');

                Route::patch(
                    '/application',
                    [ReviewerApplicationController::class, 'update']
                )->name('application.update');

                Route::post(
                    '/application/submit',
                    [ReviewerApplicationController::class, 'submit']
                )->name('application.submit');


                /*
                |--------------------------------------------------------------------------
                | APPLICATION STATUS
                |--------------------------------------------------------------------------
                |
                | Possible profile approval statuses:
                |
                | draft
                | pending_approval
                | update_requested
                | approved
                | rejected
                |
                */

                Route::get(
                    '/application/status',
                    [ReviewerApplicationController::class, 'status']
                )->name('application.status');


                /*
                |--------------------------------------------------------------------------
                | REVIEWER PROFILE
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/profile',
                    [ReviewerProfileController::class, 'show']
                )->name('profile.show');


                /*
                |--------------------------------------------------------------------------
                | REVIEWER DASHBOARD
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/dashboard',
                    [ReviewerDashboardController::class, 'index']
                )->name('dashboard');


                /*
                |--------------------------------------------------------------------------
                | APPROVED REVIEWER ONLY
                |--------------------------------------------------------------------------
                |
                | Requirements:
                |
                | reviewers.status = approved
                | reviewer_profiles.approval_status = approved
                |
                */

                Route::middleware('reviewer.approved')
                    ->group(function () {

                        /*
                        |--------------------------------------------------------------------------
                        | REVIEW INVITATIONS
                        |--------------------------------------------------------------------------
                        */

                        Route::get(
                            '/invitations',
                            [
                                ReviewerPortalInvitationController::class,
                                'index'
                            ]
                        )->name('invitations.index');


                        /*
                        |--------------------------------------------------------------------------
                        | ACTIVE REVIEWS
                        |--------------------------------------------------------------------------
                        */

                        Route::get(
                            '/reviews/active',
                            [
                                ReviewerPortalReviewController::class,
                                'active'
                            ]
                        )->name('reviews.active');


                        /*
                        |--------------------------------------------------------------------------
                        | COMPLETED REVIEWS
                        |--------------------------------------------------------------------------
                        */

                        Route::get(
                            '/reviews/completed',
                            [
                                ReviewerPortalReviewController::class,
                                'completed'
                            ]
                        )->name('reviews.completed');


                        /*
                        |--------------------------------------------------------------------------
                        | REVIEW HISTORY
                        |--------------------------------------------------------------------------
                        */

                        Route::get(
                            '/reviews/history',
                            [
                                ReviewerPortalReviewController::class,
                                'history'
                            ]
                        )->name('reviews.history');


                        /*
                        |--------------------------------------------------------------------------
                        | REVIEWER PAYMENTS
                        |--------------------------------------------------------------------------
                        */

                        Route::get(
                            '/payments',
                            [
                                ReviewerPortalPaymentController::class,
                                'index'
                            ]
                        )->name('payments.index');

                    }); // reviewer.approved

            }); // auth:reviewer

    }); // reviewer prefix
/*
|--------------------------------------------------------------------------
| INTERNAL SYSTEM AUTHENTICATION
|--------------------------------------------------------------------------
|
| Internal roles:
|
| - System Administrator
| - Editorial Officer
| - Journal Officer
| - Assistant Editor
| - Associate / Handling Editor
| - Editor-in-Chief
| - Accounts / Finance Officer
| - Copy Editor
| - Proofreader
| - Production Administrator
| - Journal Manager
|
|--------------------------------------------------------------------------
*/

Route::middleware('guest')
    ->group(function () {

        Route::get(
            '/login',
            [LoginController::class, 'showLogin']
        )->name('login');

        Route::post(
            '/login',
            [LoginController::class, 'login']
        )->name('login.submit');
    });


/*
|--------------------------------------------------------------------------
| INTERNAL LOGOUT
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [LoginController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| INTERNAL USER PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        Route::get(
            '/profile',
            [ProfileController::class, 'edit']
        )->name('profile.edit');

        Route::patch(
            '/profile',
            [ProfileController::class, 'update']
        )->name('profile.update');

        Route::delete(
            '/profile',
            [ProfileController::class, 'destroy']
        )->name('profile.destroy');
    });


/*
|--------------------------------------------------------------------------
| INTERNAL ROLE DASHBOARDS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Common Dynamic Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [InternalDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Editorial Officer Dashboard
        |--------------------------------------------------------------------------
        */

        Route::view(
            '/editorial/dashboard',
            'editorial.dashboard'
        )->name('editorial.dashboard');





        /*
        |--------------------------------------------------------------------------
        | Assistant Editor Dashboard
        |--------------------------------------------------------------------------
        */

        Route::view(
            '/assistant/dashboard',
            'assistant.dashboard'
        )->name('assistant.dashboard');


        /*
        |--------------------------------------------------------------------------
        | Copyediting Dashboard
        |--------------------------------------------------------------------------
        */

        Route::view(
            '/copyediting/dashboard',
            'copyediting.dashboard'
        )->name('copyediting.dashboard');


        /*
        |--------------------------------------------------------------------------
        | Proofreading Dashboard
        |--------------------------------------------------------------------------
        */

        Route::view(
            '/proofreading/dashboard',
            'proofreading.dashboard'
        )->name('proofreading.dashboard');


        /*
        |--------------------------------------------------------------------------
        | Production Dashboard
        |--------------------------------------------------------------------------
        */

        Route::view(
            '/production/dashboard',
            'production.dashboard'
        )->name('production.dashboard');


        /*
        |--------------------------------------------------------------------------
        | Journal Manager Dashboard
        |--------------------------------------------------------------------------
        */

        Route::view(
            '/journal/dashboard',
            'journal.dashboard'
        )->name('journal.dashboard');
    });


/*
|--------------------------------------------------------------------------
| SYSTEM ADMINISTRATOR
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:system_administrator',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'users',
            UserController::class
        )
            ->middleware(
                'permission:user.view|user.create|user.edit|user.delete'
            );


        /*
        |--------------------------------------------------------------------------
        | Role Management
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'roles',
            RoleController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Journal Management
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'journals',
            JournalController::class
        )
            ->middleware(
                'permission:settings.manage'
            );


        /*
        |--------------------------------------------------------------------------
        | Article Type - Toggle Status
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/article-types/{articleType}/toggle-status',
            [
                ArticleTypeController::class,
                'toggleStatus'
            ]
        )
            ->whereNumber('articleType')
            ->middleware(
                'permission:settings.manage'
            )
            ->name(
                'article-types.toggle-status'
            );


        /*
        |--------------------------------------------------------------------------
        | Article Type Management
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'article-types',
            ArticleTypeController::class
        )
            ->middleware(
                'permission:settings.manage'
            );


        /*
        |--------------------------------------------------------------------------
        | Journal Website Content Management
        |--------------------------------------------------------------------------
        |
        | Only System Administrator can access these routes because this
        | resource is inside the role:system_administrator route group.
        |
        */

        Route::resource(
            'journal-pages',
            JournalPageController::class
        );



    });


/*
|--------------------------------------------------------------------------
| ADMIN / EDITORIAL REVIEWER MANAGEMENT
|--------------------------------------------------------------------------
|
| Reviewer self-service belongs to:
|
| /reviewer/...
|
| Internal reviewer management belongs to:
|
| /admin/reviewers/...
|
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('admin/reviewers')
    ->name('admin.reviewers.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Reviewer List / Pool
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [ReviewerController::class, 'index']
        )
            ->middleware('permission:reviewer.view')
            ->name('index');


        /*
        |--------------------------------------------------------------------------
        | Add Reviewer
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/create',
            [ReviewerController::class, 'create']
        )
            ->middleware('permission:reviewer.create')
            ->name('create');

        Route::post(
            '/',
            [ReviewerController::class, 'store']
        )
            ->middleware('permission:reviewer.create')
            ->name('store');


        /*
        |--------------------------------------------------------------------------
        | Static Reviewer Status Routes
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile-incomplete',
            [ReviewerController::class, 'profileIncomplete']
        )
            ->middleware('permission:reviewer.view')
            ->name('profile-incomplete');


        Route::get(
            '/pending',
            [ReviewerController::class, 'pending']
        )
            ->middleware('permission:reviewer.view')
            ->name('pending');


        Route::get(
            '/update-requested',
            [ReviewerController::class, 'updateRequested']
        )
            ->middleware('permission:reviewer.view')
            ->name('update-requested');


        Route::get(
            '/approved',
            [ReviewerController::class, 'approved']
        )
            ->middleware('permission:reviewer.view')
            ->name('approved');


        Route::get(
            '/rejected',
            [ReviewerController::class, 'rejected']
        )
            ->middleware('permission:reviewer.view')
            ->name('rejected');


        Route::get(
            '/suspended',
            [ReviewerController::class, 'suspended']
        )
            ->middleware('permission:reviewer.view')
            ->name('suspended');


        /*
        |--------------------------------------------------------------------------
        | Reviewer Search
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/search',
            [ReviewerController::class, 'search']
        )
            ->middleware('permission:reviewer.search')
            ->name('search');


        /*
        |--------------------------------------------------------------------------
        | Reviewer Workload
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/workload',
            [ReviewerController::class, 'workload']
        )
            ->middleware('permission:reviewer.workload.view')
            ->name('workload');


        /*
        |--------------------------------------------------------------------------
        | Reviewer History
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/review-history',
            [ReviewerController::class, 'reviewHistory']
        )
            ->middleware('permission:reviewer.history.view')
            ->name('review-history');


        /*
        |--------------------------------------------------------------------------
        | Reviewer Performance
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/performance',
            [ReviewerController::class, 'performance']
        )
            ->middleware('permission:reviewer.performance.view')
            ->name('performance');


        /*
        |--------------------------------------------------------------------------
        | Reviewer Approval
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{reviewer}/approve',
            [ReviewerController::class, 'approve']
        )
            ->whereNumber('reviewer')
            ->middleware('permission:reviewer.approve')
            ->name('approve');


        /*
        |--------------------------------------------------------------------------
        | Reviewer Rejection
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{reviewer}/reject',
            [ReviewerController::class, 'reject']
        )
            ->whereNumber('reviewer')
            ->middleware('permission:reviewer.reject')
            ->name('reject');


        /*
        |--------------------------------------------------------------------------
        | Request Reviewer Update
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{reviewer}/request-update',
            [ReviewerController::class, 'requestUpdate']
        )
            ->whereNumber('reviewer')
            ->middleware('permission:reviewer.request_update')
            ->name('request-update');


        /*
        |--------------------------------------------------------------------------
        | Suspend Reviewer
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{reviewer}/suspend',
            [ReviewerController::class, 'suspend']
        )
            ->whereNumber('reviewer')
            ->middleware('permission:reviewer.suspend')
            ->name('suspend');


        /*
        |--------------------------------------------------------------------------
        | Activate Reviewer
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/{reviewer}/activate',
            [ReviewerController::class, 'activate']
        )
            ->whereNumber('reviewer')
            ->middleware('permission:reviewer.activate')
            ->name('activate');


        /*
        |--------------------------------------------------------------------------
        | Edit Reviewer
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{reviewer}/edit',
            [ReviewerController::class, 'edit']
        )
            ->whereNumber('reviewer')
            ->middleware('permission:reviewer.edit')
            ->name('edit');


        Route::put(
            '/{reviewer}',
            [ReviewerController::class, 'update']
        )
            ->whereNumber('reviewer')
            ->middleware('permission:reviewer.edit')
            ->name('update');


        /*
        |--------------------------------------------------------------------------
        | Reviewer Details
        |--------------------------------------------------------------------------
        |
        | Dynamic route LAST.
        |
        */

        Route::get(
            '/{reviewer}',
            [ReviewerController::class, 'show']
        )
            ->whereNumber('reviewer')
            ->middleware('permission:reviewer.view')
            ->name('show');
    });


/*
|--------------------------------------------------------------------------
| REVIEWER INVITATION MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('admin/reviewer-invitations')
    ->name('admin.reviewer-invitations.')
    ->group(function () {

        Route::get(
            '/',
            [ReviewerInvitationController::class, 'index']
        )
            ->middleware('permission:reviewer.invitation.view')
            ->name('index');

        Route::get(
            '/create',
            [ReviewerInvitationController::class, 'create']
        )
            ->middleware('permission:reviewer.invite')
            ->name('create');

        Route::post(
            '/',
            [ReviewerInvitationController::class, 'store']
        )
            ->middleware('permission:reviewer.invite')
            ->name('store');

        Route::get(
            '/{invitation}',
            [ReviewerInvitationController::class, 'show']
        )
            ->whereNumber('invitation')
            ->middleware('permission:reviewer.invitation.view')
            ->name('show');

        Route::patch(
            '/{invitation}/remind',
            [ReviewerInvitationController::class, 'remind']
        )
            ->whereNumber('invitation')
            ->middleware('permission:reviewer.invitation.remind')
            ->name('remind');

        Route::patch(
            '/{invitation}/cancel',
            [ReviewerInvitationController::class, 'cancel']
        )
            ->whereNumber('invitation')
            ->middleware('permission:reviewer.invitation.cancel')
            ->name('cancel');
    });


        /*
    |--------------------------------------------------------------------------
    | REVIEWER REQUESTS
    |--------------------------------------------------------------------------
    */

        Route::middleware('auth')
            ->prefix('admin/reviewers/requests')
            ->name('admin.reviewers.requests.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Reviewer Request List
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [ReviewerRequestController::class, 'index']
                )
                    ->middleware('permission:reviewer.request')
                    ->name('index');


                /*
                |--------------------------------------------------------------------------
                | Create Reviewer Request
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/create',
                    [ReviewerRequestController::class, 'create']
                )
                    ->middleware('permission:reviewer.request')
                    ->name('create');


                /*
                |--------------------------------------------------------------------------
                | Store Reviewer Request
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/',
                    [ReviewerRequestController::class, 'store']
                )
                    ->middleware('permission:reviewer.request')
                    ->name('store');


                /*
                |--------------------------------------------------------------------------
                | View Reviewer Request
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/{reviewerRequest}',
                    [ReviewerRequestController::class, 'show']
                )
                    ->whereNumber('reviewerRequest')
                    ->middleware('permission:reviewer.request')
                    ->name('show');
            });
    
/*
|--------------------------------------------------------------------------
| REVIEWER ASSIGNMENT MANAGEMENT
|--------------------------------------------------------------------------
|
| Assistant Editor / Handling Editor assigns reviewers
| to submitted manuscripts.
|
*/

Route::middleware('auth')
    ->prefix('admin/reviewer-assignments')
    ->name('admin.reviewer-assignments.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Assignment Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [ReviewerAssignmentController::class, 'index']
        )
        ->middleware('permission:reviewer.assign')
        ->name('index');

        /*
        |--------------------------------------------------------------------------
        | Pending Manuscripts for Assignment
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pending',
            [ReviewerAssignmentController::class, 'pending']
        )
        ->middleware('permission:reviewer.assign')
        ->name('pending');

        /*
        |--------------------------------------------------------------------------
        | Assignment Form
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/create/{manuscript}',
            [ReviewerAssignmentController::class, 'create']
        )
        ->whereNumber('manuscript')
        ->middleware('permission:reviewer.assign')
        ->name('create');

        /*
        |--------------------------------------------------------------------------
        | Save Assignment
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [ReviewerAssignmentController::class, 'store']
        )
        ->middleware('permission:reviewer.assign')
        ->name('store');

        /*
        |--------------------------------------------------------------------------
        | View Assignment
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{assignment}',
            [ReviewerAssignmentController::class, 'show']
        )
        ->whereNumber('assignment')
        ->middleware('permission:reviewer.assign')
        ->name('show');

        /*
        |--------------------------------------------------------------------------
        | Update Assignment
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{assignment}/edit',
            [ReviewerAssignmentController::class, 'edit']
        )
        ->whereNumber('assignment')
        ->middleware('permission:reviewer.assign')
        ->name('edit');

        Route::put(
            '/{assignment}',
            [ReviewerAssignmentController::class, 'update']
        )
        ->whereNumber('assignment')
        ->middleware('permission:reviewer.assign')
        ->name('update');

        /*
        |--------------------------------------------------------------------------
        | Remove Reviewer Assignment
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/{assignment}',
            [ReviewerAssignmentController::class, 'destroy']
        )
        ->whereNumber('assignment')
        ->middleware('permission:reviewer.assign')
        ->name('destroy');

    });



/*
|--------------------------------------------------------------------------
| INTERNAL MANUSCRIPT MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Manuscript List
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manuscripts',
            [ManuscriptController::class, 'index']
        )
            ->middleware(
                'permission:manuscript.view'
            )
            ->name('manuscripts.index');


        /*
        |--------------------------------------------------------------------------
        | Technical Review Queue
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manuscripts/technical-review',
            [TechnicalCheckController::class, 'index']
        )
            ->middleware(
                'permission:technical_check.view'
            )
            ->name(
                'manuscripts.technical-review.index'
            );


        /*
        |--------------------------------------------------------------------------
        | Payment Request Queue
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manuscripts/payment',
            [PaymentController::class, 'index']
        )
            ->middleware(
                'permission:payment.view'
            )
            ->name(
                'manuscripts.payment.index'
            );


        /*
        |--------------------------------------------------------------------------
        | Create Payment Request
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manuscripts/{manuscript}/payment/create',
            [PaymentController::class, 'create']
        )
            ->whereNumber('manuscript')
            ->middleware(
                'permission:payment.create'
            )
            ->name(
                'manuscripts.payment.create'
            );

        Route::post(
            '/manuscripts/{manuscript}/payment',
            [PaymentController::class, 'store']
        )
            ->whereNumber('manuscript')
            ->middleware(
                'permission:payment.create'
            )
            ->name(
                'manuscripts.payment.store'
            );


        /*
        |--------------------------------------------------------------------------
        | Technical Check - Details
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manuscripts/{manuscript}/technical-check',
            [TechnicalCheckController::class, 'show']
        )
            ->whereNumber('manuscript')
            ->middleware(
                'permission:technical_check.view'
            )
            ->name(
                'manuscripts.technical-check'
            );


        /*
        |--------------------------------------------------------------------------
        | Start Technical Check
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/manuscripts/{manuscript}/technical-check/start',
            [TechnicalCheckController::class, 'start']
        )
            ->whereNumber('manuscript')
            ->middleware(
                'permission:technical_check.perform'
            )
            ->name(
                'manuscripts.technical-check.start'
            );


        /*
        |--------------------------------------------------------------------------
        | Update Technical Check
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/technical-checks/{technicalCheck}',
            [TechnicalCheckController::class, 'update']
        )
            ->whereNumber('technicalCheck')
            ->middleware(
                'permission:technical_check.perform'
            )
            ->name(
                'manuscripts.technical-check.update'
            );


        /*
        |--------------------------------------------------------------------------
        | Complete Technical Check
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/technical-checks/{technicalCheck}/complete',
            [TechnicalCheckController::class, 'complete']
        )
            ->whereNumber('technicalCheck')
            ->middleware(
                'permission:technical_check.complete'
            )
            ->name(
                'manuscripts.technical-check.complete'
            );


        /*
        |--------------------------------------------------------------------------
        | Return Manuscript to Author
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/technical-checks/{technicalCheck}/return',
            [TechnicalCheckController::class, 'returnToAuthor']
        )
            ->whereNumber('technicalCheck')
            ->middleware(
                'permission:technical_check.return'
            )
            ->name(
                'manuscripts.technical-check.return'
            );


        /*
        |--------------------------------------------------------------------------
        | Add Technical Issue
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/technical-checks/{technicalCheck}/issues',
            [TechnicalCheckController::class, 'addIssue']
        )
            ->whereNumber('technicalCheck')
            ->middleware(
                'permission:technical_check.perform'
            )
            ->name(
                'manuscripts.technical-check.issue'
            );


        /*
        |--------------------------------------------------------------------------
        | Manuscript Details
        |--------------------------------------------------------------------------
        |
        | Keep after static / workflow routes.
        |
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manuscripts/{manuscript}',
            [ManuscriptController::class, 'show']
        )
            ->whereNumber('manuscript')
            ->middleware(
                'permission:manuscript.view'
            )
            ->name(
                'manuscripts.show'
            );
    });
















/*
|--------------------------------------------------------------------------
| INTERNAL PAYMENT MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin/payments')
    ->name('admin.payments.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Payment Verification Queue
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/verification',
            [PaymentVerificationController::class, 'index']
        )
            ->middleware(
                'permission:payment.verify'
            )
            ->name(
                'verification.index'
            );


        /*
        |--------------------------------------------------------------------------
        | Verified Payment History
        |--------------------------------------------------------------------------
        |
        | Static route is intentionally before /{payment}.
        |
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/verified',
            [PaymentVerificationController::class, 'verified']
        )
            ->middleware(
                'permission:payment.view'
            )
            ->name('verified');


        /*
        |--------------------------------------------------------------------------
        | Payment Verification Details
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/verification/{payment}',
            [PaymentVerificationController::class, 'show']
        )
            ->whereNumber('payment')
            ->middleware(
                'permission:payment.verify'
            )
            ->name(
                'verification.show'
            );


        /*
        |--------------------------------------------------------------------------
        | Verify Payment
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/verification/{payment}/verify',
            [PaymentVerificationController::class, 'verify']
        )
            ->whereNumber('payment')
            ->middleware(
                'permission:payment.verify'
            )
            ->name(
                'verification.verify'
            );


        /*
        |--------------------------------------------------------------------------
        | Reject Payment
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/verification/{payment}/reject',
            [PaymentVerificationController::class, 'reject']
        )
            ->whereNumber('payment')
            ->middleware(
                'permission:payment.verify'
            )
            ->name(
                'verification.reject'
            );


        /*
        |--------------------------------------------------------------------------
        | Send Payment Request to Author
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{payment}/send-to-author',
            [PaymentController::class, 'sendToAuthor']
        )
            ->whereNumber('payment')
            ->middleware(
                'permission:payment.send'
            )
            ->name(
                'send-to-author'
            );


        /*
        |--------------------------------------------------------------------------
        | Payment Details
        |--------------------------------------------------------------------------
        |
        | Keep dynamic route at bottom.
        |
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{payment}',
            [PaymentController::class, 'show']
        )
            ->whereNumber('payment')
            ->middleware(
                'permission:payment.view'
            )
            ->name('show');
    });

/*
|--------------------------------------------------------------------------
| Center dashboard redirect route
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/dashboard',
        [DashboardRedirectController::class, 'index']
    )->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| FINANCE / ACCOUNTS
|--------------------------------------------------------------------------
*/

Route::prefix('finance')
    ->name('finance.')
    ->middleware([
        'auth',
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Finance Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [FinanceDashboardController::class, 'index']
        )->name('dashboard');

    });


/*
|--------------------------------------------------------------------------
| EDITOR
|--------------------------------------------------------------------------
*/

Route::prefix('editor')
    ->name('editor.')
    ->middleware([
        'auth',
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Editor Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [EditorDashboardController::class, 'index']
        )->name('dashboard');

    });

/*
|--------------------------------------------------------------------------
| FUTURE WORKFLOW MODULES
|--------------------------------------------------------------------------
|
| Add future routes here in workflow order:
|
| 1. Similarity Check
| 2. Editor Assignment
| 3. Editorial Assessment
| 4. Reviewer Selection
| 5. Reviewer Invitation
| 6. Peer Review
| 7. Editorial Recommendation
| 8. Editor-in-Chief Decision
| 9. Author Revision
| 10. Re-review
| 11. Final Decision
| 12. Copyediting
| 13. Proofreading
| 14. Publication
|
|--------------------------------------------------------------------------
*/