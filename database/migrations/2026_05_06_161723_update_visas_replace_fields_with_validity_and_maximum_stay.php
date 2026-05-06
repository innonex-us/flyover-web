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
        Schema::table('visas', function (Blueprint $table) {
            $table->dropColumn(['processing_time', 'entry_type', 'validity_info']);
            $table->string('validity')->nullable();
            $table->string('maximum_stay')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $table->dropColumn(['validity', 'maximum_stay']);
            $table->string('processing_time')->nullable();
            $table->string('entry_type')->nullable();
            $table->string('validity_info')->nullable();
        });
    }
};
