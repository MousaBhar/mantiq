<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Reset token expiration column is defined in the initial migration.
    }

    public function down(): void
    {
        // No additional columns are added by this migration.
    }
};
