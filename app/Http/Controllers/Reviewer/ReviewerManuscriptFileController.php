<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ManuscriptFile;
use App\Models\ReviewerInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReviewerManuscriptFileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Download Reviewer File
    |--------------------------------------------------------------------------
    */

    public function download(
        int $invitation,
        int $file
    ) {
        [
            $reviewInvitation,
            $manuscriptFile,
            $filePath,
            $fileType,
        ] = $this->getAuthorizedReviewerFile(
            $invitation,
            $file
        );

        /*
        |--------------------------------------------------------------------------
        | File Extension
        |--------------------------------------------------------------------------
        */

        $extension = strtolower(
            (string) pathinfo(
                $filePath,
                PATHINFO_EXTENSION
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Manuscript Number
        |--------------------------------------------------------------------------
        */

        $manuscriptNumber =
            $reviewInvitation
                ->manuscript
                ?->manuscript_id
            ?? $reviewInvitation->manuscript_id;

        $safeManuscriptNumber = preg_replace(
            '/[^A-Za-z0-9\-_]/',
            '-',
            (string) $manuscriptNumber
        );

        /*
        |--------------------------------------------------------------------------
        | Anonymous File Type Label
        |--------------------------------------------------------------------------
        */

        $typeLabel = match ($fileType) {

            'main manuscript',
            'main_manuscript',
            'manuscript',
            'article',
            'article_file',
            'manuscript_file',
            'main_file'
                => 'Manuscript',

            'table',
            'tables'
                => 'Table',

            'figure',
            'figures'
                => 'Figure',

            'supplementary',
            'supplementary file',
            'supplementary_file',
            'supplementary files',
            'supplementary_files'
                => 'Supplementary',

            default
                => 'File',
        };

        /*
        |--------------------------------------------------------------------------
        | Safe Anonymous Download Name
        |--------------------------------------------------------------------------
        */

        $safeFileName =
            'BMRC-' .
            $safeManuscriptNumber .
            '-' .
            $typeLabel .
            '-' .
            $manuscriptFile->id;

        if (!empty($extension)) {
            $safeFileName .= '.' . $extension;
        }

        /*
        |--------------------------------------------------------------------------
        | Download Original File
        |--------------------------------------------------------------------------
        */

        return Storage::disk('public')->download(
            $filePath,
            $safeFileName
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BMRC Preview Page
    |--------------------------------------------------------------------------
    |
    | This method does NOT directly return the PDF.
    |
    | Instead it loads a normal BMRC Blade page so:
    |
    | - BMRC favicon is displayed
    | - BMRC page title is displayed
    | - Reviewer layout is retained
    | - Back / Download buttons can be displayed
    |
    */

    public function view(
        int $invitation,
        int $file
    ) {
        [
            $reviewInvitation,
            $manuscriptFile,
            $filePath,
            $fileType,
        ] = $this->getAuthorizedReviewerFile(
            $invitation,
            $file
        );

        /*
        |--------------------------------------------------------------------------
        | Reviewer-safe Display Name
        |--------------------------------------------------------------------------
        */

        $displayName = match ($fileType) {

            'main manuscript',
            'main_manuscript',
            'manuscript',
            'article',
            'article_file',
            'manuscript_file',
            'main_file'
                => 'Main Manuscript',

            'table',
            'tables'
                => 'Table',

            'figure',
            'figures'
                => 'Figure',

            'supplementary',
            'supplementary file',
            'supplementary_file',
            'supplementary files',
            'supplementary_files'
                => 'Supplementary File',

            default
                => 'Manuscript File',
        };

        return view(
            'reviewer.peer-reviews.file-preview',
            [
                'invitation' => $reviewInvitation,
                'file' => $manuscriptFile,
                'displayName' => $displayName,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Actual Preview Content
    |--------------------------------------------------------------------------
    |
    | This URL is loaded inside the iframe of the BMRC preview page.
    |
    | IMPORTANT:
    | Authorization is checked again here.
    |
    */

    public function previewContent(
        int $invitation,
        int $file
    ) {
        [
            $reviewInvitation,
            $manuscriptFile,
            $filePath,
            $fileType,
        ] = $this->getAuthorizedReviewerFile(
            $invitation,
            $file
        );

        /*
        |--------------------------------------------------------------------------
        | Determine Extension
        |--------------------------------------------------------------------------
        */

        $extension = strtolower(
            (string) pathinfo(
                $filePath,
                PATHINFO_EXTENSION
            )
        );

        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        if ($extension === 'pdf') {

            $absolutePath =
                Storage::disk('public')
                    ->path($filePath);

            return response()->file(
                $absolutePath,
                [
                    'Content-Type' =>
                        'application/pdf',

                    'Content-Disposition' =>
                        'inline; filename="BMRC-Manuscript-Preview.pdf"',

                    'X-Content-Type-Options' =>
                        'nosniff',

                    'Cache-Control' =>
                        'private, no-store, no-cache, must-revalidate',

                    'Pragma' =>
                        'no-cache',
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Images
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $extension,
                [
                    'jpg',
                    'jpeg',
                    'png',
                    'gif',
                    'webp',
                ],
                true
            )
        ) {

            $absolutePath =
                Storage::disk('public')
                    ->path($filePath);

            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];

            return response()->file(
                $absolutePath,
                [
                    'Content-Type' =>
                        $mimeTypes[$extension]
                        ?? 'application/octet-stream',

                    'Content-Disposition' =>
                        'inline; filename="BMRC-Manuscript-Image.' .
                        $extension .
                        '"',

                    'X-Content-Type-Options' =>
                        'nosniff',

                    'Cache-Control' =>
                        'private, no-store, no-cache, must-revalidate',
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DOC / DOCX
        |--------------------------------------------------------------------------
        |
        | Convert Word document to PDF using LibreOffice.
        |
        */

        if (
            in_array(
                $extension,
                ['doc', 'docx'],
                true
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | LibreOffice Executable
            |--------------------------------------------------------------------------
            */

            $libreOffice =
                config('services.libreoffice.path');

            abort_if(
                empty($libreOffice),
                500,
                'LibreOffice path is not configured.'
            );

            abort_unless(
                file_exists($libreOffice),
                500,
                'LibreOffice executable could not be found.'
            );


            /*
            |--------------------------------------------------------------------------
            | Source Word File
            |--------------------------------------------------------------------------
            */

            $sourcePath =
                Storage::disk('public')
                    ->path($filePath);

            abort_unless(
                file_exists($sourcePath),
                404,
                'The source manuscript file could not be found.'
            );


            /*
            |--------------------------------------------------------------------------
            | Private Preview Directory
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | storage/app/reviewer-previews/2/1/
            |
            */

            $previewDirectory =
                storage_path(
                    'app/reviewer-previews/' .
                    $reviewInvitation->manuscript_id .
                    '/' .
                    $manuscriptFile->id
                );

            if (!is_dir($previewDirectory)) {

                $created = mkdir(
                    $previewDirectory,
                    0755,
                    true
                );

                abort_unless(
                    $created || is_dir($previewDirectory),
                    500,
                    'The preview directory could not be created.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Generated PDF Path
            |--------------------------------------------------------------------------
            */

            $sourceBaseName = pathinfo(
                $sourcePath,
                PATHINFO_FILENAME
            );

            $generatedPdfPath =
                $previewDirectory .
                DIRECTORY_SEPARATOR .
                $sourceBaseName .
                '.pdf';


            /*
            |--------------------------------------------------------------------------
            | Determine Whether Conversion Is Required
            |--------------------------------------------------------------------------
            */

            $needsConversion =
                !file_exists($generatedPdfPath)
                ||
                filemtime($sourcePath) >
                filemtime($generatedPdfPath);


            /*
            |--------------------------------------------------------------------------
            | Convert Word → PDF
            |--------------------------------------------------------------------------
            */

            if ($needsConversion) {

                /*
                |--------------------------------------------------------------------------
                | Remove Old Preview
                |--------------------------------------------------------------------------
                */

                if (file_exists($generatedPdfPath)) {
                    @unlink($generatedPdfPath);
                }


                /*
                |--------------------------------------------------------------------------
                | LibreOffice Command
                |--------------------------------------------------------------------------
                */

                $command =
                    escapeshellarg($libreOffice) .
                    ' --headless' .
                    ' --convert-to pdf' .
                    ' --outdir ' .
                    escapeshellarg($previewDirectory) .
                    ' ' .
                    escapeshellarg($sourcePath);


                /*
                |--------------------------------------------------------------------------
                | Execute Conversion
                |--------------------------------------------------------------------------
                */

                $output = [];
                $exitCode = 0;

                exec(
                    $command . ' 2>&1',
                    $output,
                    $exitCode
                );


                /*
                |--------------------------------------------------------------------------
                | Verify Conversion
                |--------------------------------------------------------------------------
                */

                if (
                    $exitCode !== 0
                    ||
                    !file_exists($generatedPdfPath)
                ) {

                    Log::error(
                        'Reviewer manuscript PDF conversion failed.',
                        [
                            'manuscript_id' =>
                                $reviewInvitation
                                    ->manuscript_id,

                            'file_id' =>
                                $manuscriptFile->id,

                            'source' =>
                                $sourcePath,

                            'expected_pdf' =>
                                $generatedPdfPath,

                            'exit_code' =>
                                $exitCode,

                            'output' =>
                                $output,
                        ]
                    );

                    abort(
                        500,
                        'The manuscript preview could not be generated.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Return Generated PDF
            |--------------------------------------------------------------------------
            */

            return response()->file(
                $generatedPdfPath,
                [
                    'Content-Type' =>
                        'application/pdf',

                    'Content-Disposition' =>
                        'inline; filename="BMRC-Manuscript-Preview.pdf"',

                    'X-Content-Type-Options' =>
                        'nosniff',

                    'Cache-Control' =>
                        'private, no-store, no-cache, must-revalidate',

                    'Pragma' =>
                        'no-cache',
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Unsupported Preview Type
        |--------------------------------------------------------------------------
        */

        abort(
            415,
            'Preview is not available for this file type.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Authorized Reviewer File
    |--------------------------------------------------------------------------
    |
    | Shared security method.
    |
    | Both:
    |
    | - view
    | - previewContent
    | - download
    |
    | must pass through this method.
    |
    */

    private function getAuthorizedReviewerFile(
        int $invitation,
        int $file
    ): array {

        /*
        |--------------------------------------------------------------------------
        | 1. Authenticated Reviewer
        |--------------------------------------------------------------------------
        */

        $reviewer =
            Auth::guard('reviewer')->user();

        abort_unless(
            $reviewer,
            403,
            'Reviewer authentication required.'
        );


        /*
        |--------------------------------------------------------------------------
        | 2. Accepted Invitation
        |--------------------------------------------------------------------------
        */

        $reviewInvitation =
            ReviewerInvitation::query()
                ->with('manuscript')
                ->whereKey($invitation)
                ->where(
                    'reviewer_id',
                    $reviewer->id
                )
                ->where(
                    'status',
                    'accepted'
                )
                ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | 3. Review Deadline
        |--------------------------------------------------------------------------
        */

        if (
            $reviewInvitation->review_deadline
            &&
            $reviewInvitation
                ->review_deadline
                ->isPast()
        ) {

            abort(
                403,
                'The review deadline has passed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Requested File
        |--------------------------------------------------------------------------
        |
        | The file must belong to the manuscript attached
        | to this review invitation.
        |
        */

        $manuscriptFile =
            ManuscriptFile::query()
                ->whereKey($file)
                ->where(
                    'manuscript_id',
                    $reviewInvitation->manuscript_id
                )
                ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | 5. Normalize File Type
        |--------------------------------------------------------------------------
        */

        $fileType = strtolower(
            trim(
                (string) $manuscriptFile->file_type
            )
        );


        /*
        |--------------------------------------------------------------------------
        | 6. Explicitly Block Author-identifying Files
        |--------------------------------------------------------------------------
        */

        $blockedFileTypes = [

            'title page',
            'title_page',

            'cover letter',
            'cover_letter',

            'author information',
            'author_information',

            'author details',
            'author_details',

            'authorship form',
            'authorship_form',

            'author declaration',
            'author_declaration',

            'copyright form',
            'copyright_form',

            'conflict of interest',
            'conflict_of_interest',

            'funding information',
            'funding_information',

            'ethics approval',
            'ethics_approval',

            'irb approval',
            'irb_approval',

            'consent form',
            'consent_form',

            'declaration form',
            'declaration_form',
        ];

        abort_if(
            in_array(
                $fileType,
                $blockedFileTypes,
                true
            ),
            403,
            'Author-identifying files are not available for peer review.'
        );


        /*
        |--------------------------------------------------------------------------
        | 7. Reviewer-safe File Types
        |--------------------------------------------------------------------------
        */

        $allowedFileTypes = [

            /*
            | Main Manuscript
            */

            'main manuscript',
            'main_manuscript',
            'manuscript',
            'article',
            'article_file',
            'manuscript_file',
            'main_file',

            /*
            | Tables
            */

            'table',
            'tables',

            /*
            | Figures
            */

            'figure',
            'figures',

            /*
            | Supplementary
            */

            'supplementary',
            'supplementary file',
            'supplementary_file',
            'supplementary files',
            'supplementary_files',
        ];

        abort_unless(
            in_array(
                $fileType,
                $allowedFileTypes,
                true
            ),
            403,
            'This file is not available for peer review.'
        );


        /*
        |--------------------------------------------------------------------------
        | 8. File Status
        |--------------------------------------------------------------------------
        */

        $fileStatus = strtolower(
            trim(
                (string) (
                    $manuscriptFile->status ?? ''
                )
            )
        );

        $blockedStatuses = [
            'deleted',
            'replaced',
            'inactive',
            'archived',
            'removed',
        ];

        abort_if(
            in_array(
                $fileStatus,
                $blockedStatuses,
                true
            ),
            403,
            'This manuscript file is no longer available.'
        );


        /*
        |--------------------------------------------------------------------------
        | 9. File Path
        |--------------------------------------------------------------------------
        */

        $filePath = trim(
            (string) (
                $manuscriptFile->file_path ?? ''
            )
        );

        abort_if(
            empty($filePath),
            404,
            'The manuscript file path is unavailable.'
        );


        /*
        |--------------------------------------------------------------------------
        | 10. Normalize File Path
        |--------------------------------------------------------------------------
        */

        $filePath = str_replace(
            '\\',
            '/',
            $filePath
        );

        if (
            str_starts_with(
                $filePath,
                '/storage/'
            )
        ) {

            $filePath = substr(
                $filePath,
                strlen('/storage/')
            );
        }

        if (
            str_starts_with(
                $filePath,
                'storage/'
            )
        ) {

            $filePath = substr(
                $filePath,
                strlen('storage/')
            );
        }

        $filePath = ltrim(
            $filePath,
            '/'
        );


        /*
        |--------------------------------------------------------------------------
        | 11. Verify Physical File
        |--------------------------------------------------------------------------
        */

        abort_unless(
            Storage::disk('public')
                ->exists($filePath),
            404,
            'The manuscript file could not be found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Return Validated Information
        |--------------------------------------------------------------------------
        */

        return [
            $reviewInvitation,
            $manuscriptFile,
            $filePath,
            $fileType,
        ];
    }
}