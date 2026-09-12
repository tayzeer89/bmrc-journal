<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Finance Officer Dashboard.
     */
    public function index()
    {
        $visibleModules = collect([

            [
                'title' => 'Payment Verification',
                'icon' => 'bi-credit-card-2-front',
                'route' => 'admin.payments.verification.index',
                'permission' => 'payment.verify',
            ],

            [
                'title' => 'Verified Payments',
                'icon' => 'bi-check-circle',
                'route' => 'admin.payments.verified',
                'permission' => 'payment.view',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Permission Based Module Visibility
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        $visibleModules = $visibleModules
            ->filter(function ($module) use ($user) {

                return $user->can($module['permission']);

            })
            ->values();


        return view(
            'finance.dashboard',
            compact('visibleModules')
        );
    }
}