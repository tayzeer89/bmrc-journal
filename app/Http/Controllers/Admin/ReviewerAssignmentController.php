<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use Illuminate\Http\Request;

class ReviewerAssignmentController extends Controller
{
    public function index()
    {
        return view('admin.reviewers.assignments.index');
    }

    public function create(Manuscript $manuscript)
    {
        return view(
            'admin.reviewers.assignments.create',
            compact('manuscript')
        );
    }

    public function store(Request $request, Manuscript $manuscript)
    {
        $validated = $request->validate([
            'reviewer_id' => [
                'required',
                'integer',
                'exists:reviewers,id',
            ],

            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ]);

        // We will add database assignment logic here.

        return redirect()
            ->route('admin.reviewer-assignments.index')
            ->with('success', 'Reviewer assigned successfully.');
    }

    public function show($assignment)
    {
        return view(
            'admin.reviewers.assignments.show',
            compact('assignment')
        );
    }

    public function reassign(Request $request, $assignment)
    {
        $request->validate([
            'reviewer_id' => [
                'required',
                'integer',
                'exists:reviewers,id',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ]);

        return back()
            ->with('success', 'Reviewer reassigned successfully.');
    }

    public function remove(Request $request, $assignment)
    {
        return back()
            ->with('success', 'Reviewer assignment removed successfully.');
    }
}