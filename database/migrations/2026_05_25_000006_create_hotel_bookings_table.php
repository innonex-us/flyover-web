<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('hotel_rooms')->cascadeOnDelete();
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedSmallInteger('nights');
            $table->unsignedSmallInteger('guests')->default(1);
            $table->decimal('total_amount', 10, 2);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->text('special_request')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};
