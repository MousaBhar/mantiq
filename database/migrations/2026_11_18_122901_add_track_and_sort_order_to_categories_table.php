<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('track')->default('tech')->after('name');
            $table->unsignedInteger('sort_order')->default(0)->after('track');

            $table->unique(['track', 'name'], 'categories_track_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_track_name_unique');
            $table->dropColumn(['track', 'sort_order']);
        });
    }
};
