<?php



namespace App\Http\Controllers\Reviewer;



use App\Http\Controllers\Controller;

use App\Models\ManuscriptFile;

use App\Models\PeerReview;

use App\Models\PeerReviewAssessment;

use App\Models\PeerReviewSuggestion;

use App\Models\ReviewerInvitation;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use Illuminate\Validation\Rule;

use Illuminate\Validation\ValidationException;



class PeerReviewController extends Controller

{

    /*

    |--------------------------------------------------------------------------

    | Get Authorized Invitation

    |--------------------------------------------------------------------------

    |

    | Reviewer can access only his/her own accepted invitation.

    |

    */



    private function authorizedInvitation(

        int $invitationId

    ): ReviewerInvitation {



        $reviewer = Auth::guard('reviewer')->user();



        abort_unless(

            $reviewer,

            403,

            'Reviewer authentication required.'

        );



        return ReviewerInvitation::query()

            ->whereKey($invitationId)

            ->where(

                'reviewer_id',

                $reviewer->id

            )

            ->where(

                'status',

                'accepted'

            )

            ->with([

                'manuscript.journal',

                'manuscript.articleType',

            ])

            ->firstOrFail();

    }





    /*

    |--------------------------------------------------------------------------

    | Get Authorized Peer Review

    |--------------------------------------------------------------------------

    */



    private function authorizedReview(

        int $peerReviewId

    ): PeerReview {



        $reviewer = Auth::guard('reviewer')->user();



        abort_unless(

            $reviewer,

            403,

            'Reviewer authentication required.'

        );



        return PeerReview::query()

            ->whereKey($peerReviewId)

            ->where(

                'reviewer_id',

                $reviewer->id

            )

            ->with([

                'invitation',

                'assessments',

                'suggestions',

            ])

            ->firstOrFail();

    }





    /*

    |--------------------------------------------------------------------------

    | Download Reviewer-Safe Manuscript File

    |--------------------------------------------------------------------------

    */



    public function downloadFile(

        int $invitation,

        int $file

    ) {



        $authorizedInvitation =

            $this->authorizedInvitation(

                $invitation

            );



        $manuscriptFile =

            ManuscriptFile::query()

                ->whereKey($file)

                ->where(

                    'manuscript_id',

                    $authorizedInvitation->manuscript_id

                )

                ->firstOrFail();





        /*

        |--------------------------------------------------------------------------

        | Normalize File Type / Status

        |--------------------------------------------------------------------------

        */



        $fileType = strtolower(

            trim(

                (string) (

                    $manuscriptFile->file_type

                    ?? ''

                )

            )

        );



        $fileStatus = strtolower(

            trim(

                (string) (

                    $manuscriptFile->status

                    ?? ''

                )

            )

        );





        /*

        |--------------------------------------------------------------------------

        | Block Unavailable Files

        |--------------------------------------------------------------------------

        */



        $blockedStatuses = [

            'deleted',

            'replaced',

            'inactive',

            'archived',

            'removed',

        ];



        if (

            in_array(

                $fileStatus,

                $blockedStatuses,

                true

            )

        ) {

            abort(

                404,

                'This manuscript file is no longer available.'

            );

        }





        /*

        |--------------------------------------------------------------------------

        | Author-identifying Files

        |--------------------------------------------------------------------------

        */



        $blockedFileTypes = [

            'title_page',

            'title page',

            'titlepage',



            'cover_letter',

            'cover letter',

            'coverletter',



            'author_information',

            'author information',

            'author_info',

            'author info',

            'author_details',

            'author details',

            'authors',



            'authorship_form',

            'authorship form',



            'author_declaration',

            'author declaration',



            'copyright',

            'copyright_form',

            'copyright form',



            'conflict_of_interest',

            'conflict of interest',

            'coi',

            'coi_form',



            'funding',

            'funding_information',

            'funding information',



            'ethics',

            'ethics_approval',

            'ethics approval',



            'irb',

            'irb_approval',

            'irb approval',



            'consent',

            'consent_form',

            'consent form',



            'declaration',

            'declaration_form',

            'declaration form',

        ];



        if (

            in_array(

                $fileType,

                $blockedFileTypes,

                true

            )

        ) {

            abort(

                403,

                'Author-identifying files are not available for peer review.'

            );

        }





        /*

        |--------------------------------------------------------------------------

        | Reviewer-Safe File Types

        |--------------------------------------------------------------------------

        */



        $mainManuscriptTypes = [

            'main_manuscript',

            'main manuscript',



            'manuscript',



            'manuscript_file',

            'manuscript file',



            'article',



            'article_file',

            'article file',



            'article_text',

            'article text',



            'main_file',

            'main file',



            'blinded_manuscript',

            'blinded manuscript',

        ];



        $supportingFileTypes = [

            'table',

            'tables',

            'table_file',

            'table file',



            'figure',

            'figures',

            'figure_file',

            'figure file',



            'supplementary',

            'supplementary_file',

            'supplementary file',

            'supplementary_files',

            'supplementary files',

        ];



        $allowedFileTypes = array_merge(

            $mainManuscriptTypes,

            $supportingFileTypes

        );



        if (

            !in_array(

                $fileType,

                $allowedFileTypes,

                true

            )

        ) {

            abort(

                403,

                'This file is not available for peer review.'

            );

        }





        /*

        |--------------------------------------------------------------------------

        | File Path

        |--------------------------------------------------------------------------

        */



        $relativePath = trim(

            (string) (

                $manuscriptFile->file_path

                ?? ''

            )

        );



        if ($relativePath === '') {

            abort(

                404,

                'The manuscript file path is missing.'

            );

        }



        $relativePath = str_replace(

            '\\\\',

            '/',

            $relativePath

        );



        if (

            str_starts_with(

                $relativePath,

                '/storage/'

            )

        ) {

            $relativePath = substr(

                $relativePath,

                strlen('/storage/')

            );

        }



        if (

            str_starts_with(

                $relativePath,

                'storage/'

            )

        ) {

            $relativePath = substr(

                $relativePath,

                strlen('storage/')

            );

        }



        $relativePath = ltrim(

            $relativePath,

            '/'

        );





        /*

        |--------------------------------------------------------------------------

        | File Extension

        |--------------------------------------------------------------------------

        */



        $sourceName =

            $manuscriptFile->original_name

            ?? $manuscriptFile->stored_name

            ?? $relativePath;



        $extension = strtolower(

            pathinfo(

                $sourceName,

                PATHINFO_EXTENSION

            )

        );



        if (!$extension) {

            $extension = strtolower(

                pathinfo(

                    $relativePath,

                    PATHINFO_EXTENSION

                )

            );

        }





        /*

        |--------------------------------------------------------------------------

        | Anonymous Reviewer-Safe Filename

        |--------------------------------------------------------------------------

        */



        if (

            in_array(

                $fileType,

                $mainManuscriptTypes,

                true

            )

        ) {



            $downloadName =

                'BMRC-Blinded-Manuscript';



        } elseif (

            in_array(

                $fileType,

                [

                    'table',

                    'tables',

                    'table_file',

                    'table file',

                ],

                true

            )

        ) {



            $downloadName =

                'BMRC-Table-' .

                $manuscriptFile->id;



        } elseif (

            in_array(

                $fileType,

                [

                    'figure',

                    'figures',

                    'figure_file',

                    'figure file',

                ],

                true

            )

        ) {



            $downloadName =

                'BMRC-Figure-' .

                $manuscriptFile->id;



        } else {



            $downloadName =

                'BMRC-Supplementary-' .

                $manuscriptFile->id;

        }



        if ($extension) {

            $downloadName .=

                '.' .

                $extension;

        }





        /*

        |--------------------------------------------------------------------------

        | Download

        |--------------------------------------------------------------------------

        */



        if (

            Storage::disk('public')

                ->exists($relativePath)

        ) {

            return Storage::disk('public')

                ->download(

                    $relativePath,

                    $downloadName

                );

        }



        if (

            Storage::disk('local')

                ->exists($relativePath)

        ) {

            return Storage::disk('local')

                ->download(

                    $relativePath,

                    $downloadName

                );

        }



        $storagePublicPath =

            storage_path(

                'app/public/' .

                $relativePath

            );



        if (

            is_file(

                $storagePublicPath

            )

        ) {

            return response()->download(

                $storagePublicPath,

                $downloadName

            );

        }



        $storageLocalPath =

            storage_path(

                'app/' .

                $relativePath

            );



        if (

            is_file(

                $storageLocalPath

            )

        ) {

            return response()->download(

                $storageLocalPath,

                $downloadName

            );

        }



        $publicFilePath =

            public_path(

                $relativePath

            );



        if (

            is_file(

                $publicFilePath

            )

        ) {

            return response()->download(

                $publicFilePath,

                $downloadName

            );

        }



        abort(

            404,

            'The manuscript file could not be found on the server.'

        );

    }





    /*

    |--------------------------------------------------------------------------

    | Create / Start Review

    |--------------------------------------------------------------------------

    */



    public function create(

        int $invitation

    ) {



        $reviewer =

            Auth::guard('reviewer')->user();



        $invitation =

            $this->authorizedInvitation(

                $invitation

            );





        /*

        |--------------------------------------------------------------------------

        | Deadline Check

        |--------------------------------------------------------------------------

        */



        if (

            $invitation->review_deadline

            &&

            $invitation

                ->review_deadline

                ->isPast()

        ) {



            return redirect()

                ->route(

                    'reviewer.invitations.show',

                    $invitation->id

                )

                ->with(

                    'error',

                    'The review deadline has passed. Please contact the BMRC Editorial Office.'

                );

        }





        $manuscript =

            $invitation->manuscript;





        /*

        |--------------------------------------------------------------------------

        | Create Review

        |--------------------------------------------------------------------------

        */



        $peerReview =

            PeerReview::firstOrCreate(

                [

                    'reviewer_invitation_id' =>

                        $invitation->id,



                    'review_round' =>

                        1,

                ],

                [

                    'manuscript_id' =>

                        $manuscript->id,



                    'reviewer_id' =>

                        $reviewer->id,



                    'status' =>

                        'draft',



                    'started_at' =>

                        now(),

                ]

            );





        /*

        |--------------------------------------------------------------------------

        | Already Submitted

        |--------------------------------------------------------------------------

        */



        if (

            $peerReview->status ===

            'submitted'

        ) {



            return redirect()

                ->route(

                    'reviewer.peer-reviews.show',

                    $peerReview->id

                );

        }





        /*

        |--------------------------------------------------------------------------

        | Load Draft

        |--------------------------------------------------------------------------

        */



        $peerReview->load([

            'assessments',

            'suggestions',

        ]);





        /*

        |--------------------------------------------------------------------------

        | Review Sections

        |--------------------------------------------------------------------------

        */



        $reviewSections =

            config(

                'peer_review.sections',

                []

            );





        /*

        |--------------------------------------------------------------------------

        | Blind Manuscript Data

        |--------------------------------------------------------------------------

        */



        $blindManuscript =

            $this->blindManuscriptData(

                $manuscript

            );





        /*

        |--------------------------------------------------------------------------

        | Existing Assessments

        |--------------------------------------------------------------------------

        */





        $existingAssessments =

    $peerReview

        ->assessments

        ->pluck(

            'assessment',

            'item_key'

        )

        ->toArray();





        /*

        |--------------------------------------------------------------------------

        | Existing Suggestions

        |--------------------------------------------------------------------------

        */



        $existingSuggestions =

            $peerReview

                ->suggestions

                ->pluck(

                    'suggestion',

                    'section'

                )

                ->toArray();





        /*

        |--------------------------------------------------------------------------

        | Reviewer-Safe Files

        |--------------------------------------------------------------------------

        */



        $reviewerFiles =

            $this->reviewerSafeFiles(

                $manuscript->id

            );





        /*

        |--------------------------------------------------------------------------

        | Return Review Workspace

        |--------------------------------------------------------------------------

        */



        return view(

            'reviewer.peer-reviews.create',

            compact(

                'invitation',

                'peerReview',

                'blindManuscript',

                'reviewSections',

                'existingAssessments',

                'existingSuggestions',

                'reviewerFiles'

            )

        );

    }





    /*

    |--------------------------------------------------------------------------

    | Save Draft

    |--------------------------------------------------------------------------

    */



    public function saveDraft(

        Request $request,

        int $peerReview

    ) {



        $review =

            $this->authorizedReview(

                $peerReview

            );



        if (

            $review->status ===

            'submitted'

        ) {



            return redirect()

                ->route(

                    'reviewer.peer-reviews.show',

                    $review->id

                )

                ->with(

                    'info',

                    'This review has already been submitted.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | Validate Draft

        |--------------------------------------------------------------------------

        */



        $request->validate([



            'assessment' =>

                'nullable|array',



            'assessment.*' => [

                'nullable',



                Rule::in([

                    'agree',

                    'disagree',

                    'need_modification',

                ]),

            ],



            'suggestions' =>

                'nullable|array',



            'suggestions.*' =>

                'nullable|string|max:10000',



            'overall_evaluation' => [

                'nullable',



                Rule::in([

                    'suitable',

                    'major_revision',

                    'minor_revision',

                    'not_suitable',

                ]),

            ],



            'comments_to_author' =>

                'nullable|string|max:20000',



            'confidential_comments_to_editor' =>

                'nullable|string|max:20000',



            'conflict_of_interest' =>

                'nullable|boolean',



            'conflict_details' =>

                'nullable|string|max:5000',



            'confidentiality_confirmed' =>

                'nullable|boolean',



            'reviewer_declaration' =>

                'nullable|boolean',

        ]);





        /*

        |--------------------------------------------------------------------------

        | Save Draft

        |--------------------------------------------------------------------------

        */



        DB::transaction(

            function () use (

                $request,

                $review

            ) {



                $review->update([



                    'overall_evaluation' =>

                        $request

                            ->overall_evaluation,



                    'comments_to_author' =>

                        $request

                            ->comments_to_author,



                    'confidential_comments_to_editor' =>

                        $request

                            ->confidential_comments_to_editor,



                    'conflict_of_interest' =>

                        $request->boolean(

                            'conflict_of_interest'

                        ),



                    'conflict_details' =>

                        $request

                            ->conflict_details,



                    'confidentiality_confirmed' =>

                        $request->boolean(

                            'confidentiality_confirmed'

                        ),



                    'reviewer_declaration' =>

                        $request->boolean(

                            'reviewer_declaration'

                        ),



                    'status' =>

                        'draft',

                ]);





                $this->storeAssessments(

                    $review,

                    $request->input(

                        'assessment',

                        []

                    )

                );





                $this->storeSuggestions(

                    $review,

                    $request->input(

                        'suggestions',

                        []

                    )

                );

            }

        );




        if ($request->boolean('preview_after_save')) {

            return redirect()
                ->route(
                    'reviewer.peer-reviews.preview',
                    $review->id
                );
        }

        return redirect()
            ->route(
                'reviewer.peer-reviews.create',
                $review->reviewer_invitation_id
            )
            ->with(
                'success',
                'Review draft saved successfully.'
            );

    }





    /*

    |--------------------------------------------------------------------------

    | Preview

    |--------------------------------------------------------------------------

    */



    public function preview(

        int $peerReview

    ) {



        $review =

            $this->authorizedReview(

                $peerReview

            );





        /*

        |--------------------------------------------------------------------------

        | Load Preview Data

        |--------------------------------------------------------------------------

        */



        $review->load([

            'assessments',

            'suggestions',

            'invitation.manuscript.articleType',

            'invitation.manuscript.journal',

        ]);





        /*

        |--------------------------------------------------------------------------

        | Invitation

        |--------------------------------------------------------------------------

        */



        $invitation =

            $review->invitation;



        abort_unless(

            $invitation,

            404,

            'Review invitation could not be found.'

        );





        /*

        |--------------------------------------------------------------------------

        | Manuscript

        |--------------------------------------------------------------------------

        */



        $manuscript =

            $invitation->manuscript;



        abort_unless(

            $manuscript,

            404,

            'Manuscript could not be found.'

        );





        /*

        |--------------------------------------------------------------------------

        | Blind Manuscript

        |--------------------------------------------------------------------------

        */



        $blindManuscript =

            $this->blindManuscriptData(

                $manuscript

            );





        /*

        |--------------------------------------------------------------------------

        | Review Sections

        |--------------------------------------------------------------------------

        */



        $reviewSections =

            config(

                'peer_review.sections',

                []

            );





        /*

        |--------------------------------------------------------------------------

        | Existing Assessments

        |--------------------------------------------------------------------------

        */



        $existingAssessments =

            $review

                ->assessments

                ->pluck(

                    'assessment',

                    'item_key'

                )
                ->toArray();





        /*

        |--------------------------------------------------------------------------

        | Existing Suggestions

        |--------------------------------------------------------------------------

        */



        $existingSuggestions =

            $review

                ->suggestions

                ->pluck(

                    'suggestion',

                    'section'

                )
                ->toArray();





        /*

        |--------------------------------------------------------------------------

        | Blade Compatibility

        |--------------------------------------------------------------------------

        |

        | Existing controller convention:

        | $review

        |

        | Preview Blade convention:

        | $peerReview

        |

        */



        $peerReview = $review;





        /*

        |--------------------------------------------------------------------------

        | Return Preview

        |--------------------------------------------------------------------------

        */



        return view(

            'reviewer.peer-reviews.preview',

            compact(

                'review',

                'peerReview',

                'invitation',

                'blindManuscript',

                'reviewSections',

                'existingAssessments',

                'existingSuggestions'

            )

        );

    }





    /*

    |--------------------------------------------------------------------------

    | Final Submit

    |--------------------------------------------------------------------------

    */



    public function submit(

        Request $request,

        int $peerReview

    ) {



        $review =

            $this->authorizedReview(

                $peerReview

            );





        /*

        |--------------------------------------------------------------------------

        | Already Submitted

        |--------------------------------------------------------------------------

        */



        if (

            $review->status ===

            'submitted'

        ) {



            return redirect()

                ->route(

                    'reviewer.peer-reviews.show',

                    $review->id

                )

                ->with(

                    'info',

                    'This review has already been submitted.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | Review Deadline

        |--------------------------------------------------------------------------

        */



        $invitation =

            $review->invitation;



        if (

            $invitation->review_deadline

            &&

            $invitation

                ->review_deadline

                ->isPast()

        ) {



            return back()

                ->with(

                    'error',

                    'The review deadline has passed.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | Review Sections

        |--------------------------------------------------------------------------

        */



        $reviewSections =

            config(

                'peer_review.sections',

                []

            );





        /*

        |--------------------------------------------------------------------------

        | Final Validation

        |--------------------------------------------------------------------------

        */



        $rules = [



            'assessment' =>

                'required|array',



            'suggestions' =>

                'nullable|array',



            'suggestions.*' =>

                'nullable|string|max:10000',



            'overall_evaluation' => [

                'required',



                Rule::in([

                    'suitable',

                    'major_revision',

                    'minor_revision',

                    'not_suitable',

                ]),

            ],



            'comments_to_author' =>

                'nullable|string|max:20000',



            'confidential_comments_to_editor' =>

                'nullable|string|max:20000',



            'conflict_of_interest' =>

                'nullable|boolean',



            'conflict_details' =>

                'nullable|string|max:5000',



            'confidentiality_confirmed' =>

                'accepted',



            'reviewer_declaration' =>

                'accepted',

        ];





        /*

        |--------------------------------------------------------------------------

        | Every Assessment Item Required

        |--------------------------------------------------------------------------

        */



        foreach (

            $reviewSections as

            $section

        ) {



            foreach (

                $section['items'] as

                $itemKey => $label

            ) {



                $rules[

                    'assessment.' .

                    $itemKey

                ] = [



                    'required',



                    Rule::in([

                        'agree',

                        'disagree',

                        'need_modification',

                    ]),

                ];

            }

        }





        /*

        |--------------------------------------------------------------------------

        | Validate

        |--------------------------------------------------------------------------

        */



        $request->validate(

            $rules

        );





        /*

        |--------------------------------------------------------------------------

        | Conflict Explanation

        |--------------------------------------------------------------------------

        */



        if (

            $request->boolean(

                'conflict_of_interest'

            )

            &&

            blank(

                $request

                    ->conflict_details

            )

        ) {



            throw ValidationException::withMessages([



                'conflict_details' =>

                    'Please describe the conflict of interest.',

            ]);

        }





        /*

        |--------------------------------------------------------------------------

        | Final Save

        |--------------------------------------------------------------------------

        */



        DB::transaction(

            function () use (

                $request,

                $review

            ) {



                $this->storeAssessments(

                    $review,

                    $request->input(

                        'assessment',

                        []

                    )

                );





                $this->storeSuggestions(

                    $review,

                    $request->input(

                        'suggestions',

                        []

                    )

                );





                $review->update([



                    'overall_evaluation' =>

                        $request

                            ->overall_evaluation,



                    'comments_to_author' =>

                        $request

                            ->comments_to_author,



                    'confidential_comments_to_editor' =>

                        $request

                            ->confidential_comments_to_editor,



                    'conflict_of_interest' =>

                        $request->boolean(

                            'conflict_of_interest'

                        ),



                    'conflict_details' =>

                        $request

                            ->conflict_details,



                    'confidentiality_confirmed' =>

                        true,



                    'reviewer_declaration' =>

                        true,



                    'status' =>

                        'submitted',



                    'submitted_at' =>

                        now(),

                ]);

            }

        );





        /*

        |--------------------------------------------------------------------------

        | Submitted Review

        |--------------------------------------------------------------------------

        */



        return redirect()

            ->route(

                'reviewer.peer-reviews.show',

                $review->id

            )

            ->with(

                'success',

                'Peer review submitted successfully.'

            );

    }





    /*

    |--------------------------------------------------------------------------

    | Show Submitted Review

    |--------------------------------------------------------------------------

    */



    public function show(

        int $peerReview

    ) {



        $review =

            $this->authorizedReview(

                $peerReview

            );



        $review->load([

            'assessments',

            'suggestions',

            'invitation.manuscript.articleType',

            'invitation.manuscript.journal',

        ]);



        $invitation =

            $review->invitation;



        abort_unless(

            $invitation,

            404,

            'Review invitation could not be found.'

        );



        $manuscript =

            $invitation->manuscript;



        abort_unless(

            $manuscript,

            404,

            'Manuscript could not be found.'

        );





        /*

        |--------------------------------------------------------------------------

        | Blind Manuscript Data

        |--------------------------------------------------------------------------

        */



        $blindManuscript =

            $this->blindManuscriptData(

                $manuscript

            );





        /*

        |--------------------------------------------------------------------------

        | Review Sections

        |--------------------------------------------------------------------------

        */



        $reviewSections =

            config(

                'peer_review.sections',

                []

            );





        /*

        |--------------------------------------------------------------------------

        | Existing Assessment Values

        |--------------------------------------------------------------------------

        */



        $existingAssessments =

            $review

                ->assessments

                ->pluck(

                    'assessment',

                    'item_key'

                )
                ->toArray();





        /*

        |--------------------------------------------------------------------------

        | Existing Suggestions

        |--------------------------------------------------------------------------

        */



        $existingSuggestions =

            $review

                ->suggestions

                ->pluck(

                    'suggestion',

                    'section'

                )
                ->toArray();





        /*

        |--------------------------------------------------------------------------

        | Blade Compatibility

        |--------------------------------------------------------------------------

        */



        $peerReview = $review;





        return view(

            'reviewer.peer-reviews.show',

            compact(

                'review',

                'peerReview',

                'invitation',

                'blindManuscript',

                'reviewSections',

                'existingAssessments',

                'existingSuggestions'

            )

        );

    }





    /*

    |--------------------------------------------------------------------------

    | Store Assessments

    |--------------------------------------------------------------------------

    */



    private function storeAssessments(

        PeerReview $review,

        array $assessments

    ): void {



        $sections =

            config(

                'peer_review.sections',

                []

            );



        $sortOrder = 1;





        foreach (

            $sections as

            $sectionKey => $section

        ) {



            foreach (

                $section['items'] as

                $itemKey => $itemLabel

            ) {



                if (

                    !array_key_exists(

                        $itemKey,

                        $assessments

                    )

                ) {



                    $sortOrder++;



                    continue;

                }





                $assessment =

                    $assessments[

                        $itemKey

                    ];





                if (

                    !in_array(

                        $assessment,

                        [

                            'agree',

                            'disagree',

                            'need_modification',

                        ],

                        true

                    )

                ) {



                    $sortOrder++;



                    continue;

                }





                PeerReviewAssessment::updateOrCreate(

                    [

                        'peer_review_id' =>

                            $review->id,



                        'item_key' =>

                            $itemKey,

                    ],

                    [

                        'section' =>

                            $sectionKey,



                        'item_label' =>

                            $itemLabel,



                        'assessment' =>

                            $assessment,



                        'sort_order' =>

                            $sortOrder,

                    ]

                );





                $sortOrder++;

            }

        }

    }





    /*

    |--------------------------------------------------------------------------

    | Store Suggestions

    |--------------------------------------------------------------------------

    */



    private function storeSuggestions(

        PeerReview $review,

        array $suggestions

    ): void {



        $sections =

            config(

                'peer_review.sections',

                []

            );





        foreach (

            $sections as

            $sectionKey => $section

        ) {



            if (

                !array_key_exists(

                    $sectionKey,

                    $suggestions

                )

            ) {

                continue;

            }





            PeerReviewSuggestion::updateOrCreate(

                [

                    'peer_review_id' =>

                        $review->id,



                    'section' =>

                        $sectionKey,

                ],

                [

                    'suggestion' =>

                        $suggestions[

                            $sectionKey

                        ],

                ]

            );

        }

    }





    /*

    |--------------------------------------------------------------------------

    | Blind Manuscript Data

    |--------------------------------------------------------------------------

    |

    | Centralized so create(), preview(), and show() use exactly

    | the same reviewer-safe manuscript information.

    |

    */



    private function blindManuscriptData(

        $manuscript

    ): array {



        return [



            'manuscript_id' =>

                $manuscript->manuscript_id,



            'title' =>

                $manuscript->title,



            'article_type' =>

                $manuscript

                    ->articleType

                    ?->name,



            'journal' =>

                $manuscript

                    ->journal

                    ?->name

                ?? 'BMRC Bulletin',



            'abstract' =>

                $manuscript->abstract

                ?? null,



            'keywords' =>

                $manuscript->keywords

                ?? null,



            'subject_area' =>

                $manuscript->subject_area

                ?? null,



            'background' =>

                $manuscript->background

                ?? null,



            'objectives' =>

                $manuscript->objectives

                ?? null,



            'methods' =>

                $manuscript->methods

                ?? null,



            'results' =>

                $manuscript->results

                ?? null,



            'conclusions' =>

                $manuscript->conclusions

                ?? null,

        ];

    }





    /*

    |--------------------------------------------------------------------------

    | Reviewer-Safe Files

    |--------------------------------------------------------------------------

    |

    | This is only for displaying available files in the review workspace.

    |

    | Actual View / Download authorization must still be handled by

    | ReviewerManuscriptFileController.

    |

    */



    private function reviewerSafeFiles(

        int $manuscriptId

    ) {



        $allowedFileTypes = [



            'main manuscript',

            'main_manuscript',

            'manuscript',

            'manuscript_file',

            'manuscript file',

            'article',

            'article_file',

            'article file',

            'article_text',

            'article text',

            'main_file',

            'main file',

            'blinded_manuscript',

            'blinded manuscript',



            'table',

            'tables',

            'table_file',

            'table file',



            'figure',

            'figures',

            'figure_file',

            'figure file',



            'supplementary',

            'supplementary_file',

            'supplementary file',

            'supplementary_files',

            'supplementary files',

        ];



        $blockedStatuses = [

            'deleted',

            'replaced',

            'inactive',

            'archived',

            'removed',

        ];





        return ManuscriptFile::query()

            ->where(

                'manuscript_id',

                $manuscriptId

            )

            ->get()

            ->filter(

                function (

                    ManuscriptFile $file

                ) use (

                    $allowedFileTypes,

                    $blockedStatuses

                ) {



                    $fileType =

                        strtolower(

                            trim(

                                (string) (

                                    $file->file_type

                                    ?? ''

                                )

                            )

                        );



                    $fileStatus =

                        strtolower(

                            trim(

                                (string) (

                                    $file->status

                                    ?? ''

                                )

                            )

                        );





                    return

                        in_array(

                            $fileType,

                            $allowedFileTypes,

                            true

                        )

                        &&

                        !in_array(

                            $fileStatus,

                            $blockedStatuses,

                            true

                        );

                }

            )

            ->values();

    }

}