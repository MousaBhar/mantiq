<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('contents')
            ->where('id', 2)
            ->where('title', 'Laravel')
            ->where('type', 'podcast')
            ->where('track', 'business')
            ->where('category_id', 1)
            ->update([
                'track' => null,
                'category_id' => null,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('contents')
            ->where('id', 2)
            ->where('title', 'Laravel')
            ->where('type', 'podcast')
            ->whereNull('track')
            ->whereNull('category_id')
            ->update([
                'track' => 'business',
                'category_id' => 1,
                'updated_at' => now(),
            ]);
    }
};
