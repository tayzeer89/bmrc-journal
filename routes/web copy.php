<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Internal Dashboard Controller
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\InternalDashboardController;



/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JournalController;
use App\Http\Controllers\ArticleTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TechnicalCheckController;
use App\Http\Controllers\Admin\ManuscriptController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\ReviewerController;

/*
|--------------------------------------------------------------------------
| Author Controllers
|--------------------------------------------------------------------------
*/


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
| Reviewer Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Reviewer\Auth\ReviewerAuthController;
use App\Http\Controllers\Reviewer\ReviewerApplicationController;

/*
|--------------------------------------------------------------------------
| Internal Authentication
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\LoginController;


/*
|--------------------------------------------------------------------------
| PUBLIC HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


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
        | Author Protected Area
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth')->group(function () {

            Route::post(
                '/logout',
                [AuthorAuthController::class, 'logout']
            )->name('logout');


            /*
            | Author Dashboard
            */

            Route::get(
                '/dashboard',
                [
                    AuthorDashboardController::class,
                    'index'
                ]
            )->name('dashboard');


            /*
            | Author Profile Step 1
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
            | Author Profile Step 2
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
            | Author Profile Step 3
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
            | General Author Profile
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



    Route::middleware(['auth'])
    ->prefix('author')
    ->group(function(){


        Route::get(
            '/submission/create',
            [SubmissionController::class,'create']
        )
        ->name('author.submission.create');



        Route::post(
            '/submission/step1',
            [SubmissionController::class,'storeStep1']
        )
        ->name('author.submission.step1.store');



        /*
        |--------------------------------------------------------------------------
        | Step 2 Show Form
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/submission/step2/{manuscript}',
            [SubmissionController::class,'step2']
        )
        ->name('author.submission.step2');



        /*
        |--------------------------------------------------------------------------
        | Step 2 Save Data
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/submission/step2/{manuscript}',
            [SubmissionController::class,'storeStep2']
        )
        ->name('author.submission.step2.store');


        /*
        |--------------------------------------------------------------------------
        | Step 3 - Authors
        |--------------------------------------------------------------------------
        */

        Route::get(
        '/submission/step3/{manuscript}',
        [SubmissionController::class,'step3']
        )
        ->name('author.submission.step3');


        Route::post(
        '/submission/step3/{manuscript}',
        [SubmissionController::class,'storeStep3']
        )
        ->name('author.submission.step3.store');


        /*
        |--------------------------------------------------------------------------
        | Step 4 Affiliation
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/submission/step4/{manuscript}',
            [SubmissionController::class,'step4']
        )
        ->name('author.submission.step4');


        Route::post(
            '/submission/step4/{manuscript}',
            [SubmissionController::class,'storeStep4']
        )
        ->name('author.submission.step4.store');




        /*
        |--------------------------------------------------------------------------
        | Step 5 Corresponding Author Declaration
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/author/submission/step5/{manuscript}',
            [SubmissionController::class,'step5']
        )
        ->name('author.submission.step5');



        Route::post(
            '/author/submission/step5/{manuscript}',
            [SubmissionController::class,'storeStep5']
        )
        ->name('author.submission.step5.store');



        /*
        |--------------------------------------------------------------------------
        | Step 6 - Manuscript File Upload
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/author/submission/step6/{manuscript}',
            [SubmissionController::class, 'step6']
        )
        ->name('author.submission.step6');


        Route::post(
            '/author/submission/step6/{manuscript}',
            [
                SubmissionController::class,
                'storeStep6'
            ]
        )
        ->name('author.submission.step6.store');


        /*
        |--------------------------------------------------------------------------
        | Step 7 - Ethical Information 
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/author/submission/step7/{manuscript}',
            [
                SubmissionController::class,
                'step7'
            ]
        )
        ->name('author.submission.step7');


        Route::post(
        '/author/submission/step7/{manuscript}',
        [SubmissionController::class,'storeStep7']
        )
        ->name('author.submission.step7.store');



        /*
        |--------------------------------------------------------------------------
        | Step 8 - Ethical Information Upload
        |--------------------------------------------------------------------------
        */
    
        Route::get(
            '/author/submission/step8/{manuscript}',
            [SubmissionController::class, 'step8']
        )->name('author.submission.step8');


        Route::post(
            '/author/submission/step8/{manuscript}',
            [SubmissionController::class, 'storeStep8']
        )->name('author.submission.step8.store');


         /*
        |--------------------------------------------------------------------------
        | Step 9 - Conflict of Interest
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/author/submission/step9/{manuscript}',
            [SubmissionController::class, 'step9']
        )
        ->name('author.submission.step9');


        Route::post(
            '/author/submission/step9/{manuscript}',
            [SubmissionController::class, 'storeStep9']
        )
        ->name('author.submission.step9.store');



        /*
        |--------------------------------------------------------------------------
        | Step 10 - Data Availability
        |--------------------------------------------------------------------------
        */

        Route::get(
        '/author/submission/step10/{id}',
        [SubmissionController::class,'step10']
        )
        ->name('author.submission.step10');



        Route::post(
        '/author/submission/step10/{id}',
        [SubmissionController::class,'storeStep10']
        )
        ->name('author.submission.step10.store');

         /*
        |--------------------------------------------------------------------------
        | Step 11 - Acknowledgement
        |--------------------------------------------------------------------------
        */
        
         Route::get(
        '/author/submission/step11/{id}',
        [SubmissionController::class,'step11']
        )
        ->name('author.submission.step11');



        Route::post(
        '/author/submission/step11/{id}',
        [SubmissionController::class,'storeStep11']
        )
        ->name('author.submission.step11.store');

        /*
        |--------------------------------------------------------------------------
        | Step 12 - Declaration Checklist
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/author/submission/{manuscript}/step12',
            [SubmissionController::class,'step12']
        )
        ->name('author.submission.step12');



        Route::post(
            '/author/submission/{manuscript}/step12',
            [SubmissionController::class,'storeStep12']
        )
        ->name('author.submission.step12.store');

        /*
        |--------------------------------------------------------------------------
        | Step 13 - Submission Confirmation
        |--------------------------------------------------------------------------
        */
            Route::get(
            '/author/submission/step13/{manuscript}',
            [SubmissionController::class,'step13']
            )->name('author.submission.step13');



            Route::post(
            '/submission/{manuscript}/final-submit',
            [
                SubmissionController::class,
                'finalSubmit'
            ]
            )
            ->name('author.submission.finalSubmit');

    });


Route::middleware(['auth'])
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
            [
                MyManuscriptController::class,
                'index'
            ]
        )->name('manuscripts.index');


        /*
        |--------------------------------------------------------------------------
        | Manuscript Details
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manuscripts/{manuscript}',
            [
                MyManuscriptController::class,
                'show'
            ]
        )->name('manuscripts.show');



        /*
        |--------------------------------------------------------------------------
        | Submit Technical Correction
        |--------------------------------------------------------------------------
        |
        | Author uses this route after Technical Review returns the
        | manuscript for correction.
        |
        */

        Route::post(
            '/manuscripts/{manuscript}/technical-correction',
            [
                TechnicalCorrectionController::class,
                'submit'
            ]
        )->name('manuscripts.technical-correction.submit');


       
        /*
        |--------------------------------------------------------------------------
        | Author Payment
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/payments',
            [
                AuthorPaymentController::class,
                'index'
            ]
        )->name('payments.index');

        Route::get(
            '/payments/{payment}',
            [
                AuthorPaymentController::class,
                'show'
            ]
        )->name('payments.show');

        Route::post(
            '/payments/{payment}/submit',
            [
                AuthorPaymentController::class,
                'submit'
            ]
        )->name('payments.submit');

    });

     /*
    |--------------------------------------------------------------------------
    | Draft Manuscript
    |--------------------------------------------------------------------------
    */

        Route::middleware(['auth'])
        ->prefix('author')
        ->name('author.')
        ->group(function(){


            Route::get(
                '/drafts',
                [
                    DraftSubmissionController::class,
                    'index'
                ]
            )
            ->name('drafts.index');



            Route::get(
                '/drafts/{id}/edit',
                [
                    DraftSubmissionController::class,
                    'edit'
                ]
            )
            ->name('drafts.edit');



            Route::delete(
                '/drafts/{id}',
                [
                    DraftSubmissionController::class,
                    'destroy'
                ]
            )
            ->name('drafts.destroy');




        });


        /*
        |--------------------------------------------------------------------------
        | SUBMITTED MANUSCRIPTS
        |--------------------------------------------------------------------------
        */

        Route::middleware(['auth'])
            ->prefix('author')
            ->name('author.')
            ->group(function(){


                Route::get(
                    '/submitted-manuscripts',
                    [
                        SubmittedManuscriptController::class,
                        'index'
                    ]
                )
                ->name('submitted.index');



                Route::get(
                    '/submitted-manuscripts/{manuscript}',
                    [
                        SubmittedManuscriptController::class,
                        'show'
                    ]
                )
                ->name('submitted.show');


            });



        /*
|--------------------------------------------------------------------------
| SUBMITTED MANUSCRIPTS / AUTHOR PAYMENTS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('author')
    ->name('author.')
    ->group(function () {

        Route::get(
            '/payments',
            [
                AuthorPaymentController::class,
                'index'
            ]
        )->name('payments.index');


        Route::get(
            '/payments/{payment}',
            [
                AuthorPaymentController::class,
                'show'
            ]
        )->name('payments.show');

    });


 /*
|--------------------------------------------------------------------------
| REVIEWER PORTAL
|--------------------------------------------------------------------------
*/

Route::prefix('reviewer')
    ->name('reviewer.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Reviewer Registration
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
        | Reviewer Login
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
        | Reviewer Protected Area
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth:reviewer')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Logout
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/logout',
                    [ReviewerAuthController::class, 'logout']
                )->name('logout');


                /*
                |--------------------------------------------------------------------------
                | Dashboard
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/dashboard',
                    [ReviewerAuthController::class, 'dashboard']
                )->name('dashboard');


                /*
                |--------------------------------------------------------------------------
                | Reviewer Profile / Application
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/application',
                    [ReviewerApplicationController::class, 'edit']
                )->name('application');

                Route::patch(
                    '/application',
                    [ReviewerApplicationController::class, 'update']
                )->name('application.update');


                /*
                |--------------------------------------------------------------------------
                | Submit Profile for Approval
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/application/submit',
                    [ReviewerApplicationController::class, 'submit']
                )->name('application.submit');


                /*
                |--------------------------------------------------------------------------
                | Application Status
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/application/status',
                    [ReviewerApplicationController::class, 'status']
                )->name('application.status');

            });
    });



/*
|--------------------------------------------------------------------------
| ADMIN / EDITORIAL REVIEWER MANAGEMENT
|--------------------------------------------------------------------------
|
| Used by:
| - Handling / Associate Editor
| - Editorial Officer / Journal Officer
| - Editor-in-Chief
|
*/

Route::middleware([
        'auth',
    ])
    ->prefix('admin/reviewers')
    ->name('admin.reviewers.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | REVIEWER LIST / POOL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [ReviewerManagementController::class, 'index']
        )
        ->middleware('permission:reviewer.view')
        ->name('index');


        /*
        |--------------------------------------------------------------------------
        | SEARCH REVIEWERS
        |--------------------------------------------------------------------------
        |
        | Handling / Associate Editor
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/search',
            [ReviewerManagementController::class, 'search']
        )
        ->middleware('permission:reviewer.search')
        ->name('search');


        /*
        |--------------------------------------------------------------------------
        | ADD NEW REVIEWER
        |--------------------------------------------------------------------------
        |
        | Editorial Officer / Journal Officer
        |
        | Only basic information:
        | - Name
        | - Email
        | - Mobile
        | - Institution
        | - Designation
        | - Country
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/create',
            [ReviewerManagementController::class, 'create']
        )
        ->middleware('permission:reviewer.create')
        ->name('create');


        Route::post(
            '/',
            [ReviewerManagementController::class, 'store']
        )
        ->middleware('permission:reviewer.create')
        ->name('store');


        /*
        |--------------------------------------------------------------------------
        | PROFILE INCOMPLETE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile-incomplete',
            [ReviewerManagementController::class, 'profileIncomplete']
        )
        ->middleware('permission:reviewer.view')
        ->name('profile-incomplete');


        /*
        |--------------------------------------------------------------------------
        | PENDING APPROVAL
        |--------------------------------------------------------------------------
        |
        | Editor-in-Chief
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pending',
            [ReviewerApprovalController::class, 'pending']
        )
        ->middleware('permission:reviewer.approve')
        ->name('pending');


        /*
        |--------------------------------------------------------------------------
        | UPDATE REQUESTED
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/update-requested',
            [ReviewerApprovalController::class, 'updateRequested']
        )
        ->middleware('permission:reviewer.approve')
        ->name('update-requested');


        /*
        |--------------------------------------------------------------------------
        | APPROVED REVIEWERS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/approved',
            [ReviewerManagementController::class, 'approved']
        )
        ->middleware('permission:reviewer.view')
        ->name('approved');


        /*
        |--------------------------------------------------------------------------
        | REJECTED REVIEWERS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/rejected',
            [ReviewerApprovalController::class, 'rejected']
        )
        ->middleware('permission:reviewer.approve')
        ->name('rejected');


        /*
        |--------------------------------------------------------------------------
        | SUSPENDED REVIEWERS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/suspended',
            [ReviewerManagementController::class, 'suspended']
        )
        ->middleware('permission:reviewer.suspend')
        ->name('suspended');


        /*
        |--------------------------------------------------------------------------
        | WORKLOAD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/workload',
            [ReviewerManagementController::class, 'workload']
        )
        ->middleware('permission:reviewer.performance.view')
        ->name('workload');


        /*
        |--------------------------------------------------------------------------
        | REVIEW HISTORY
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/review-history',
            [ReviewerManagementController::class, 'reviewHistory']
        )
        ->middleware('permission:reviewer.performance.view')
        ->name('review-history');


        /*
        |--------------------------------------------------------------------------
        | REVIEWER PERFORMANCE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/performance',
            [ReviewerManagementController::class, 'performance']
        )
        ->middleware('permission:reviewer.performance.view')
        ->name('performance');


        /*
        |--------------------------------------------------------------------------
        | SHOW REVIEWER
        |--------------------------------------------------------------------------
        |
        | Keep dynamic {reviewer} route after static routes such as
        | /approved, /pending, /search etc.
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{reviewer}',
            [ReviewerManagementController::class, 'show']
        )
        ->middleware('permission:reviewer.view')
        ->name('show');


        /*
        |--------------------------------------------------------------------------
        | EDIT REVIEWER BASIC INFORMATION
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{reviewer}/edit',
            [ReviewerManagementController::class, 'edit']
        )
        ->middleware('permission:reviewer.edit')
        ->name('edit');


        Route::patch(
            '/{reviewer}',
            [ReviewerManagementController::class, 'update']
        )
        ->middleware('permission:reviewer.edit')
        ->name('update');


        /*
        |--------------------------------------------------------------------------
        | REQUEST PROFILE UPDATE
        |--------------------------------------------------------------------------
        |
        | Editor-in-Chief
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{reviewer}/request-update',
            [ReviewerApprovalController::class, 'requestUpdate']
        )
        ->middleware('permission:reviewer.approve')
        ->name('request-update');


        /*
        |--------------------------------------------------------------------------
        | APPROVE REVIEWER
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{reviewer}/approve',
            [ReviewerApprovalController::class, 'approve']
        )
        ->middleware('permission:reviewer.approve')
        ->name('approve');


        /*
        |--------------------------------------------------------------------------
        | REJECT REVIEWER
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{reviewer}/reject',
            [ReviewerApprovalController::class, 'reject']
        )
        ->middleware('permission:reviewer.reject')
        ->name('reject');


        /*
        |--------------------------------------------------------------------------
        | SUSPEND REVIEWER ACCOUNT
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{reviewer}/suspend',
            [ReviewerManagementController::class, 'suspend']
        )
        ->middleware('permission:reviewer.suspend')
        ->name('suspend');


        /*
        |--------------------------------------------------------------------------
        | ACTIVATE / RESTORE REVIEWER
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/{reviewer}/activate',
            [ReviewerManagementController::class, 'activate']
        )
        ->middleware('permission:reviewer.suspend')
        ->name('activate');

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
        ->middleware('permission:reviewer.invite')
        ->name('index');


        Route::post(
            '/',
            [ReviewerInvitationController::class, 'store']
        )
        ->middleware('permission:reviewer.invite')
        ->name('store');


        Route::post(
            '/{invitation}/reminder',
            [ReviewerInvitationController::class, 'reminder']
        )
        ->middleware('permission:reviewer.invite')
        ->name('reminder');


        Route::post(
            '/{invitation}/cancel',
            [ReviewerInvitationController::class, 'cancel']
        )
        ->middleware('permission:reviewer.invite')
        ->name('cancel');

    });



/*
|--------------------------------------------------------------------------
| REVIEWER ASSIGNMENTS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin/reviewer-assignments')
    ->name('admin.reviewer-assignments.')
    ->group(function () {

        Route::get(
            '/',
            [ReviewerAssignmentController::class, 'index']
        )
        ->middleware('permission:reviewer.assign')
        ->name('index');

    });

/*
|--------------------------------------------------------------------------
| INTERNAL SYSTEM LOGIN
|--------------------------------------------------------------------------
|
| System Administrator
| Editorial Officer
| Editor-in-Chief
| Associate Editor
| Accounts Officer
| Copy Editor
| Proofreader
| Production/Web Administrator
| Journal Manager
|
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    /*
    | Login Page
    */

    Route::get(
        '/login',
        [LoginController::class, 'showLogin']
    )->name('login');


    /*
    | Login Submit
    */

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

Route::middleware('auth')->group(function () {

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
| SYSTEM ADMINISTRATOR
|--------------------------------------------------------------------------
|
| ONLY users with:
|
| system_administrator
|
| can access these routes.
|
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
    | Dashboard
    |--------------------------------------------------------------------------
    |
    | URL:
    | http://127.0.0.1:8000/admin/dashboard
    |
    | Route name:
    | admin.dashboard
    |
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
    | Article Type Status
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/article-types/{articleType}/toggle-status',
        [
            ArticleTypeController::class,
            'toggleStatus'
        ]
    )
    ->middleware(
        'permission:settings.manage'
    )
    ->name(
        'article-types.toggle-status'
    );

});


/*
|--------------------------------------------------------------------------
| INTERNAL ROLE DASHBOARDS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Common Dashboard Entry
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [InternalDashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Editorial Officer
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/editorial/dashboard',
        function () {
            return view('editorial.dashboard');
        }
    )->name('editorial.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Editor Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/editor/dashboard',
        function () {
            return view('editor.dashboard');
        }
    )->name('editor.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Assistant Editor Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/assistant/dashboard',
        function () {
            return view('assistant.dashboard');
        }
    )->name('assistant.dashboard');



/*
|--------------------------------------------------------------------------
| FINANCE / ACCOUNTS
|--------------------------------------------------------------------------
*/

    Route::middleware([
        'auth',
        'permission:finance.view',
    ])
    ->prefix('finance')
    ->name('finance.')
    ->group(function () {

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

    });

    /*
    |--------------------------------------------------------------------------
    | Copyediting Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/copyediting/dashboard',
        function () {
            return view('copyediting.dashboard');
        }
    )->name('copyediting.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Proofreading Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/proofreading/dashboard',
        function () {
            return view('proofreading.dashboard');
        }
    )->name('proofreading.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Production Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/production/dashboard',
        function () {
            return view('production.dashboard');
        }
    )->name('production.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Journal Manager Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/journal/dashboard',
        function () {
            return view('journal.dashboard');
        }
    )->name('journal.dashboard');

});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Manuscripts
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/manuscripts',
        [ManuscriptController::class, 'index']
    )
        ->name('manuscripts.index')
        ->middleware('permission:manuscript.view');


    /*
    |--------------------------------------------------------------------------
    | Technical Review
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/manuscripts/technical-review',
        [TechnicalCheckController::class, 'index']
    )
        ->name('manuscripts.technical-review.index')
        ->middleware('permission:technical_check.view');


    /*
    |--------------------------------------------------------------------------
    | Payment Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/manuscripts/payment',
        [PaymentController::class, 'index']
    )
        ->name('manuscripts.payment.index')
        ->middleware('permission:payment.view');


    Route::get(
        '/manuscripts/{manuscript}/payment/create',
        [PaymentController::class, 'create']
    )
        ->name('manuscripts.payment.create')
        ->middleware('permission:payment.create');


    Route::post(
        '/manuscripts/{manuscript}/payment',
        [PaymentController::class, 'store']
    )
        ->name('manuscripts.payment.store')
        ->middleware('permission:payment.create');


    /*
    |--------------------------------------------------------------------------
    | Manuscript Details
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/manuscripts/{manuscript}',
        [ManuscriptController::class, 'show']
    )
        ->name('manuscripts.show')
        ->middleware('permission:manuscript.view');


    /*
    |--------------------------------------------------------------------------
    | Technical Check
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/manuscripts/{manuscript}/technical-check',
        [TechnicalCheckController::class, 'show']
    )
        ->name('manuscripts.technical-check')
        ->middleware('permission:technical_check.view');

/*
    |--------------------------------------------------------------------------
    | Technical Check Actions
    |--------------------------------------------------------------------------
    */

    Route::put(
        '/technical-checks/{technicalCheck}',
        [TechnicalCheckController::class, 'update']
    )
        ->name('manuscripts.technical-check.update')
        ->middleware('permission:technical_check.perform');


    Route::post(
        '/technical-checks/{technicalCheck}/complete',
        [TechnicalCheckController::class, 'complete']
    )
        ->name('manuscripts.technical-check.complete')
        ->middleware('permission:technical_check.complete');


    Route::post(
        '/technical-checks/{technicalCheck}/return',
        [TechnicalCheckController::class, 'returnToAuthor']
    )
        ->name('manuscripts.technical-check.return')
        ->middleware('permission:technical_check.return');


    Route::post(
        '/technical-checks/{technicalCheck}/issues',
        [TechnicalCheckController::class, 'addIssue']
    )
        ->name('manuscripts.technical-check.issue')
        ->middleware('permission:technical_check.perform');




    Route::post(
        '/manuscripts/{manuscript}/technical-check/start',
        [TechnicalCheckController::class, 'start']
    )
        ->name('manuscripts.technical-check.start')
        ->middleware('permission:technical_check.perform');



/*
|--------------------------------------------------------------------------
| Payment Details
|--------------------------------------------------------------------------
*/

Route::get(
    '/payments/{payment}',
    [PaymentController::class, 'show']
)
    ->whereNumber('payment')
    ->name('payments.show')
    ->middleware('permission:payment.view');

Route::post(
    '/payments/{payment}/send-to-author',
    [PaymentController::class, 'sendToAuthor']
)
    ->whereNumber('payment')
    ->name('payments.send-to-author')
    ->middleware('permission:payment.send');


/*
|--------------------------------------------------------------------------
| Payment Verification
|--------------------------------------------------------------------------
*/

Route::prefix('payments')
    ->name('payments.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Verification Queue
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/verification',
            [PaymentVerificationController::class, 'index']
        )
            ->name('verification.index')
            ->middleware('permission:payment.verify');


        /*
        |--------------------------------------------------------------------------
        | Verification Details
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/verification/{payment}',
            [PaymentVerificationController::class, 'show']
        )
            ->whereNumber('payment')
            ->name('verification.show')
            ->middleware('permission:payment.verify');


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
            ->name('verification.verify')
            ->middleware('permission:payment.verify');


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
            ->name('verification.reject')
            ->middleware('permission:payment.verify');



           /*
            |--------------------------------------------------------------------------
            | Verified Payment History
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/verified',
                [PaymentVerificationController::class, 'verified']
            )
                ->name('verified')
                ->middleware('permission:payment.view');

    });

});

  