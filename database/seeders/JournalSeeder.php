<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Journal;

class JournalSeeder extends Seeder
{
    public function run(): void
    {

        Journal::updateOrCreate(

            [
                'code' => 'BMRCJ'
            ],

            [

                'name' => 'BMRC Journal',

                'short_name' => 'BMRCJ',

                'issn' => '0377-9238',

                'eissn' => '2224-7238',

                'publisher' => 'Bangladesh Medical Research Council',

                'description' => 'Official journal of Bangladesh Medical Research Council.',

                'website' => 'https://www.bmrcbd.org/',

                'email' => 'editor@bmrc.gov.bd',

                'phone' => '+8802-222298396',

                'address' => 'BMRC Bhaban, Mohakhali, Dhaka, Bangladesh',

                'frequency' => 'Triannual',

                'language' => 'English',

                'country' => 'Bangladesh',

                'is_active' => true,

            ]

        );

    }
}