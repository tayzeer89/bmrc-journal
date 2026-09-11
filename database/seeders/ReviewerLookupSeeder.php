<?php

namespace Database\Seeders;

use App\Models\ReviewerLookupOption;
use App\Models\ReviewerProfile;
use Illuminate\Database\Seeder;

class ReviewerLookupSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Bangladesh Divisions
        |--------------------------------------------------------------------------
        */

        $divisions = [
            'Barishal',
            'Chattogram',
            'Dhaka',
            'Khulna',
            'Mymensingh',
            'Rajshahi',
            'Rangpur',
            'Sylhet',
        ];

        foreach ($divisions as $index => $division) {
            $this->add(
                'division_state',
                $division,
                null,
                $index + 1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Bangladesh Districts
        |--------------------------------------------------------------------------
        */

        $districts = [
            'Dhaka' => [
                'Dhaka',
                'Faridpur',
                'Gazipur',
                'Gopalganj',
                'Kishoreganj',
                'Madaripur',
                'Manikganj',
                'Munshiganj',
                'Narayanganj',
                'Narsingdi',
                'Rajbari',
                'Shariatpur',
                'Tangail',
            ],

            'Chattogram' => [
                'Bandarban',
                'Brahmanbaria',
                'Chandpur',
                'Chattogram',
                'Cumilla',
                "Cox's Bazar",
                'Feni',
                'Khagrachhari',
                'Lakshmipur',
                'Noakhali',
                'Rangamati',
            ],

            'Rajshahi' => [
                'Bogura',
                'Joypurhat',
                'Naogaon',
                'Natore',
                'Chapainawabganj',
                'Pabna',
                'Rajshahi',
                'Sirajganj',
            ],

            'Khulna' => [
                'Bagerhat',
                'Chuadanga',
                'Jashore',
                'Jhenaidah',
                'Khulna',
                'Kushtia',
                'Magura',
                'Meherpur',
                'Narail',
                'Satkhira',
            ],

            'Barishal' => [
                'Barguna',
                'Barishal',
                'Bhola',
                'Jhalokathi',
                'Patuakhali',
                'Pirojpur',
            ],

            'Sylhet' => [
                'Habiganj',
                'Moulvibazar',
                'Sunamganj',
                'Sylhet',
            ],

            'Rangpur' => [
                'Dinajpur',
                'Gaibandha',
                'Kurigram',
                'Lalmonirhat',
                'Nilphamari',
                'Panchagarh',
                'Rangpur',
                'Thakurgaon',
            ],

            'Mymensingh' => [
                'Jamalpur',
                'Mymensingh',
                'Netrokona',
                'Sherpur',
            ],
        ];

        foreach ($districts as $division => $items) {
            foreach ($items as $index => $district) {
                $this->add(
                    'city_district',
                    $district,
                    $division,
                    $index + 1
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Common Designations
        |--------------------------------------------------------------------------
        */

        $designations = [
            'Professor',
            'Professor & Head',
            'Associate Professor',
            'Assistant Professor',
            'Lecturer',
            'Consultant',
            'Senior Consultant',
            'Chief Consultant',
            'Medical Officer',
            'Scientific Officer',
            'Research Investigator',
            'Senior Research Investigator',
            'Research Scientist',
            'Scientist',
            'Director',
            'Director General',
            'Chairman',
            'Head of Department',
            'Principal',
            'Vice Principal',
            'Programme Specialist',
            'Project Coordinator',
        ];

        foreach ($designations as $index => $value) {
            $this->add(
                'designation',
                $value,
                null,
                $index + 1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Highest Academic Degree
        |--------------------------------------------------------------------------
        */

        $degrees = [
            'MBBS',
            'BDS',
            'MPH',
            'MSc',
            'MPhil',
            'MS',
            'MD',
            'FCPS',
            'FRCS',
            'MRCP',
            'PhD',
            'DrPH',
        ];

        foreach ($degrees as $index => $degree) {
            $this->add(
                'highest_degree',
                $degree,
                null,
                $index + 1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Common Specializations
        |--------------------------------------------------------------------------
        */

        $specializations = [
            'Anatomy',
            'Biochemistry',
            'Cardiology',
            'Community Medicine',
            'Dental Public Health',
            'Dermatology',
            'Endocrinology',
            'Epidemiology',
            'Gastroenterology',
            'General Surgery',
            'Gynecology & Obstetrics',
            'Gynecological Oncology',
            'Internal Medicine',
            'Microbiology',
            'Nephrology',
            'Neurology',
            'Oncology',
            'Ophthalmology',
            'Oral & Maxillofacial Surgery',
            'Orthopedic Surgery',
            'Pathology',
            'Pediatric Surgery',
            'Pediatrics',
            'Pharmacology',
            'Physical Medicine & Rehabilitation',
            'Physiology',
            'Plastic Surgery',
            'Public Health',
            'Radiology & Imaging',
            'Research Methodology',
            'Rheumatology',
            'Surgical Oncology',
        ];

        foreach ($specializations as $index => $value) {
            $this->add(
                'specialization',
                $value,
                null,
                $index + 1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Common Research Interests
        |--------------------------------------------------------------------------
        */

        $researchInterests = [
            'Public Health',
            'Epidemiology',
            'Clinical Research',
            'Biomedical Research',
            'Health Systems Research',
            'Maternal Health',
            'Child Health',
            'Communicable Diseases',
            'Non-Communicable Diseases',
            'Cardiovascular Disease',
            'Cancer Research',
            'Medical Education',
            'Health Policy',
            'Research Ethics',
            'Biostatistics',
            'Community Health',
            'Clinical Trials',
            'Implementation Research',
        ];

        foreach ($researchInterests as $index => $value) {
            $this->add(
                'research_interest',
                $value,
                null,
                $index + 1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Common Review Keywords
        |--------------------------------------------------------------------------
        */

        $keywords = [
            'Public Health',
            'Epidemiology',
            'Biostatistics',
            'Clinical Trial',
            'Research Methodology',
            'Cardiology',
            'Neurology',
            'Nephrology',
            'Oncology',
            'Surgery',
            'Medicine',
            'Pediatrics',
            'Gynecology',
            'Microbiology',
            'Biochemistry',
            'Pathology',
            'Radiology',
            'Endocrinology',
            'Pharmacology',
            'Maternal Health',
            'Child Health',
            'Health Policy',
            'Research Ethics',
        ];

        foreach ($keywords as $index => $value) {
            $this->add(
                'review_keyword',
                $value,
                null,
                $index + 1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Import Existing Reviewer Profile Values
        |--------------------------------------------------------------------------
        */

        $this->importExistingProfileValues();
    }


    private function add(
        string $type,
        string $value,
        ?string $parent = null,
        int $sortOrder = 0
    ): void {
        ReviewerLookupOption::firstOrCreate(
            [
                'type' => $type,
                'value' => trim($value),
                'parent_value' => $parent,
            ],
            [
                'is_active' => true,
                'sort_order' => $sortOrder,
            ]
        );
    }


    private function importExistingProfileValues(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Single-value profile columns
        |--------------------------------------------------------------------------
        */

        $singleFields = [
            'division_state' => 'division_state',
            'city_district' => 'city_district',
            'institution' => 'institution',
            'department' => 'department',
            'designation' => 'designation',
            'highest_degree' => 'highest_degree',
        ];

        foreach ($singleFields as $column => $type) {
            ReviewerProfile::query()
                ->whereNotNull($column)
                ->where($column, '!=', '')
                ->distinct()
                ->pluck($column)
                ->each(function ($value) use ($type) {
                    $this->add(
                        $type,
                        trim($value)
                    );
                });
        }


        /*
        |--------------------------------------------------------------------------
        | Multiple-value text columns
        |--------------------------------------------------------------------------
        */

        $multipleFields = [
            'specialization' => 'specialization',
            'research_interests' => 'research_interest',
            'expertise_keywords' => 'review_keyword',
        ];

        foreach ($multipleFields as $column => $type) {
            ReviewerProfile::query()
                ->whereNotNull($column)
                ->where($column, '!=', '')
                ->pluck($column)
                ->each(function ($value) use ($type) {
                    $items = preg_split(
                        '/[,;|]+/',
                        $value
                    );

                    foreach ($items as $item) {
                        $item = trim($item);

                        if ($item === '') {
                            continue;
                        }

                        $this->add(
                            $type,
                            $item
                        );
                    }
                });
        }
    }
}