<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Manuscript;
use App\Models\ManuscriptFile;
use App\Models\ManuscriptVersion;
use App\Models\TechnicalCheck;
use App\Models\TechnicalCorrectionResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TechnicalCorrectionController extends Controller
{
    /**
     * Submit technical correction.
     *
     * Workflow:
     *
     * technical_correction
     *          ↓
     * Author uploads corrected files
     *          ↓
     * New manuscript version
     *          ↓
     * Old files remain preserved
     *          ↓
     * New files linked through replacement_file_id
     *          ↓
     * Manuscript → technical_check
     *          ↓
     * Technical Review → new check
     */
    public function submit(
        Request $request,
        Manuscript $manuscript
    ) {
        /*
        |--------------------------------------------------------------------------
        | 1. Verify author ownership
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        $isOwner = false;

        /*
        | submitter_id
        */

        if (
            Schema::hasColumn('manuscripts', 'submitter_id') &&
            (int) $manuscript->submitter_id === (int) $user->id
        ) {
            $isOwner = true;
        }

        /*
        | user_id
        */

        if (
            Schema::hasColumn('manuscripts', 'user_id') &&
            (int) $manuscript->user_id === (int) $user->id
        ) {
            $isOwner = true;
        }

        /*
        | Try submitter relationship.
        */

        if (!$isOwner) {

            try {

                if (
                    $manuscript->submitter &&
                    (int) $manuscript->submitter->id === (int) $user->id
                ) {
                    $isOwner = true;
                }

            } catch (\Throwable $e) {
                // Ignore unavailable relationship.
            }
        }

        abort_unless($isOwner, 403);


        /*
        |--------------------------------------------------------------------------
        | 2. Check manuscript status
        |--------------------------------------------------------------------------
        */

        if ($manuscript->status !== 'technical_correction') {

            return back()
                ->with(
                    'error',
                    'This manuscript is not currently waiting for technical correction.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | 3. Get latest technical check
        |--------------------------------------------------------------------------
        */

        $technicalCheck = $manuscript
            ->technicalChecks()
            ->with([
                'items',
                'issues',
            ])
            ->latest('check_number')
            ->first();


        if (!$technicalCheck) {

            return back()
                ->with(
                    'error',
                    'No technical correction request was found.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | 4. Verify technical check status
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $technicalCheck->status,
                [
                    'correction_required',
                    'failed',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This technical check is not waiting for author correction.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | 5. Validate uploaded files
        |--------------------------------------------------------------------------
        |
        | Files are submitted as:
        |
        | files[OLD_FILE_ID]
        |
        */

        $validated = $request->validate([

            'files' => [
                'required',
                'array',
            ],

            'files.*' => [
                'required',
                'file',
                'max:20480',
                'mimes:pdf,doc,docx,rtf,txt',
            ],

            'comments' => [
                'nullable',
                'string',
                'max:10000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | 6. Get current manuscript files
        |--------------------------------------------------------------------------
        */

        $currentFiles = $manuscript
            ->files()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 7. Verify every selected file belongs to manuscript
        |--------------------------------------------------------------------------
        */

        foreach (
            array_keys($validated['files'])
            as $oldFileId
        ) {

            $oldFile = $currentFiles
                ->firstWhere('id', $oldFileId);

            if (!$oldFile) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Invalid manuscript file selected.'
                    );

            }
        }


        /*
        |--------------------------------------------------------------------------
        | 8. Determine next manuscript version
        |--------------------------------------------------------------------------
        */

        $latestVersion = (int) (
            $currentFiles->max(
                fn ($file) =>
                    (int) ($file->version_number ?? 1)
            ) ?? 1
        );

        $newVersionNumber = $latestVersion + 1;


        /*
        |--------------------------------------------------------------------------
        | 9. Database transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $manuscript,
            $technicalCheck,
            $validated,
            $currentFiles,
            $newVersionNumber
        ) {

            /*
            |--------------------------------------------------------------------------
            | Create Manuscript Version
            |--------------------------------------------------------------------------
            */

            $manuscriptVersion = null;

            if (
                method_exists(
                    $manuscript,
                    'versions'
                )
            ) {

                /*
                | Only send fields that actually exist.
                */

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
            | Process replacement files
            |--------------------------------------------------------------------------
            */

            foreach (
                $validated['files']
                as $oldFileId => $uploadedFile
            ) {

                $oldFile = $currentFiles
                    ->firstWhere('id', $oldFileId);

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
                | Stored filename
                |--------------------------------------------------------------------------
                */

                $extension =
                    strtolower(
                        $uploadedFile
                            ->getClientOriginalExtension()
                    );

                $storedName =
                    Str::uuid()->toString() .
                    ($extension
                        ? '.' . $extension
                        : '');


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
                | New file data
                |--------------------------------------------------------------------------
                */

                $newFileData = [

                    'manuscript_id' =>
                        $manuscript->id,

                    'file_type' =>
                        $oldFile->file_type,

                    'original_name' =>
                        $uploadedFile->getClientOriginalName(),

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

                    'replacement_file_id' =>
                        $oldFile->id,

                ];


                /*
                |--------------------------------------------------------------------------
                | manuscript_version_id
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
                | file_id
                |--------------------------------------------------------------------------
                |
                | Do not set this unless your existing system requires it.
                |
                */

                    $newFileData = [

                        'manuscript_id' =>
                            $manuscript->id,

                        'file_id' =>
                            'MF-' . strtoupper(Str::random(12)),

                        'file_type' =>
                            $oldFile->file_type,

                        'original_name' =>
                            $uploadedFile->getClientOriginalName(),

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

                        'replacement_file_id' =>
                            $oldFile->id,
                    ];


                    if ($manuscriptVersion) {

                        $newFileData['manuscript_version_id'] =
                            $manuscriptVersion->id;
                    }
                    
                /*
                |--------------------------------------------------------------------------
                | Create new file
                |--------------------------------------------------------------------------
                */

                $newFile =
                    $manuscript
                        ->files()
                        ->create($newFileData);


                /*
                |--------------------------------------------------------------------------
                | Mark previous file as replaced
                |--------------------------------------------------------------------------
                */

                $oldFile->update([
                    'status' => 'replaced',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Save author's response
            |--------------------------------------------------------------------------
            */

            if (
                !empty($validated['comments']) &&
                Schema::hasColumn(
                    'technical_checks',
                    'author_comments'
                )
            ) {

                $technicalCheck->update([
                    'author_comments' =>
                        $validated['comments'],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Update manuscript workflow
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
        | 10. Redirect
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