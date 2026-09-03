<?php

namespace App\Services;

use App\Models\Manuscript;
use App\Models\TechnicalCheck;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TechnicalCheckService
{
    /**
     * Create a new technical check for a manuscript.
     *
     * This automatically creates:
     *
     * Technical Check #1
     * Technical Check #2
     * Technical Check #3
     * etc.
     *
     * The next check number is determined automatically.
     */
    public function createForManuscript(
        Manuscript $manuscript,
        ?int $assignedTo = null
    ): TechnicalCheck {
        return DB::transaction(function () use ($manuscript, $assignedTo) {

            /*
            |--------------------------------------------------------------------------
            | Find the next check number
            |--------------------------------------------------------------------------
            */

            $lastNumber = TechnicalCheck::where(
                'manuscript_id',
                $manuscript->id
            )->max('check_number');

            $checkNumber = $lastNumber
                ? $lastNumber + 1
                : 1;


            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate active technical checks
            |--------------------------------------------------------------------------
            */

            $existingActiveCheck = TechnicalCheck::where(
                'manuscript_id',
                $manuscript->id
            )
                ->whereIn('status', [
                    'pending',
                    'in_progress',
                ])
                ->first();

            if ($existingActiveCheck) {
                return $existingActiveCheck->load([
                    'manuscript',
                    'items',
                    'issues',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create Technical Check
            |--------------------------------------------------------------------------
            */

            $technicalCheck = TechnicalCheck::create([
                'manuscript_id' => $manuscript->id,

                'check_number' => $checkNumber,

                'status' => 'pending',

                'overall_result' => null,

                'assigned_to' => $assignedTo,

                'started_by' => null,

                'completed_by' => null,

                'started_at' => null,

                'completed_at' => null,

                'comments' => null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Checklist Items
            |--------------------------------------------------------------------------
            */

            foreach ($this->checklist($manuscript) as $item) {

                $technicalCheck->items()->create([
                    'check_key' => $item['key'],

                    'check_name' => $item['name'],

                    'sort_order' => $item['sort_order'],

                    'result' => 'pending',

                    'comment' => null,

                    'checked_by' => null,

                    'checked_at' => null,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Return complete Technical Check
            |--------------------------------------------------------------------------
            */

            return $technicalCheck->load([
                'manuscript',
                'items',
                'issues',
            ]);
        });
    }


    /**
     * Get the BMRC Technical Review checklist.
     *
     * IMPORTANT:
     * Technical review checks completeness, formatting,
     * submission requirements and journal policy compliance.
     *
     * It should NOT judge the scientific quality of the manuscript.
     */
    public function checklist(Manuscript $manuscript): array
    {
        $items = [

            /*
            |--------------------------------------------------------------------------
            | 01. Article Information
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'article_type',
                'name' => 'Article type is correctly selected',
            ],

            [
                'key' => 'title',
                'name' => 'Manuscript title is complete and appropriate',
            ],

            [
                'key' => 'short_title',
                'name' => 'Short title is provided where required',
            ],

            [
                'key' => 'author_information',
                'name' => 'Author information is complete and consistent',
            ],

            [
                'key' => 'author_order',
                'name' => 'Author order is clearly provided',
            ],

            [
                'key' => 'corresponding_author',
                'name' => 'Corresponding author information is complete',
            ],


            /*
            |--------------------------------------------------------------------------
            | 02. Abstract & Keywords
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'abstract',
                'name' => 'Abstract is included',
            ],

            [
                'key' => 'abstract_length',
                'name' => 'Abstract follows the required word limit',
            ],

            [
                'key' => 'keywords',
                'name' => 'Keywords are provided',
            ],

            [
                'key' => 'keyword_count',
                'name' => 'Number of keywords follows journal requirements',
            ],


            /*
            |--------------------------------------------------------------------------
            | 03. Manuscript File
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'main_file',
                'name' => 'Main manuscript file is uploaded',
            ],

            [
                'key' => 'file_format',
                'name' => 'Manuscript file is in the required format',
            ],

            [
                'key' => 'file_naming',
                'name' => 'Uploaded files follow the required naming convention',
            ],

            [
                'key' => 'file_readability',
                'name' => 'Uploaded manuscript file is readable and complete',
            ],


            /*
            |--------------------------------------------------------------------------
            | 04. Title Page & Blinding
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'title_page',
                'name' => 'Title page is included where required',
            ],

            [
                'key' => 'blinding',
                'name' => 'Manuscript follows the required blinding policy',
            ],

            [
                'key' => 'author_identifiers',
                'name' => 'Author identifiers are handled correctly for blinded review',
            ],


            /*
            |--------------------------------------------------------------------------
            | 05. Manuscript Structure
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'manuscript_structure',
                'name' => 'Manuscript follows the required structure',
            ],

            [
                'key' => 'headings',
                'name' => 'Required headings and sections are present',
            ],

            [
                'key' => 'word_count',
                'name' => 'Word count follows article requirements',
            ],

            [
                'key' => 'number_of_tables',
                'name' => 'Number of tables is correctly declared',
            ],

            [
                'key' => 'number_of_figures',
                'name' => 'Number of figures is correctly declared',
            ],

            [
                'key' => 'number_of_references',
                'name' => 'Number of references is correctly declared',
            ],


            /*
            |--------------------------------------------------------------------------
            | 06. Tables
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'tables',
                'name' => 'Tables are complete and properly formatted',
            ],

            [
                'key' => 'table_numbering',
                'name' => 'Tables are numbered correctly',
            ],

            [
                'key' => 'table_references',
                'name' => 'All tables are appropriately cited in the manuscript',
            ],


            /*
            |--------------------------------------------------------------------------
            | 07. Figures
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'figures',
                'name' => 'Figures are complete and properly labelled',
            ],

            [
                'key' => 'figure_numbering',
                'name' => 'Figures are numbered correctly',
            ],

            [
                'key' => 'figure_references',
                'name' => 'All figures are appropriately cited in the manuscript',
            ],

            [
                'key' => 'figure_quality',
                'name' => 'Figure files meet the required quality and format',
            ],


            /*
            |--------------------------------------------------------------------------
            | 08. Supplementary Files
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'supplementary_files',
                'name' => 'Supplementary files are included where required',
            ],

            [
                'key' => 'supplementary_file_format',
                'name' => 'Supplementary files meet required format',
            ],


            /*
            |--------------------------------------------------------------------------
            | 09. References
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'references',
                'name' => 'References follow journal requirements',
            ],

            [
                'key' => 'reference_consistency',
                'name' => 'In-text citations and reference list are consistent',
            ],

            [
                'key' => 'reference_numbering',
                'name' => 'Reference numbering/order is correct where applicable',
            ],


            /*
            |--------------------------------------------------------------------------
            | 10. Ethics
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'ethical_approval',
                'name' => 'Ethical approval information is provided where required',
            ],

            [
                'key' => 'ethics_document',
                'name' => 'Ethical approval document is uploaded where required',
            ],

            [
                'key' => 'informed_consent',
                'name' => 'Informed consent information is provided where required',
            ],

            [
                'key' => 'consent_document',
                'name' => 'Consent documentation is provided where required',
            ],


            /*
            |--------------------------------------------------------------------------
            | 11. Conflict of Interest
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'conflict_of_interest',
                'name' => 'Conflict of interest declaration is provided',
            ],


            /*
            |--------------------------------------------------------------------------
            | 12. Funding
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'funding',
                'name' => 'Funding information is provided',
            ],


            /*
            |--------------------------------------------------------------------------
            | 13. Author Contribution
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'author_contribution',
                'name' => 'Author contribution statement is provided',
            ],


            /*
            |--------------------------------------------------------------------------
            | 14. Data Availability
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'data_availability',
                'name' => 'Data availability statement is provided where required',
            ],


            /*
            |--------------------------------------------------------------------------
            | 15. Clinical Trial Registration
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'trial_registration',
                'name' => 'Clinical trial registration is provided where required',
            ],


            /*
            |--------------------------------------------------------------------------
            | 16. Declarations
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'declarations',
                'name' => 'Required declarations are complete',
            ],

            [
                'key' => 'acknowledgement',
                'name' => 'Acknowledgement information is provided where applicable',
            ],


            /*
            |--------------------------------------------------------------------------
            | 17. Submission Completeness
            |--------------------------------------------------------------------------
            */

            [
                'key' => 'submission_completeness',
                'name' => 'Submission is complete',
            ],

            [
                'key' => 'required_files',
                'name' => 'All required submission files are uploaded',
            ],

            [
                'key' => 'metadata_consistency',
                'name' => 'Submission metadata is consistent with the manuscript',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | Add Sort Order
        |--------------------------------------------------------------------------
        */

        foreach ($items as $index => &$item) {

            $item['sort_order'] = $index + 1;
        }

        unset($item);


        return $items;
    }


    /**
     * Get the next technical check number for a manuscript.
     */
    public function nextCheckNumber(Manuscript $manuscript): int
    {
        $lastNumber = TechnicalCheck::where(
            'manuscript_id',
            $manuscript->id
        )->max('check_number');

        return $lastNumber
            ? $lastNumber + 1
            : 1;
    }


    /**
     * Determine whether a manuscript already has
     * an active technical check.
     */
    public function hasActiveCheck(Manuscript $manuscript): bool
    {
        return TechnicalCheck::where(
            'manuscript_id',
            $manuscript->id
        )
            ->whereIn('status', [
                'pending',
                'in_progress',
            ])
            ->exists();
    }


    /**
     * Get the latest technical check.
     */
    public function latestCheck(
        Manuscript $manuscript
    ): ?TechnicalCheck {

        return TechnicalCheck::where(
            'manuscript_id',
            $manuscript->id
        )
            ->orderByDesc('check_number')
            ->with([
                'items',
                'issues',
                'assignedTo',
                'startedBy',
                'completedBy',
            ])
            ->first();
    }


    /**
     * Get all technical check history.
     */
    public function history(
        Manuscript $manuscript
    ) {

        return TechnicalCheck::where(
            'manuscript_id',
            $manuscript->id
        )
            ->with([
                'items',
                'issues',
                'assignedTo',
                'startedBy',
                'completedBy',
            ])
            ->orderByDesc('check_number')
            ->get();
    }


    /**
     * Validate that a technical check belongs
     * to the supplied manuscript.
     */
    public function validateOwnership(
        TechnicalCheck $technicalCheck,
        Manuscript $manuscript
    ): void {

        if (
            (int) $technicalCheck->manuscript_id
            !==
            (int) $manuscript->id
        ) {
            throw new RuntimeException(
                'This technical check does not belong to the manuscript.'
            );
        }
    }
}