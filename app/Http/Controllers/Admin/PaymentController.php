<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Payment Setup Queue
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        abort_unless(
            auth()->user()->can('payment.view'),
            403
        );

        $manuscripts = Manuscript::query()
            ->where('status', 'payment_setup')
            ->with([
                'latestPayment',
            ])
            ->latest('id')
            ->paginate(15);

        return view(
            'admin.payments.index',
            compact('manuscripts')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Payment Invoice
    |--------------------------------------------------------------------------
    */

    public function create(Manuscript $manuscript)
    {
        abort_unless(
            auth()->user()->can('payment.create'),
            403
        );

        abort_unless(
            $manuscript->status === 'payment_setup',
            404
        );

        return view(
            'admin.payments.create',
            compact('manuscript')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Payment Invoice
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Manuscript $manuscript
    ) {
        abort_unless(
            auth()->user()->can('payment.create'),
            403
        );

        abort_unless(
            $manuscript->status === 'payment_setup',
            404
        );

        $validated = $request->validate([
            'fee_type' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:3',
            ],

            'invoice_date' => [
                'required',
                'date',
            ],

            'payment_deadline' => [
                'nullable',
                'date',
                'after_or_equal:invoice_date',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $payment = DB::transaction(function () use (
            $validated,
            $manuscript
        ) {

            $lastPaymentNumber = $manuscript
                ->payments()
                ->count() + 1;

            $invoiceNo = sprintf(
                'BMRC-INV-%s-%05d',
                now()->format('Y'),
                $manuscript->id
            );

            if ($lastPaymentNumber > 1) {
                $invoiceNo .= '-' . $lastPaymentNumber;
            }

            return $manuscript->payments()->create([

                'invoice_no' => $invoiceNo,

                'fee_type' => $validated['fee_type'],

                'amount' => $validated['amount'],

                'currency' => strtoupper(
                    $validated['currency']
                ),

                'invoice_date' => $validated['invoice_date'],

                'payment_deadline' =>
                    $validated['payment_deadline'] ?? null,

                'payment_status' => 'pending',

                'verification_status' => 'pending',

                'created_by' => auth()->id(),

                'remarks' =>
                    $validated['remarks'] ?? null,
            ]);
        });

        return redirect()
            ->route(
                'admin.payments.show',
                $payment
            )
            ->with(
                'success',
                'Payment invoice created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Payment
    |--------------------------------------------------------------------------
    */

    public function show(Payment $payment)
    {
        abort_unless(
            auth()->user()->can('payment.view'),
            403
        );

        $payment->load([
            'manuscript',
            'createdBy',
            'verifiedBy',
        ]);

        return view(
            'admin.payments.show',
            compact('payment')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Send Payment Request to Author
    |--------------------------------------------------------------------------
    */

    public function sendToAuthor(Payment $payment)
    {
        abort_unless(
            auth()->user()->can('payment.send'),
            403
        );

        $manuscript = $payment->manuscript;

        abort_unless(
            $manuscript,
            404
        );

        if ($payment->sent_to_author_at) {
            return back()->with(
                'warning',
                'This payment request has already been sent to the author.'
            );
        }

        DB::transaction(function () use (
            $payment,
            $manuscript
        ) {

            $payment->update([
                'sent_to_author_at' => now(),
                'payment_status' => 'pending',
            ]);

            $manuscript->update([
                'status' => 'payment_required',
                'current_stage' => 'payment',
            ]);
        });

        return redirect()
            ->route(
                'admin.payments.show',
                $payment
            )
            ->with(
                'success',
                'Payment request has been sent to the author.'
            );
    }
}