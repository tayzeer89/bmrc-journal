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

/*
|--------------------------------------------------------------------------
| Author Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Author\Auth\AuthorAuthController;

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
                function () {
                    return view('author.dashboard');
                }
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

        Route::middleware('auth:reviewer')->group(function () {

            /*
            | Reviewer Logout
            */

            Route::post(
                '/logout',
                [ReviewerAuthController::class, 'logout']
            )->name('logout');


            /*
            | Reviewer Application
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
            | Reviewer Application Status
            */

            Route::get(
                '/application/status',
                [ReviewerApplicationController::class, 'status']
            )->name('application.status');


            /*
            | Reviewer Dashboard
            */

            Route::get(
                '/dashboard',
                [ReviewerAuthController::class, 'dashboard']
            )->name('dashboard');

        });
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
    | Finance Dashboard
    |--------------------------------------------------------------------------
    */

    // Route::get(
    //     '/finance/dashboard',
    //     function () {
    //         return view('finance.dashboard');
    //     }
    // )->name('finance.dashboard');

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
