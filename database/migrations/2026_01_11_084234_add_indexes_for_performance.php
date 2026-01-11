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
        Schema::table('packages', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('slug');
            $table->index('created_at');
        });

        Schema::table('visas', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('slug');
            $table->index('created_at');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->index('is_published');
            $table->index('slug');
            $table->index('published_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('visas', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['is_published']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['created_at']);
        });
    }
};
