<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewerRequest;
use Illuminate\Http\Request;

class ReviewerRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Reviewer Request List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = ReviewerRequest::query()
            ->with([
                'requester',
                'processor',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $requests = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.reviewers.requests.index',
            compact('requests')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.reviewers.requests.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'institution' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reason' => [
                'required',
                'string',
                'max:3000',
            ],
        ]);

        ReviewerRequest::create([
            'name' => $validated['name'],

            'email' => $validated['email'],

            'institution' =>
                $validated['institution'] ?? null,

            'designation' =>
                $validated['designation'] ?? null,

            'specialization' =>
                $validated['specialization'] ?? null,

            'reason' => $validated['reason'],

            'status' => 'pending',

            'requested_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.reviewers.requests.index')
            ->with(
                'success',
                'Reviewer request submitted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        ReviewerRequest $reviewerRequest
    ) {
        $reviewerRequest->load([
            'requester',
            'processor',
        ]);

        return view(
            'admin.reviewers.requests.show',
            compact('reviewerRequest')
        );
    }
}