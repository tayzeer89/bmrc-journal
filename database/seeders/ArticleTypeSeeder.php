<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArticleType;

class ArticleTypeSeeder extends Seeder
{
    public function run(): void
    {

        $types = [

            [
                'name' => 'Original Article',
                'code' => 'ORIGINAL',
                'sort_order' => 1,
            ],

            [
                'name' => 'Review Article',
                'code' => 'REVIEW',
                'sort_order' => 2,
            ],

            [
                'name' => 'Systematic Review',
                'code' => 'SYSTEMATIC_REVIEW',
                'sort_order' => 3,
            ],

            [
                'name' => 'Meta-analysis',
                'code' => 'META_ANALYSIS',
                'sort_order' => 4,
            ],

            [
                'name' => 'Short Communication',
                'code' => 'SHORT_COMMUNICATION',
                'sort_order' => 5,
            ],

            [
                'name' => 'Case Report',
                'code' => 'CASE_REPORT',
                'sort_order' => 6,
            ],

            [
                'name' => 'Case Series',
                'code' => 'CASE_SERIES',
                'sort_order' => 7,
            ],

            [
                'name' => 'Editorial',
                'code' => 'EDITORIAL',
                'sort_order' => 8,
            ],

            [
                'name' => 'Letter to the Editor',
                'code' => 'LETTER',
                'sort_order' => 9,
            ],

            [
                'name' => 'Commentary',
                'code' => 'COMMENTARY',
                'sort_order' => 10,
            ],

            [
                'name' => 'Perspective',
                'code' => 'PERSPECTIVE',
                'sort_order' => 11,
            ],

            [
                'name' => 'Methodological Article',
                'code' => 'METHODOLOGICAL',
                'sort_order' => 12,
            ],

            [
                'name' => 'Protocol',
                'code' => 'PROTOCOL',
                'sort_order' => 13,
            ],

            [
                'name' => 'Brief Report',
                'code' => 'BRIEF_REPORT',
                'sort_order' => 14,
            ],

            [
                'name' => 'Research Communication',
                'code' => 'RESEARCH_COMMUNICATION',
                'sort_order' => 15,
            ],

        ];


        foreach($types as $type)
        {

            ArticleType::updateOrCreate(

                [
                    'name' => $type['name']
                ],

                [
                    'code' => $type['code'],
                    'sort_order' => $type['sort_order'],
                    'is_active' => true,
                ]

            );

        }


    }
}