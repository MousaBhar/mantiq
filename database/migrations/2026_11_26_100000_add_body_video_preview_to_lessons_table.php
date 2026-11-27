<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Markdown body rendered inside the lesson page.
            $table->longText('body')->nullable()->after('description');
            // Embeddable video (YouTube / Vimeo) shown in the player.
            $table->string('video_url')->nullable()->after('media_url');
            // Free preview lessons are visible to any signed-in user.
            $table->boolean('is_preview')->default(false)->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['body', 'video_url', 'is_preview']);
        });
    }
};
