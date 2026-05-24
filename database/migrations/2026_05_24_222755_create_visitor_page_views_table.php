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
        Schema::create('visitor_page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
            $table->foreignId('session_id')->constrained('visitor_sessions')->onDelete('cascade');
            $table->string('url');
            $table->string('title')->nullable();
            $table->string('path');
            $table->string('query_params')->nullable();
            $table->string('hash')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->integer('time_on_page')->default(0);
            $table->integer('scroll_depth')->default(0);
            $table->integer('max_scroll_depth')->default(0);
            $table->boolean('is_exit_page')->default(false);
            $table->json('interactions')->nullable();
            $table->json('performance_metrics')->nullable();
            $table->timestamps();
            
            $table->index('visitor_id');
            $table->index('session_id');
            $table->index('url');
            $table->index('path');
            $table->index('viewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_page_views');
    }
};
