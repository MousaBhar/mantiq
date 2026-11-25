<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('contents')
            ->where('id', 3)
            ->where('title', 'PHP')
            ->where('type', 'podcast')
            ->where('track', 'tech')
            ->whereNull('category_id')
            ->update([
                'track' => null,
                'category_id' => null,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('contents')
            ->where('id', 3)
            ->where('title', 'PHP')
            ->where('type', 'podcast')
            ->whereNull('track')
            ->whereNull('category_id')
            ->update([
                'track' => 'tech',
                'category_id' => null,
                'updated_at' => now(),
            ]);
    }
};