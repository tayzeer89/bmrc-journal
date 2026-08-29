<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ArticleType;

class ArticleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            ArticleType::insert([
            [
                'name' => 'Original Article',
                'code' => 'ORIGINAL',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Review Article',
                'code' => 'REVIEW',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Systematic Review',
                'code' => 'SYSTEMATIC_REVIEW',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Meta-analysis',
                'code' => 'META_ANALYSIS',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Short Communication',
                'code' => 'SHORT_COMMUNICATION',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Case Report',
                'code' => 'CASE_REPORT',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Case Series',
                'code' => 'CASE_SERIES',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Editorial',
                'code' => 'EDITORIAL',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Letter to the Editor',
                'code' => 'LETTER',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Commentary',
                'code' => 'COMMENTARY',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Perspective',
                'code' => 'PERSPECTIVE',
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Methodological Article',
                'code' => 'METHODOLOGICAL',
                'sort_order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Protocol',
                'code' => 'PROTOCOL',
                'sort_order' => 13,
                'is_active' => true,
            ],
            [
                'name' => 'Brief Report',
                'code' => 'BRIEF_REPORT',
                'sort_order' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Research Communication',
                'code' => 'RESEARCH_COMMUNICATION',
                'sort_order' => 15,
                'is_active' => true,
            ],
        ]);
    }
















    
}
