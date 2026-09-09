<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use App\Models\TechnicalCorrectionResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TechnicalCorrectionController extends Controller
{
    /**
     * Submit technical corrections by author.
     */
    public function submit(Request $request, Manuscript $manuscript)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | 1. Check manuscript ownership
        |--------------------------------------------------------------------------
        */

        $isOwner = false;

        if (
            Schema::hasColumn('manuscripts', 'submitted_by') &&
            (int) $manuscript->submitted_by === (int) $user->id
        ) {
            $isOwner = true;
        }

        if (
            Schema::hasColumn('manuscripts', 'submitter_id') &&
            (int) $manuscript->submitter_id === (int) $user->id
        ) {
            $isOwner = true;
        }

        if (
            Schema::hasColumn('manuscripts', 'user_id') &&
            (int) $manuscript->user_id === (int) $user->id
        ) {
            $isOwner = true;
        }

        if (!$isOwner) {
            try {
                if (
                    $manuscript->submitter &&
                    (int) $manuscript->submitter->id === (int) $user->id
                ) {
                    $isOwner = true;
                }
            } catch (\Throwable $e) {
                // Ignore relationship errors.
            }
        }

        abort_unless($isOwner, 403);


        /*
        |--------------------------------------------------------------------------
        | 2. Manuscript must be waiting for technical correction
        |--------------------------------------------------------------------------
        */

        if ($manuscript->status !== 'technical_correction') {
            return back()->with(
                'error',
                'This manuscript is not currently waiting for technical correction.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Find active correction-required technical check
        |--------------------------------------------------------------------------
        */

        $technicalCheck = $manuscript
            ->technicalChecks()
            ->where(function ($query) {
                $query
                    ->where('status', 'correction_required')
                    ->orWhere(
                        'overall_result',
                        'correction_required'
                    );
            })
            ->latest('check_number')
            ->first();

        if (!$technicalCheck) {
            return back()->with(
                'error',
                'No active technical correction request was found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Validate author submission
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'response' => [
                'required',
                'string',
                'min:10',
                'max:10000',
            ],

            'confirmation' => [
                'required',
                'accepted',
            ],

            'files' => [
                'nullable',
                'array',
            ],

            'files.*' => [
                'nullable',
                'file',
                'max:20480',
                'mimes:pdf,doc,docx,rtf,txt',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 5. Get active manuscript files
        |--------------------------------------------------------------------------
        */

        $currentFiles = $manuscript
            ->files()
            ->where('status', 'active')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 6. Validate selected file IDs
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['files'])) {

            foreach (array_keys($validated['files']) as $oldFileId) {

                $oldFile = $currentFiles->firstWhere(
                    'id',
                    $oldFileId
                );

                if (!$oldFile) {
                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Invalid manuscript file selected.'
                        );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 7. Database transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $manuscript,
            $technicalCheck,
            $validated,
            $currentFiles
        ) {

            /*
            |--------------------------------------------------------------------------
            | Determine next manuscript version
            |--------------------------------------------------------------------------
            */

            $newVersionNumber = 1;

            if (Schema::hasTable('manuscript_versions')) {

                $lastVersion = DB::table('manuscript_versions')
                    ->where(
                        'manuscript_id',
                        $manuscript->id
                    )
                    ->lockForUpdate()
                    ->max('version_number');

                $newVersionNumber =
                    ((int) $lastVersion) + 1;
            }


            /*
            |--------------------------------------------------------------------------
            | Create manuscript version
            |--------------------------------------------------------------------------
            */

            $manuscriptVersion = null;

            if (
                method_exists(
                    $manuscript,
                    'versions'
                ) &&
                Schema::hasTable(
                    'manuscript_versions'
                )
            ) {

                $versionData = [];


                if (
                    Schema::hasColumn(
                        'manuscript_versions',
                        'manuscript_id'
                    )
                ) {
                    $versionData['manuscript_id'] =
                        $manuscript->id;
                }


                if (
                    Schema::hasColumn(
                        'manuscript_versions',
                        'version_number'
                    )
                ) {
                    $versionData['version_number'] =
                        $newVersionNumber;
                }


                if (
                    Schema::hasColumn(
                        'manuscript_versions',
                        'created_by'
                    )
                ) {
                    $versionData['created_by'] =
                        auth()->id();
                }


                if (
                    Schema::hasColumn(
                        'manuscript_versions',
                        'status'
                    )
                ) {
                    $versionData['status'] =
                        'submitted';
                }


                $manuscriptVersion =
                    $manuscript
                        ->versions()
                        ->create($versionData);
            }


            /*
            |--------------------------------------------------------------------------
            | Upload corrected files
            |--------------------------------------------------------------------------
            */

            if (!empty($validated['files'])) {

                foreach (
                    $validated['files']
                    as $oldFileId => $uploadedFile
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Skip empty upload
                    |--------------------------------------------------------------------------
                    */

                    if (!$uploadedFile) {
                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find old file
                    |--------------------------------------------------------------------------
                    */

                    $oldFile =
                        $currentFiles->firstWhere(
                            'id',
                            $oldFileId
                        );

                    if (!$oldFile) {
                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Storage directory
                    |--------------------------------------------------------------------------
                    */

                    $directory =
                        'manuscripts/' .
                        $manuscript->id .
                        '/versions/' .
                        $newVersionNumber;


                    /*
                    |--------------------------------------------------------------------------
                    | Original extension
                    |--------------------------------------------------------------------------
                    */

                    $extension = strtolower(
                        $uploadedFile
                            ->getClientOriginalExtension()
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Generate unique stored file name
                    |--------------------------------------------------------------------------
                    */

                    $storedName =
                        Str::uuid()->toString() .
                        (
                            $extension
                                ? '.' . $extension
                                : ''
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Store file
                    |--------------------------------------------------------------------------
                    */

                    $path = $uploadedFile->storeAs(
                        $directory,
                        $storedName,
                        'public'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Generate NEW unique file_id
                    |--------------------------------------------------------------------------
                    |
                    | IMPORTANT:
                    |
                    | manuscript_files.file_id is UNIQUE.
                    |
                    | Therefore we MUST NOT use:
                    |
                    |     $oldFile->file_id
                    |
                    | We create a completely new file ID.
                    |
                    */

                    do {

                        $newFileId =
                            'MF-' .
                            strtoupper(
                                Str::random(12)
                            );

                    } while (
                        DB::table('manuscript_files')
                            ->where(
                                'file_id',
                                $newFileId
                            )
                            ->exists()
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Prepare new manuscript file
                    |--------------------------------------------------------------------------
                    */

                    $newFileData = [

                        'manuscript_id' =>
                            $manuscript->id,

                        /*
                        |--------------------------------------------------------------------------
                        | NEW UNIQUE file_id
                        |--------------------------------------------------------------------------
                        */

                        'file_id' =>
                            $newFileId,

                        'file_type' =>
                            $oldFile->file_type,

                        'original_name' =>
                            $uploadedFile
                                ->getClientOriginalName(),

                        'stored_name' =>
                            $storedName,

                        'file_path' =>
                            $path,

                        'file_size' =>
                            $uploadedFile->getSize(),

                        'mime_type' =>
                            $uploadedFile->getMimeType(),

                        'version_number' =>
                            $newVersionNumber,

                        'uploaded_by' =>
                            auth()->id(),

                        'status' =>
                            'active',

                        /*
                        |--------------------------------------------------------------------------
                        | Keep relationship with previous manuscript file
                        |--------------------------------------------------------------------------
                        */

                        'replacement_file_id' =>
                            $oldFile->id,
                    ];


                    /*
                    |--------------------------------------------------------------------------
                    | Link manuscript version
                    |--------------------------------------------------------------------------
                    */

                    if ($manuscriptVersion) {

                        $newFileData[
                            'manuscript_version_id'
                        ] =
                            $manuscriptVersion->id;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Create new manuscript file
                    |--------------------------------------------------------------------------
                    */

                    $manuscript
                        ->files()
                        ->create(
                            $newFileData
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Mark old file as replaced
                    |--------------------------------------------------------------------------
                    */

                    $oldFile->update([
                        'status' => 'replaced',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Save technical correction response
            |--------------------------------------------------------------------------
            */

            if (
                Schema::hasTable(
                    'technical_correction_responses'
                )
            ) {

                $responseData = [];


                /*
                |--------------------------------------------------------------------------
                | manuscript_id
                |--------------------------------------------------------------------------
                */

                if (
                    Schema::hasColumn(
                        'technical_correction_responses',
                        'manuscript_id'
                    )
                ) {

                    $responseData['manuscript_id'] =
                        $manuscript->id;
                }


                /*
                |--------------------------------------------------------------------------
                | technical_check_id
                |--------------------------------------------------------------------------
                */

                if (
                    Schema::hasColumn(
                        'technical_correction_responses',
                        'technical_check_id'
                    )
                ) {

                    $responseData[
                        'technical_check_id'
                    ] =
                        $technicalCheck->id;
                }


                /*
                |--------------------------------------------------------------------------
                | submitted_by / user_id
                |--------------------------------------------------------------------------
                */

                if (
                    Schema::hasColumn(
                        'technical_correction_responses',
                        'submitted_by'
                    )
                ) {

                    $responseData[
                        'submitted_by'
                    ] =
                        auth()->id();

                } elseif (
                    Schema::hasColumn(
                        'technical_correction_responses',
                        'user_id'
                    )
                ) {

                    $responseData[
                        'user_id'
                    ] =
                        auth()->id();
                }


                /*
                |--------------------------------------------------------------------------
                | response / comments
                |--------------------------------------------------------------------------
                */

                if (
                    Schema::hasColumn(
                        'technical_correction_responses',
                        'response'
                    )
                ) {

                    $responseData['response'] =
                        $validated['response'];

                } elseif (
                    Schema::hasColumn(
                        'technical_correction_responses',
                        'comments'
                    )
                ) {

                    $responseData['comments'] =
                        $validated['response'];
                }


                /*
                |--------------------------------------------------------------------------
                | Create response
                |--------------------------------------------------------------------------
                */

                if (!empty($responseData)) {

                    TechnicalCorrectionResponse::create(
                        $responseData
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Save author comments
            |--------------------------------------------------------------------------
            */

            if (
                Schema::hasColumn(
                    'technical_checks',
                    'author_comments'
                )
            ) {

                $technicalCheck->update([
                    'author_comments' =>
                        $validated['response'],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Return manuscript to Technical Review
            |--------------------------------------------------------------------------
            */

            $manuscript->update([

                'status' =>
                    'technical_check',

                'current_stage' =>
                    'technical_review',
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | 8. Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'author.manuscripts.show',
                $manuscript
            )
            ->with(
                'success',
                'Technical correction submitted successfully. Your manuscript has been returned to Technical Review.'
            );
    }
}
