<?php

namespace App\Http\Controllers\Author;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Payment;

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
            function($query){

                $query->where(
                    'submitted_by',
                    Auth::id()
                );

            }
        )
        ->latest()
        ->get();



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


        return view(
            'author.payments.show',
            compact('payment')
        );


    }



}