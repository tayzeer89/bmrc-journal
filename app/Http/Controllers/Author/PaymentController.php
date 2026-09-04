<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Author Payment List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $payments = Payment::whereHas(
            'manuscript',
            function ($query) {
                $query->where('submitted_by', Auth::id());
            }
        )
        ->with([
            'manuscript.articleType',
            'manuscript.journal',
        ])
        ->latest('id')
        ->paginate(15);

        return view(
            'author.payments.index',
            compact('payments')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Details
    |--------------------------------------------------------------------------
    */

    public function show(Payment $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        | Author can only view payment belonging to their own manuscript.
        */

        abort_unless(
            $payment->manuscript &&
            (int) $payment->manuscript->submitted_by === (int) Auth::id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Load Related Data
        |--------------------------------------------------------------------------
        */

        $payment->load([
            'manuscript.articleType',
            'manuscript.journal',
        ]);

        return view(
            'author.payments.show',
            compact('payment')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Submit Payment Information
    |--------------------------------------------------------------------------
    */

    public function submit(Request $request, Payment $payment)
    {
        $manuscript = $payment->manuscript;

        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $manuscript &&
            (int) $manuscript->submitted_by === (int) Auth::id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Payment Status Validation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $payment->payment_status === 'pending',
            403
        );

        abort_unless(
            $manuscript->status === 'payment_required',
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Payment Information
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'string',
                'max:100',
            ],

            'transaction_id' => [
                'required',
                'string',
                'max:255',
            ],

            'payer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'payer_mobile' => [
                'required',
                'string',
                'max:30',
            ],

            'payment_date' => [
                'required',
                'date',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Payment
        |--------------------------------------------------------------------------
        */

        $payment->update([
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'payer_name' => $validated['payer_name'],
            'payer_mobile' => $validated['payer_mobile'],
            'payment_date' => $validated['payment_date'],

            'payment_status' => 'submitted',
            'verification_status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Keep Manuscript in Payment Stage
        |--------------------------------------------------------------------------
        */

        $manuscript->update([
            'status' => 'payment_required',
            'current_stage' => 'payment',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'author.payments.show',
                $payment
            )
            ->with(
                'success',
                'Payment information submitted successfully. It is now awaiting verification.'
            );
    }
}