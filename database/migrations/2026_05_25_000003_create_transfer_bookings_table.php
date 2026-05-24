<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->nullable()->constrained('transfer_routes')->nullOnDelete();
            $table->boolean('is_custom')->default(false);
            $table->string('pickup_location');
            $table->string('drop_location');
            $table->unsignedSmallInteger('passenger_count');
            $table->date('travel_date');
            $table->time('pickup_time')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->text('special_request')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_bookings');
    }
};
