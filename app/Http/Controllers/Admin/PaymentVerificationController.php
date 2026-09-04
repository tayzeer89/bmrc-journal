<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentVerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Verification Queue
    |--------------------------------------------------------------------------
    */


    public function index()
    {
        abort_unless(
            auth()->user()->can('payment.verify'),
            403
        );

        $payments = Payment::query()
            ->where('payment_status', 'submitted')
            ->where('verification_status', 'pending')
            ->with([
                'manuscript.submitter',
                'manuscript.articleType',
                'manuscript.journal',
            ])
            ->latest('id')
            ->paginate(15);

        return view(
            'admin.payments.verification.index',
            compact('payments')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Verification Details
    |--------------------------------------------------------------------------
    */

    public function show(Payment $payment)
    {
        abort_unless(
            auth()->user()->can('payment.verify'),
            403
        );

        $payment->load([
            'manuscript.submitter',
            'manuscript.articleType',
            'manuscript.journal',
            'createdBy',
            'verifiedBy',
        ]);

        return view(
            'admin.payments.verification.show',
            compact('payment')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Verify Payment
    |--------------------------------------------------------------------------
    */

    public function verify(
        Request $request,
        Payment $payment
    ) {
        abort_unless(
            auth()->user()->can('payment.verify'),
            403
        );

        $validated = $request->validate([
            'verification_notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        DB::transaction(function () use (
            $payment,
            $validated
        ) {

            $payment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($payment->id);

            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate verification
            |--------------------------------------------------------------------------
            */

            if (
                $payment->payment_status !== 'submitted' ||
                $payment->verification_status !== 'pending'
            ) {
                abort(
                    422,
                    'This payment is no longer awaiting verification.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update Payment
            |--------------------------------------------------------------------------
            */

            $payment->update([
                'payment_status' => 'paid',

                'verification_status' => 'verified',

                'verified_by' => auth()->id(),

                'verified_at' => now(),

                'verification_notes' =>
                    $validated['verification_notes'] ?? null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Update Manuscript
            |--------------------------------------------------------------------------
            */

            $payment->manuscript()->update([
                'status' => 'payment_verified',

                'current_stage' => 'editorial_assessment',
            ]);
        });


        return redirect()
            ->route(
                'admin.payments.verification.show',
                $payment
            )
            ->with(
                'success',
                'Payment has been successfully verified.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Payment
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Payment $payment
    ) {
        abort_unless(
            auth()->user()->can('payment.verify'),
            403
        );

        $validated = $request->validate([
            'verification_notes' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);


        DB::transaction(function () use (
            $payment,
            $validated
        ) {

            $payment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($payment->id);

            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate rejection
            |--------------------------------------------------------------------------
            */

            if (
                $payment->payment_status !== 'submitted' ||
                $payment->verification_status !== 'pending'
            ) {
                abort(
                    422,
                    'This payment is no longer awaiting verification.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Return Payment to Author
            |--------------------------------------------------------------------------
            */

            $payment->update([

                // Important:
                // pending allows the author to submit again
                'payment_status' => 'pending',

                'verification_status' => 'rejected',

                'verified_by' => auth()->id(),

                'verified_at' => now(),

                'verification_notes' =>
                    $validated['verification_notes'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Return Manuscript to Payment Required
            |--------------------------------------------------------------------------
            */

            $payment->manuscript()->update([
                'status' => 'payment_required',

                'current_stage' => 'payment',
            ]);
        });


        return redirect()
            ->route(
                'admin.payments.verification.show',
                $payment
            )
            ->with(
                'success',
                'Payment has been rejected and returned to the author for correction.'
            );
    }
}