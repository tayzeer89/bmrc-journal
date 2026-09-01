<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\ArticleType;
use App\Models\Manuscript;
use App\Models\ManuscriptFile;
use App\Models\Affiliation;
use App\Models\AuthorContribution;
use App\Models\ManuscriptAuthor;
use App\Models\DataAvailability;
use App\Models\Acknowledgement;
use App\Models\SubmissionChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;




class SubmissionController extends Controller
{


    /**
     * Step 1 Form
     * Article Information
     */
    public function create()
    {

        $journals = Journal::where('is_active', true)
            ->orderBy('name')
            ->get();


        $articleTypes = ArticleType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();



        return view(
            'author.submission.step1',
            compact(
                'journals',
                'articleTypes'
            )
        );

    }



    /**
     * Store Step 1
     * Create Manuscript Draft
     */


    public function storeStep1(Request $request)
    {


        $validated = $request->validate([


            'journal_id' => [
                'required',
                'exists:journals,id'
            ],


            'article_type_id' => [
                'required',
                'exists:article_types,id'
            ],


            'title' => [
                'required',
                'string',
                'max:500'
            ],


            'short_title' => [
                'nullable',
                'string',
                'max:255'
            ],


            'abstract' => [
                'required',
                'string'
            ],


            'keywords' => [
                'nullable',
                'string'
            ],


            'subject_category' => [
                'nullable',
                'string'
            ],


            'subcategory' => [
                'nullable',
                'string'
            ],


            'language' => [
                'nullable',
                'string'
            ],


            'word_count' => [
                'nullable',
                'integer'
            ],


            'number_of_tables' => [
                'nullable',
                'integer'
            ],


            'number_of_figures' => [
                'nullable',
                'integer'
            ],


            'number_of_references' => [
                'nullable',
                'integer'
            ],


        ]);




        DB::beginTransaction();


        try {


            /*
            |--------------------------------------------------------------------------
            | Generate BMRC Manuscript ID
            |--------------------------------------------------------------------------
            */


            $year = date('Y');


            $serial = Manuscript::withTrashed()
                ->count() + 1;



            $manuscriptId =
                'BMRC-' .
                $year .
                '-' .
                str_pad(
                    $serial,
                    5,
                    '0',
                    STR_PAD_LEFT
                );




            /*
            |--------------------------------------------------------------------------
            | Create Manuscript Draft
            |--------------------------------------------------------------------------
            */


            $manuscript = Manuscript::create([


                'manuscript_id'
                    => $manuscriptId,


                'submitted_by'
                    => Auth::id(),


                'journal_id'
                    => $validated['journal_id'],


                'article_type_id'
                    => $validated['article_type_id'],


                'title'
                    => $validated['title'],


                'short_title'
                    => $validated['short_title'] ?? null,


                'abstract'
                    => $validated['abstract'],



                'keywords'
                    => $this->formatKeywords(
                        $validated['keywords'] ?? null
                    ),



                'subject_category'
                    => $validated['subject_category'] ?? null,



                'subcategory'
                    => $validated['subcategory'] ?? null,



                'language'
                    => $validated['language'] ?? 'English',



                'word_count'
                    => $validated['word_count'] ?? null,



                'number_of_tables'
                    => $validated['number_of_tables'] ?? 0,



                'number_of_figures'
                    => $validated['number_of_figures'] ?? 0,



                'number_of_references'
                    => $validated['number_of_references'] ?? 0,


                /*
                |--------------------------------------------------------------------------
                | Draft Tracking
                |--------------------------------------------------------------------------
                */

                'status'
                    => 'draft',

                'completion_percentage'
                    => 8,

                'last_step'
                    => 1,

                'draft_saved_at'
                    => now(),


                'submission_version'
                     => '1.0',


            ]);




            DB::commit();




        return redirect()
            ->route(
                'author.submission.step2',
                [
                    'manuscript' => $manuscript->id
                ]
            )
            ->with(
                'success',
                'Manuscript created successfully.'
            );



        }
        catch(\Exception $e)
        {


            DB::rollBack();


            return back()

                ->withInput()

                ->with(
                    'error',
                    $e->getMessage()
                );


        }



    }

    /**

* Step 2 Form
* Scientific & Study Information
  */
  public function step2(Manuscript $manuscript)
  {
  // Security: only the submitting author can access this manuscript
  abort_if(
  $manuscript->submitted_by != auth()->id(),
  403
  );

  // Load Step 2 details
  $manuscript->load('details');

  return view(
  'author.submission.step2',
  compact('manuscript')
  );
  }

/**
* Store Step 2
* Scientific & Study Information
  */
  public function storeStep2(
  Request $request,
  Manuscript $manuscript
  ) {
        // Security
        abort_if(
        $manuscript->submitted_by != auth()->id(),
        403
        );

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Scientific Information
            |--------------------------------------------------------------------------
            */

            'background' => [
                'nullable',
                'string'
            ],

            'objective' => [
                'nullable',
                'string'
            ],

            'methods' => [
                'nullable',
                'string'
            ],

            'results' => [
                'nullable',
                'string'
            ],

            'conclusion' => [
                'nullable',
                'string'
            ],


   /*
   |--------------------------------------------------------------------------
   | Trial Registration
   |--------------------------------------------------------------------------
   */

        'trial_registration_number' => [
            'nullable',
            'string',
            'max:255'
        ],

        'trial_registration_organization' => [
            'nullable',
            'string',
            'max:255'
        ],


   /*
   |--------------------------------------------------------------------------
   | Study Design
   |--------------------------------------------------------------------------
   */

        'study_design' => [
            'nullable',
            'string',
            'max:255'
        ],

        'other_study_design' => [
            'nullable',
            'string',
            'max:255'
        ],


   /*
   |--------------------------------------------------------------------------
   | Study Period
   |--------------------------------------------------------------------------
   */

        'study_start_date' => [
            'nullable',
            'date'
        ],

        'study_end_date' => [
            'nullable',
            'date',
            'after_or_equal:study_start_date'
        ],


   /*
   |--------------------------------------------------------------------------
   | Study Information
   |--------------------------------------------------------------------------
   */

        'study_location' => [
            'nullable',
            'string',
            'max:500'
        ],

        'sample_size' => [
            'nullable',
            'integer',
            'min:0'
        ],


   /*
   |--------------------------------------------------------------------------
   | Funding
   |--------------------------------------------------------------------------
   */

        'funding_source' => [
            'nullable',
            'string',
            'max:255'
        ],

        'other_funding_source' => [
            'nullable',
            'string',
            'max:255'
        ],


   /*
   |--------------------------------------------------------------------------
   | Ethical Approval
   |--------------------------------------------------------------------------
   */

        'ethical_approval_available' => [
            'nullable',
            'boolean'
        ],

        'ethical_approval_number' => [
            'nullable',
            'string',
            'max:255'
        ],

        'ethical_approval_date' => [
            'nullable',
            'date'
        ],

    ]);


/*
|--------------------------------------------------------------------------
| Ethical Approval Checkbox
|--------------------------------------------------------------------------
*/

  $validated['ethical_approval_available'] =
  $request->boolean('ethical_approval_available');

     /*                                                                         
  | -------------------------------------------------------------------------- 
  | Clear Other Study Design if not selected                                   
  | -------------------------------------------------------------------------- 
  */                                                                         

  if ($validated['study_design'] !== 'Other') {

   $validated['other_study_design'] = null;

  }

  /*                                                                         
  | -------------------------------------------------------------------------- 
  | Clear Other Funding Source if not selected                                 
  | -------------------------------------------------------------------------- 
 */                                                                         

  if ($validated['funding_source'] !== 'Other') {


   $validated['other_funding_source'] = null;


  }

  /*                                                                         
  | -------------------------------------------------------------------------- 
  | Save Step 2 Details                                                        
  | -------------------------------------------------------------------------- 
  */                                                                         

  DB::transaction(function () use (
  $manuscript,
  $validated
  ) {

   $manuscript->details()->updateOrCreate(

       [
           'manuscript_id' => $manuscript->id
       ],

       $validated

   );


   /*
   |--------------------------------------------------------------------------
   | Update Draft Progress
   |--------------------------------------------------------------------------
   */

   $manuscript->update([

       'completion_percentage' => 15,

       'last_step' => 2,

       'draft_saved_at' => now(),

   ]);


  });

   /*                                                                         
  | -------------------------------------------------------------------------- 
  | Continue to Step 3                                                         
  | -------------------------------------------------------------------------- 
  */                                                                         

  return redirect()

   ->route(
       'author.submission.step3',
       $manuscript->id
   )

   ->with(
       'success',
       'Manuscript scientific information saved successfully.'
   );

}


    /*
    |--------------------------------------------------------------------------
    | Step 3 Authors
    |--------------------------------------------------------------------------
    */

    public function step3(Manuscript $manuscript)
        {
            return view(
                'author.submission.step3',
                compact('manuscript')
            );
        }


    /**
     * Store Step 3 Authors
     */


    public function storeStep3( Request $request, Manuscript $manuscript)
        {


            $request->validate([

            'authors'=>'required|array',

            'authors.*.first_name'=>'required',
            'authors.*.last_name'=>'required',
            'authors.*.email'=>'required|email',
            'authors.*.institution'=>'required',
            'authors.*.country'=>'required',

            ]);



            foreach($request->authors as $index=>$author)
            {


            $manuscript->authors()->create([


            'author_order'=>$index+1,

            'title'=>$author['title'] ?? null,

            'first_name'=>$author['first_name'],

            'middle_name'=>$author['middle_name'] ?? null,

            'last_name'=>$author['last_name'],

            'full_name'=>
            trim(
            ($author['first_name'] ?? '')
            .' '.
            ($author['last_name'] ?? '')
            ),


            'email'=>$author['email'],

            'mobile'=>$author['mobile'] ?? null,


            'institution'=>$author['institution'],

            'department'=>$author['department'] ?? null,

            'designation'=>$author['designation'] ?? null,


            'country'=>$author['country'],


            'orcid'=>$author['orcid'] ?? null,


            'is_corresponding'=>
            $author['is_corresponding'] ?? 0,


            'confirmation_status'=>'pending'


            ]);


            }


            /*
            |--------------------------------------------------------------------------
            | Update Submission Progress
            |--------------------------------------------------------------------------
            */

            $manuscript->update([

                'completion_percentage' => 23,

                'last_step' => 3,

                'draft_saved_at' => now(),

            ]);



            return redirect()

            ->route(
            'author.submission.step4',
            $manuscript->id
            )

            ->with(
            'success',
            'Authors saved successfully.'
            );


    }


        /**
             * Step 4
             * Author Contribution & Affiliation
             */

            public function step4(Manuscript $manuscript)
                {

                    $authors = $manuscript
                        ->authors()
                        ->get();


                    $affiliations = Affiliation::orderBy(
                        'institution_name'
                    )->get();



                    return view(
                        'author.submission.step4',
                        compact(
                            'manuscript',
                            'authors',
                            'affiliations'
                        )
                    );

                }

            

        public function storeStep4(Request $request, Manuscript $manuscript)
        {


            $data = $request->input('authors');


            foreach($data as $authorId=>$authorData)
            {


                /*
                |--------------------------------------------------------------------------
                | Save CRediT Contribution
                |--------------------------------------------------------------------------
                */


                $contribution =
                $authorData['contribution'] ?? [];



                \App\Models\AuthorContribution::updateOrCreate(

                    [
                        'manuscript_author_id'=>$authorId
                    ],

                    [

                    'conceptualization'=>
                    in_array('conceptualization',$contribution),


                    'methodology'=>
                    in_array('methodology',$contribution),


                    'software'=>
                    in_array('software',$contribution),


                    'validation'=>
                    in_array('validation',$contribution),


                    'formal_analysis'=>
                    in_array('formal_analysis',$contribution),


                    'investigation'=>
                    in_array('investigation',$contribution),


                    'resources'=>
                    in_array('resources',$contribution),


                    'data_curation'=>
                    in_array('data_curation',$contribution),


                    'writing_original_draft'=>
                    in_array('writing_original_draft',$contribution),


                    'writing_review_editing'=>
                    in_array('writing_review_editing',$contribution),


                    'visualization'=>
                    in_array('visualization',$contribution),


                    'supervision'=>
                    in_array('supervision',$contribution),


                    'project_administration'=>
                    in_array('project_administration',$contribution),


                    'funding_acquisition'=>
                    in_array('funding_acquisition',$contribution),

                    ]

                );




                /*
                |--------------------------------------------------------------------------
                | Save Affiliations
                |--------------------------------------------------------------------------
                */


                if(isset($authorData['affiliation']))
                {


                    foreach($authorData['affiliation'] as $affiliationData)
                    {



                        $affiliation =
                        \App\Models\Affiliation::create([


                            'institution_name'=>
                            $affiliationData['institution_name'] ?? null,


                            'faculty_institute'=>
                            $affiliationData['faculty_institute'] ?? null,


                            'department'=>
                            $affiliationData['department'] ?? null,


                            'designation'=>
                            $affiliationData['designation'] ?? null,


                            'address'=>
                            $affiliationData['address'] ?? null,


                            'city'=>
                            $affiliationData['city'] ?? null,


                            'country'=>
                            $affiliationData['country'] ?? 'Bangladesh',


                            'postal_code'=>
                            $affiliationData['postal_code'] ?? null,


                            'institution_email'=>
                            $affiliationData['institution_email'] ?? null,


                            'institution_website'=>
                            $affiliationData['institution_website'] ?? null,


                        ]);





                        /*
                        |--------------------------------------------------------------------------
                        | Save Pivot
                        |--------------------------------------------------------------------------
                        */


                        \DB::table('author_affiliations')
                        ->insert([


                            'manuscript_author_id'=>$authorId,


                            'affiliation_id'=>$affiliation->id,


                            'affiliation_order'=>1,


                            'created_at'=>now(),


                            'updated_at'=>now(),

                        ]);



                    }


                }



            }

            /*
            |--------------------------------------------------------------------------
            | Update Submission Progress
            |--------------------------------------------------------------------------
            */


            $manuscript->update([

                'completion_percentage'=>31,

                'last_step'=>4,

                'draft_saved_at'=>now(),

            ]);



            return redirect()

                ->route(
                    'author.submission.step5',
                    $manuscript->id
                )

                ->with(
                    'success',
                    'Author contribution and affiliation saved successfully.'
                );


        }


        /**
         * Step 5
         * Corresponding Author Declaration
         */

            public function step5(Manuscript $manuscript)
            {

                $authors = $manuscript
                    ->authors()
                    ->get();


                $correspondingAuthor = $manuscript
                    ->authors()
                    ->where('is_corresponding', true)
                    ->first();



                return view(
                    'author.submission.step5',
                    compact(
                        'manuscript',
                        'authors',
                        'correspondingAuthor'
                    )
                );

            }




        /**
         * Store Step 5
         * Corresponding Author Declaration
         */
        public function storeStep5(Request $request, Manuscript $manuscript)
        {


            $validated = $request->validate([


                'preferred_communication_method'=>[
                    'required',
                    'string'
                ],


                'available_for_editorial_communication'=>[
                    'required'
                ],


                'declaration_confirmed'=>[
                    'accepted'
                ],


            ]);




            \DB::table('corresponding_author_declarations')
            ->updateOrInsert(

                [

                'manuscript_id'=>$manuscript->id

                ],


                [

                'manuscript_author_id'=>
                    $request->manuscript_author_id,


                'preferred_communication_method'=>
                    $validated['preferred_communication_method'],


                'available_for_editorial_communication'=>
                    $validated['available_for_editorial_communication'],


                'declaration_confirmed'=>true,


                'created_at'=>now(),


                'updated_at'=>now(),


                ]

            );




             /*
            |--------------------------------------------------------------------------
            | Update Draft Progress
            |--------------------------------------------------------------------------
            */


            $manuscript->update([

                'completion_percentage'=>38,

                'last_step'=>5,

                'draft_saved_at'=>now(),

            ]);




            return redirect()

                ->route(
                    'author.submission.step6',
                    $manuscript->id
                )

                ->with(
                    'success',
                    'Corresponding author declaration saved.'
                );


        }


        


        /**
         * Step 6
         * Manuscript File Upload Form
         */
        public function step6(Manuscript $manuscript)
        {

            return view(
                'author.submission.step6',
                compact('manuscript')
            );

        }



    /**
     * Store Step 6
     * Upload Manuscript Files
     */
         public function storeStep6(Request $request, Manuscript $manuscript)
            {


                $request->validate([

                    'main_manuscript'=>'required|file|max:10240',

                    'title_page'=>'nullable|file|max:10240',

                    'cover_letter'=>'nullable|file|max:10240',

                    'tables'=>'nullable|file|max:10240',

                    'figures'=>'nullable|file|max:10240',

                ]);




                $files = [

                    'main_manuscript'=>'Main Manuscript',

                    'title_page'=>'Title Page',

                    'cover_letter'=>'Cover Letter',

                    'tables'=>'Tables',

                    'figures'=>'Figures',

                ];




                foreach($files as $field=>$type)
                {


                    if($request->hasFile($field))
                    {


                        $file = $request->file($field);



                        $storedName = time().'_'.$file->getClientOriginalName();



                        $path = $file->storeAs(

                            'manuscripts/'.$manuscript->id,

                            $storedName,

                            'public'

                        );




                        ManuscriptFile::create([


                            'manuscript_id'=>$manuscript->id,


                            'file_id'=>'BMRC-FILE-'.strtoupper(Str::random(10)),


                            'file_type'=>$type,


                            'original_name'=>$file->getClientOriginalName(),


                            'stored_name'=>$storedName,


                            'file_path'=>$path,


                            'file_size'=>$file->getSize(),


                            'mime_type'=>$file->getMimeType(),


                            'version_number'=>'V1.0',


                            'uploaded_by'=>auth()->id(),


                            'status'=>'active',


                        ]);



                    }


                }


                    /*
                    |--------------------------------------------------------------------------
                    | Update Draft Progress
                    |--------------------------------------------------------------------------
                    */


                    $manuscript->update([

                        'completion_percentage'=>46,

                        'last_step'=>6,

                        'draft_saved_at'=>now(),

                    ]);


                return redirect()

                    ->route(
                        'author.submission.step7',
                        $manuscript->id
                    )

                    ->with(
                        'success',
                        'Files uploaded successfully'
                    );


            }



            /**============================
             * Step 7
             * Ethical Information
             */
            public function step7(Manuscript $manuscript)
            {

                $manuscript->load([
                    'ethicalInformation'
                ]);


                return view(
                    'author.submission.step7',
                    compact('manuscript')
                );

            }

            /**============================
             * Step 7
             * Ethical Information store
             */

            public function storeStep7(
                Request $request,
                Manuscript $manuscript
            )
            {


            $data=$request->validate([


            'human_participants'=>'required',

            'animal_study'=>'required',

            'ethics_committee_name'=>'nullable|string',

            'approval_number'=>'nullable|string',

            'approval_date'=>'nullable|date',


            ]);



            $manuscript->ethicalInformation()
            ->updateOrCreate(

            [
            'manuscript_id'=>$manuscript->id
            ],

            $data

            );


             /*
            |--------------------------------------------------------------------------
            | Update Draft Progress
            |--------------------------------------------------------------------------
            */


            $manuscript->update([

                'completion_percentage'=>54,

                'last_step'=>7,

                'draft_saved_at'=>now(),

            ]);



            return redirect()
            ->route(
            'author.submission.step8',
            $manuscript->id
            );


            }




            /**
             * Step 8 Funding Information
             */
            public function step8(Manuscript $manuscript)
            {

                return view(
                    'author.submission.step8',
                    compact('manuscript')
                );

            }



        /**
         * Store Step 8 Funding Information
         */
        public function storeStep8(
            Request $request,
            Manuscript $manuscript
        )
        {


            $validated = $request->validate([


                'funding_received'=>[
                    'required',
                    'boolean'
                ],


                'funding_type'=>[
                    'nullable',
                    'string'
                ],


                'funding_organization'=>[
                    'nullable',
                    'string'
                ],


                'grant_number'=>[
                    'nullable',
                    'string'
                ],


                'grant_amount'=>[
                    'nullable',
                    'numeric'
                ],


                'funding_start_date'=>[
                    'nullable',
                    'date'
                ],


                'funding_end_date'=>[
                    'nullable',
                    'date'
                ],


                'funding_statement'=>[
                    'nullable',
                    'string'
                ],


            ]);



            $manuscript->fundingInformation()
            ->updateOrCreate(

                [
                    'manuscript_id'=>$manuscript->id
                ],

                $validated

            );

            /*
            |--------------------------------------------------------------------------
            | Update Draft Progress
            |--------------------------------------------------------------------------
            */


            $manuscript->update([

                'completion_percentage'=>62,

                'last_step'=>8,

                'draft_saved_at'=>now(),

            ]);


            return redirect()
                ->route(
                    'author.submission.step9',
                    $manuscript->id
                )
                ->with(
                    'success',
                    'Funding information saved successfully'
                );


        }

    
            /**
             * Step 9 Conflict of Interest
             */
            public function step9(Manuscript $manuscript)
            {

                return view(
                    'author.submission.step9',
                    compact('manuscript')
                );

            }



            /**
             * Store Step 9
             */
            public function storeStep9(
                Request $request,
                Manuscript $manuscript
            )
            {

                $validated = $request->validate([

                    'conflict_exists' => [
                        'required',
                        'boolean'
                    ],

                    'conflict_description' => [
                        'nullable',
                        'string'
                    ],

                    'author_declaration' => [
                        'required',
                        'string'
                    ],

                    'all_authors_agreed' => [
                        'required',
                        'boolean'
                    ],

                ]);


                $manuscript
                    ->conflictOfInterest()
                    ->updateOrCreate(

                        [
                            'manuscript_id'=>$manuscript->id
                        ],

                        $validated

                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Update Draft Progress
                    |--------------------------------------------------------------------------
                    */


                    $manuscript->update([

                        'completion_percentage'=>69,

                        'last_step'=>9,

                        'draft_saved_at'=>now(),

                    ]);



                return redirect()
                    ->route(
                        'author.submission.step10',
                        $manuscript->id
                    )
                    ->with(
                        'success',
                        'Conflict of interest saved successfully'
                    );


            }


             /**
             * Step 10 Data Availability
             */



             /**
             * Step 10
             * Data Availability
             */
            public function step10($id)
            {

                $manuscript = Manuscript::findOrFail($id);


                $dataAvailability = 
                    DataAvailability::where(
                        'manuscript_id',
                        $manuscript->id
                    )->first();


                return view(
                    'author.submission.step10',
                    compact(
                        'manuscript',
                        'dataAvailability'
                    )
                );

            }


           /**
         * Store Step 10
         * Data Availability
         */
        public function storeStep10(Request $request,$id)
        {


            $manuscript = Manuscript::findOrFail($id);



            $validated = $request->validate([


                'data_available'
                    => 'required|boolean',


                'statement'
                    => 'nullable|string',


                'repository'
                    => 'nullable|string',


                'repository_name'
                    => 'nullable|string',


                'doi_url'
                    => 'nullable|string',


                'access_restriction'
                    => 'nullable|string',


                'restriction_reason'
                    => 'nullable|string',


            ]);




            DataAvailability::updateOrCreate(

                [

                    'manuscript_id'=>$manuscript->id

                ],


                $validated

            );



            /*
            |--------------------------------------------------------------------------
            | Update Draft Progress
            |--------------------------------------------------------------------------
            */


            $manuscript->update([

                'completion_percentage'=>77,

                'last_step'=>10,

                'draft_saved_at'=>now(),

            ]);


            return redirect()

                ->route(

                    'author.submission.step11',

                    $manuscript->id

                )

                ->with(

                    'success',

                    'Data Availability saved successfully'

                );


        }


            /**
             * Step 11 Acknowledgement
             */

            public function step11($id)
            {

                $manuscript = Manuscript::findOrFail($id);


                $acknowledgement = 
                    Acknowledgement::where(
                        'manuscript_id',
                        $manuscript->id
                    )->first();



                return view(
                    'author.submission.step11',
                    compact(
                        'manuscript',
                        'acknowledgement'
                    )
                );

            }



            public function storeStep11(Request $request,$id)
                {


                    $manuscript = Manuscript::findOrFail($id);



                    $validated = $request->validate([


                        'applicable'
                        =>
                        'required|boolean',



                        'text'
                        =>
                        'nullable|string',


                    ]);




                    Acknowledgement::updateOrCreate(

                        [

                            'manuscript_id'
                            =>
                            $manuscript->id

                        ],


                        $validated

                    );


                     /*
                    |--------------------------------------------------------------------------
                    | Update Draft Progress
                    |--------------------------------------------------------------------------
                    */


                    $manuscript->update([

                        'completion_percentage'=>85,

                        'last_step'=>11,

                        'draft_saved_at'=>now(),

                    ]);




                    return redirect()

                        ->route(

                            'author.submission.step12',

                            $manuscript->id

                        )

                        ->with(

                            'success',

                            'Acknowledgement saved successfully'

                        );


                }

                /**
                 * Step 12
                 * Declaration & Submission Checklist
                 */
                public function step12(Manuscript $manuscript)
                {

                    abort_if(
                        $manuscript->submitted_by != auth()->id(),
                        403
                    );


                    $checklist = SubmissionChecklist::where(
                        'manuscript_id',
                        $manuscript->id
                    )->first();



                    return view(
                        'author.submission.step12',
                        compact(
                            'manuscript',
                            'checklist'
                        )
                    );

                }
       
          /**
         * Store Step 12
         * Declaration & Submission Checklist
         */
        public function storeStep12(
            Request $request,
            Manuscript $manuscript
        )
        {

            $validated = $request->validate([


                'original_manuscript'=>'required',

                'not_published_elsewhere'=>'required',

                'not_under_consideration_elsewhere'=>'required',

                'authors_approved'=>'required',

                'author_order_approved'=>'required',

                'ethics_information_provided'=>'required',

                'consent_information_provided'=>'required',

                'funding_declared'=>'required',

                'coi_declared'=>'required',

                'journal_guidelines_followed'=>'required',

                'references_checked'=>'required',

                'tables_figures_checked'=>'required',

                'required_files_uploaded'=>'required',

                'corresponding_author_authorized'=>'required',

                'publication_policy_agreed'=>'required',


            ]);



            $validated['manuscript_id'] = $manuscript->id;


            $validated['confirmed_by'] = auth()->id();


            $validated['confirmed_at'] = now();



            /*
            |--------------------------------------------------------------------------
            | Save Checklist
            |--------------------------------------------------------------------------
            */

            SubmissionChecklist::updateOrCreate(

                [
                    'manuscript_id'=>$manuscript->id
                ],

                $validated

            );



            /*
            |--------------------------------------------------------------------------
            | Update Draft Progress
            |--------------------------------------------------------------------------
            */

            $manuscript->update([


                'completion_percentage'=>92,


                'last_step'=>12,


                'draft_saved_at'=>now(),


                'status'=>'draft',


            ]);



            return redirect()

                ->route(

                    'author.submission.step13',

                    $manuscript->id

                )

                ->with(

                    'success',

                    'Submission checklist completed successfully.'

                );

        }
        /**
         * Step 13
         * Submission Confirmation
         */
        public function step13(Manuscript $manuscript)
        {

            abort_if(
                $manuscript->submitted_by != auth()->id(),
                403
            );


            $manuscript->load([

                'journal',

                'articleType',

                'submitter',

                'authors',

                'details',

                'ethicalInformation',

                'fundingInformation',

                'conflictOfInterest',

                'dataAvailability',

                'acknowledgement',

                'checklist',

                'files',

            ]);



             return view(
                'author.submission.step13',
                [
                    'manuscript' => $manuscript,

                    'message' =>
                    'Your manuscript is ready for final submission. Please review all information carefully before submitting to BMRC editorial office.'
                ]
            );


        }


    


        /**
         * Final Manuscript Submit
         */
        public function finalSubmit(Manuscript $manuscript)
        {

            abort_if(
                $manuscript->submitted_by != auth()->id(),
                403
            );



            $manuscript->update([


                'status'=>'submitted',


                'completion_percentage'=>100,


                'last_step'=>13,


                'submitted_at'=>now(),


            ]);



            return redirect()

                ->route(
                    'author.dashboard'
                )

                ->with(

                    'success',

                    'Manuscript submitted successfully.'

                );

        }

    /**
     * Convert keywords text to JSON array
     */
    private function formatKeywords($keywords)
    {

        if(!$keywords){
            return null;
        }


        return array_map(
            'trim',
            explode(',', $keywords)
        );

    }



}