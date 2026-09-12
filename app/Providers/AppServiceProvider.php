<?php

namespace App\Providers;

use App\Models\JournalPage;
use App\Models\Payment;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ADMIN LAYOUT
        |--------------------------------------------------------------------------
        | Pending payment verification counter.
        |--------------------------------------------------------------------------
        */

        View::composer(
            'layouts.admin',
            function ($view) {

                $pendingPaymentVerificationCount = 0;


                /*
                |--------------------------------------------------------------------------
                | Prevent query if payments table does not exist
                |--------------------------------------------------------------------------
                */

                if (
                    Schema::hasTable('payments') &&
                    auth()->check() &&
                    auth()->user()->can('payment.verify')
                ) {

                    $pendingPaymentVerificationCount =
                        Payment::query()
                            ->where(
                                'payment_status',
                                'submitted'
                            )
                            ->where(
                                'verification_status',
                                'pending'
                            )
                            ->count();
                }


                $view->with(
                    'pendingPaymentVerificationCount',
                    $pendingPaymentVerificationCount
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PUBLIC JOURNAL WEBSITE NAVIGATION
        |--------------------------------------------------------------------------
        |
        | This provides journalMenus automatically to:
        |
        | welcome.blade.php
        | resources/views/website/*
        |
        | Only published pages with show_in_menu = true
        | will appear on the public website.
        |
        |--------------------------------------------------------------------------
        */

        View::composer(
            [
                'welcome',
                'website.*',
            ],
            function ($view) {

                /*
                |--------------------------------------------------------------------------
                | Empty collection by default
                |--------------------------------------------------------------------------
                */

                $journalMenus = collect();


                /*
                |--------------------------------------------------------------------------
                | Prevent database error before migration
                |--------------------------------------------------------------------------
                */

                if (
                    Schema::hasTable(
                        'journal_pages'
                    )
                ) {

                    $journalMenus =
                        JournalPage::query()
                            ->published()
                            ->menuVisible()

                            /*
                            |--------------------------------------------------------------------------
                            | Menu Group Priority
                            |--------------------------------------------------------------------------
                            */

                            ->orderByRaw("
                                FIELD(
                                    menu_group,
                                    'about',
                                    'editorial_board',
                                    'journal',
                                    'authors',
                                    'reviewers'
                                )
                            ")

                            /*
                            |--------------------------------------------------------------------------
                            | Page Order
                            |--------------------------------------------------------------------------
                            */

                            ->orderBy(
                                'sort_order'
                            )

                            ->orderBy(
                                'title'
                            )

                            ->get()

                            ->groupBy(
                                'menu_group'
                            );
                }


                /*
                |--------------------------------------------------------------------------
                | Share with public views
                |--------------------------------------------------------------------------
                */

                $view->with(
                    'journalMenus',
                    $journalMenus
                );
            }
        );
    }
}