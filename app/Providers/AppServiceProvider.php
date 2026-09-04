<?php

namespace App\Providers;

use App\Models\Payment;
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
        View::composer(
            'layouts.admin',
            function ($view) {

                $pendingPaymentVerificationCount = 0;

                if (
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
    }
}