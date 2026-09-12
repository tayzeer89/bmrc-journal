<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_pages', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Page Identity
            |--------------------------------------------------------------------------
            */

            $table->string('title');

            $table->string('slug')
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Menu Group
            |--------------------------------------------------------------------------
            */

            $table->enum('menu_group', [
                'about',
                'editorial_board',
                'journal',
                'authors',
                'reviewers',
            ])->index();


            /*
            |--------------------------------------------------------------------------
            | Content
            |--------------------------------------------------------------------------
            */

            $table->longText('content')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Content Mode
            |--------------------------------------------------------------------------
            |
            | visual = CKEditor
            | html   = Raw/custom HTML
            |
            */

            $table->enum('content_mode', [
                'visual',
                'html',
            ])
            ->default('visual')
            ->index();


            $table->text('short_description')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Featured Image
            |--------------------------------------------------------------------------
            */

            $table->string('featured_image')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Display Settings
            |--------------------------------------------------------------------------
            */

            $table->boolean('show_in_menu')
                ->default(true);

            $table->boolean('show_on_homepage')
                ->default(false);

            $table->unsignedInteger('sort_order')
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'draft',
                'published',
                'inactive',
            ])
            ->default('draft')
            ->index();


            /*
            |--------------------------------------------------------------------------
            | Audit Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('journal_pages');
    }
};