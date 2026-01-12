<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->text('hotel_details')->nullable();
            $table->text('additional_info')->nullable();
            $table->text('travel_tips')->nullable();
            $table->text('pickup_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['hotel_details', 'additional_info', 'travel_tips', 'pickup_note']);
        });
    }
};
