<?php

namespace App\Http\Controllers;

use App\Models\Reviewer;
use App\Models\ReviewerProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;




class ReviewerDirectoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Reviewer Directory
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Filtered Reviewers
        |--------------------------------------------------------------------------
        */

        $reviewers = $this->reviewerQuery($request)->get();


        /*
        |--------------------------------------------------------------------------
        | Group By Speciality
        |--------------------------------------------------------------------------
        |
        | Reviewers without speciality are NOT removed.
        | They will appear under "Other / General".
        |
        */

        $reviewersBySpeciality = $this->groupBySpeciality(
            $reviewers
        );


        /*
        |--------------------------------------------------------------------------
        | Speciality Filter List
        |--------------------------------------------------------------------------
        */

        $specialities = ReviewerProfile::query()

            ->where('approval_status', 'approved')

            ->where('profile_completed', true)

            ->whereHas('reviewer', function ($query) {

                $query->where(
                    'status',
                    'approved'
                );

            })

            ->whereNotNull('speciality')

            ->where('speciality', '!=', '')

            ->orderBy('speciality')

            ->pluck('speciality')

            ->map(function ($speciality) {

                return trim($speciality);

            })

            ->filter()

            ->unique()

            ->values();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalReviewers = $reviewers->count();


        $nationalCount = $reviewers->filter(
            function ($reviewer) {

                return $this->isNationalReviewer(
                    $reviewer
                );

            }
        )->count();


        $internationalCount = $reviewers->filter(
            function ($reviewer) {

                return $this->isInternationalReviewer(
                    $reviewer
                );

            }
        )->count();


        return view(
            'website.journal.reviewers.directory',
            compact(
                'reviewersBySpeciality',
                'specialities',
                'totalReviewers',
                'nationalCount',
                'internationalCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reviewer Profile
    |--------------------------------------------------------------------------
    */

    public function show(Reviewer $reviewer)
    {
        $reviewer->load('profile');


        /*
        |--------------------------------------------------------------------------
        | Public Visibility Check
        |--------------------------------------------------------------------------
        */

        if ($reviewer->status !== 'approved') {

            abort(404);

        }


        if (!$reviewer->profile) {

            abort(404);

        }


        if (
            $reviewer->profile->approval_status
            !== 'approved'
        ) {

            abort(404);

        }


        if (!$reviewer->profile->profile_completed) {

            abort(404);

        }


        /*
        |--------------------------------------------------------------------------
        | Reviewer Type
        |--------------------------------------------------------------------------
        */

        $reviewerType =
            $this->isNationalReviewer($reviewer)
                ? 'National'
                : 'International';


        return view(
            'website.journal.reviewers.show',
            compact(
                'reviewer',
                'reviewerType'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Export Reviewer Directory PDF
    |--------------------------------------------------------------------------
    */

    public function pdf(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Apply Same Search Filters
        |--------------------------------------------------------------------------
        |
        | This means:
        |
        | Search Medicine -> PDF contains Medicine search result
        | National selected -> PDF contains National result
        | No filter -> PDF contains complete directory
        |
        */

        $reviewers = $this->reviewerQuery($request)->get();


        /*
        |--------------------------------------------------------------------------
        | Group By Speciality
        |--------------------------------------------------------------------------
        */

        $reviewersBySpeciality =
            $this->groupBySpeciality(
                $reviewers
            );


        /*
        |--------------------------------------------------------------------------
        | Counts
        |--------------------------------------------------------------------------
        */

        $totalReviewers = $reviewers->count();


        $nationalCount = $reviewers->filter(
            function ($reviewer) {

                return $this->isNationalReviewer(
                    $reviewer
                );

            }
        )->count();


        $internationalCount = $reviewers->filter(
            function ($reviewer) {

                return $this->isInternationalReviewer(
                    $reviewer
                );

            }
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Current Filter Information
        |--------------------------------------------------------------------------
        */

        $filters = [

            'search' => $request->input('search'),

            'speciality' =>
                $request->input('speciality'),

            'reviewer_type' =>
                $request->input('reviewer_type'),

        ];


        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'website.journal.reviewers.pdf',
            compact(
                'reviewersBySpeciality',
                'totalReviewers',
                'nationalCount',
                'internationalCount',
                'filters'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | A4 Portrait
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper(
            'a4',
            'portrait'
        );


        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        return $pdf->download(
            'BMRC-Reviewer-Directory-' .
            now()->format('Y-m-d') .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Shared Reviewer Query
    |--------------------------------------------------------------------------
    |
    | Website and PDF use EXACTLY the same query.
    |
    */

    private function reviewerQuery(
        Request $request
    ): Builder {

        $query = Reviewer::query()

            ->with('profile')

            ->where(
                'status',
                'approved'
            )

            ->whereHas(
                'profile',
                function ($query) {

                    $query
                        ->where(
                            'approval_status',
                            'approved'
                        )

                        ->where(
                            'profile_completed',
                            true
                        );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->input('search')
            );


            $query->where(
                function ($query) use ($search) {

                    /*
                    |--------------------------------------------------------------------------
                    | Reviewer Account Name
                    |--------------------------------------------------------------------------
                    */

                    $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Reviewer Profile
                    |--------------------------------------------------------------------------
                    */

                    $query->orWhereHas(
                        'profile',
                        function ($profileQuery) use ($search) {

                            $profileQuery

                                ->where(
                                    'display_name',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'first_name',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'middle_name',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'last_name',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'speciality',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'sub_speciality',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'specialization',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'designation',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'department',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'institution',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'country',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'areas_of_expertise',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'primary_expertise',
                                    'like',
                                    '%' . $search . '%'
                                )

                                ->orWhere(
                                    'expertise_keywords',
                                    'like',
                                    '%' . $search . '%'
                                );

                        }
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Speciality Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('speciality')) {

            $speciality = trim(
                $request->input('speciality')
            );


            $query->whereHas(
                'profile',
                function ($query) use ($speciality) {

                    $query->where(
                        'speciality',
                        $speciality
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | National / International Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('reviewer_type')) {

            $type = strtolower(
                trim(
                    $request->input(
                        'reviewer_type'
                    )
                )
            );


            /*
            |--------------------------------------------------------------------------
            | National
            |--------------------------------------------------------------------------
            */

            if ($type === 'national') {

                $query->whereHas(
                    'profile',
                    function ($query) {

                        $query->whereRaw(
                            'LOWER(TRIM(country)) = ?',
                            ['bangladesh']
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | International
            |--------------------------------------------------------------------------
            */

            elseif ($type === 'international') {

                $query->whereHas(
                    'profile',
                    function ($query) {

                        $query
                            ->whereNotNull(
                                'country'
                            )

                            ->where(
                                'country',
                                '!=',
                                ''
                            )

                            ->whereRaw(
                                'LOWER(TRIM(country)) != ?',
                                ['bangladesh']
                            );

                    }
                );

            }

        }


        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | Group Reviewers By Speciality
    |--------------------------------------------------------------------------
    */

    private function groupBySpeciality(
        $reviewers
    ) {

        return $reviewers

            ->groupBy(
                function ($reviewer) {

                    $speciality = trim(
                        $reviewer
                            ->profile
                            ?->speciality ?? ''
                    );


                    return $speciality !== ''
                        ? $speciality
                        : 'Other / General';

                }
            )

            ->sortKeys(
                SORT_NATURAL
                | SORT_FLAG_CASE
            );
    }


    /*
    |--------------------------------------------------------------------------
    | National Reviewer
    |--------------------------------------------------------------------------
    */

    private function isNationalReviewer(
        $reviewer
    ): bool {

        return strtolower(
            trim(
                $reviewer
                    ->profile
                    ?->country ?? ''
            )
        ) === 'bangladesh';
    }


    /*
    |--------------------------------------------------------------------------
    | International Reviewer
    |--------------------------------------------------------------------------
    */

    private function isInternationalReviewer(
        $reviewer
    ): bool {

        $country = strtolower(
            trim(
                $reviewer
                    ->profile
                    ?->country ?? ''
            )
        );


        return
            $country !== ''
            && $country !== 'bangladesh';
    }
}