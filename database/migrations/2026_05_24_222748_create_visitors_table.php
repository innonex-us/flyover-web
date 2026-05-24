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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('fingerprint')->unique()->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('platform')->nullable();
            $table->string('device_type')->nullable();
            $table->boolean('is_mobile')->default(false);
            $table->boolean('is_tablet')->default(false);
            $table->boolean('is_desktop')->default(false);
            $table->string('language', 10)->nullable();
            $table->string('timezone')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('isp')->nullable();
            $table->string('organization')->nullable();
            $table->string('connection_type')->nullable();
            $table->json('screen_resolution')->nullable();
            $table->json('viewport_size')->nullable();
            $table->boolean('cookies_enabled')->default(true);
            $table->boolean('javascript_enabled')->default(true);
            $table->boolean('do_not_track')->default(false);
            $table->string('consent_level')->default('none');
            $table->timestamp('first_visit_at')->nullable();
            $table->timestamp('last_visit_at')->nullable();
            $table->integer('total_visits')->default(0);
            $table->integer('total_page_views')->default(0);
            $table->integer('total_duration')->default(0);
            $table->boolean('is_bot')->default(false);
            $table->timestamps();
            
            $table->index('ip_address');
            $table->index('country_code');
            $table->index('first_visit_at');
            $table->index('last_visit_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
