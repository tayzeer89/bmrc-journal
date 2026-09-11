<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewerPaymentController extends Controller
{
    public function index()
    {
        $reviewer =
            Auth::guard('reviewer')
                ->user();

        if (!$reviewer) {

            return redirect()
                ->route(
                    'reviewer.login'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Later connect reviewer_payments table.
        |--------------------------------------------------------------------------
        */

        $payments =
            collect();

        $totalPaid = 0;

        $totalPending = 0;

        return view(
            'reviewer.payments.index',
            compact(
                'reviewer',
                'payments',
                'totalPaid',
                'totalPending'
            )
        );
    }
}