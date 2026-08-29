<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {

            $table->string('display_name')
                ->nullable()
                ->after('name');

            $table->enum('user_type', [
                'internal',
                'external',
            ])
                ->default('internal')
                ->after('display_name');

            $table->text('description')
                ->nullable()
                ->after('user_type');

            $table->boolean('is_active')
                ->default(true)
                ->after('description');

            $table->boolean('is_system_role')
                ->default(false)
                ->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {

            $table->dropColumn([
                'display_name',
                'user_type',
                'description',
                'is_active',
                'is_system_role',
            ]);
        });
    }
};