<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('content_id')
                ->unique()
                ->constrained('contents')
                ->cascadeOnDelete();

            $table->longText('body');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_details');
    }
};
