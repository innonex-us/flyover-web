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
            $table->string('entry_type')->nullable(); // e.g. "Tourist Visa(E-Visa)"
            $table->string('validity_info')->nullable(); // e.g. "07 to 10 working days"
            $table->text('important_notes')->nullable();
            $table->json('required_documents')->nullable(); // To store categorized lists
            $table->text('terms')->nullable();
            $table->text('fees')->nullable(); // HTML or text for fee breakdown
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $table->dropColumn(['entry_type', 'validity_info', 'important_notes', 'required_documents', 'terms', 'fees']);
        });
    }
};
