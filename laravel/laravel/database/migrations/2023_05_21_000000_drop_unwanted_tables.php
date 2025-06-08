<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop all unwanted tables
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        // Do not drop migrations table as Laravel needs it
        // Schema::dropIfExists('migrations');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('products');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_resets');
    }

    /**
     * Reverse the migrations.
     * This function is intentionally left empty since we don't want to recreate these tables
     */
    public function down(): void
    {
        // We don't recreate tables in down() method as we want to permanently remove them
    }
}; 