<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use App\Models\SimilarityCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SimilarityCheckController extends Controller
{
    public function index()
    {
        $manuscripts = Manuscript::with('latestSimilarityCheck')
            ->whereIn('status', [
                'payment_verified',
                'similarity_check',
            ])
            ->latest()
            ->paginate(20);

        return view(
            'admin.similarity-checks.index',
            compact('manuscripts')
        );
    }

    public function show(Manuscript $manuscript)
    {
        $manuscript->load([
            'latestSimilarityCheck',
            'similarityChecks.checkedBy',
        ]);

        return view(
            'admin.similarity-checks.show',
            compact('manuscript')
        );
    }

    public function create(Manuscript $manuscript)
    {
        $lastCheck = $manuscript
            ->similarityChecks()
            ->max('check_number');

        $checkNumber = ($lastCheck ?? 0) + 1;

        return view(
            'admin.similarity-checks.create',
            compact(
                'manuscript',
                'checkNumber'
            )
        );
    }

    public function store(
        Request $request,
        Manuscript $manuscript
    ) {
        $validated = $request->validate([
            'similarity_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'threshold_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'software_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'report_file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            'status' => [
                'required',
                'in:passed,review_required,returned_to_author,escalated',
            ],

            'comments' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $validated,
            $manuscript
        ) {
            $checkNumber =
                ($manuscript
                    ->similarityChecks()
                    ->max('check_number') ?? 0) + 1;

            $reportPath = null;

            if ($request->hasFile('report_file')) {
                $reportPath = $request
                    ->file('report_file')
                    ->store(
                        'similarity-reports',
                        'public'
                    );
            }

            SimilarityCheck::create([
                'manuscript_id' =>
                    $manuscript->id,

                'check_number' =>
                    $checkNumber,

                'similarity_percentage' =>
                    $validated['similarity_percentage'],

                'threshold_percentage' =>
                    $validated['threshold_percentage'],

                'software_name' =>
                    $validated['software_name'] ?? null,

                'report_file' =>
                    $reportPath,

                'checked_by' =>
                    auth()->id(),

                'checked_at' =>
                    now(),

                'status' =>
                    $validated['status'],

                'comments' =>
                    $validated['comments'] ?? null,
            ]);

            if ($validated['status'] === 'passed') {
                $manuscript->update([
                    'status' => 'editor_assignment',
                ]);
            } elseif (
                $validated['status']
                === 'returned_to_author'
            ) {
                $manuscript->update([
                    'status' =>
                        'similarity_correction',
                ]);
            } else {
                $manuscript->update([
                    'status' =>
                        'similarity_check',
                ]);
            }
        });

        return redirect()
            ->route(
                'admin.similarity-checks.show',
                $manuscript
            )
            ->with(
                'success',
                'Similarity check saved successfully.'
            );
    }
}