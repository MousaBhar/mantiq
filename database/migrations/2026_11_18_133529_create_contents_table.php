<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();

            // Content classification
            $table->string('type');
            $table->string('track');

            // Relationships
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Basic information
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cover')->nullable();

            // Pricing and publishing
            $table->decimal('price', 10, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();

            // Display information
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);

            // Type-specific information
            $table->string('level')->nullable();
            $table->unsignedInteger('pages')->nullable();
            $table->unsignedInteger('read_minutes')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->string('media_url')->nullable();
            $table->string('file_path')->nullable();

            // Indexes
            $table->index(['type', 'track']);
            $table->index(['status', 'published_at']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
