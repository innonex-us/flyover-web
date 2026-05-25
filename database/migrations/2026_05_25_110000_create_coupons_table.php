<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->enum('applies_to', ['all', 'tours', 'hotels', 'transfers', 'visas'])->default('all');
            $table->json('applicable_ids')->nullable(); // specific package/hotel IDs
            $table->integer('usage_limit')->nullable(); // total uses allowed
            $table->integer('usage_limit_per_user')->nullable(); // per user limit
            $table->integer('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['code', 'is_active']);
            $table->index('expires_at');
        });

        // Track coupon usage per user
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('booking'); // polymorphic to all booking types
            $table->decimal('discount_amount', 10, 2);
            $table->timestamp('used_at');
            $table->timestamps();
            
            $table->index(['coupon_id', 'user_id']);
        });

        // Add discount fields to bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('total_amount')->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_id');
            $table->decimal('final_amount', 10, 2)->after('discount_amount');
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('total_amount')->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_id');
            $table->decimal('final_amount', 10, 2)->after('discount_amount');
        });

        Schema::table('transfer_bookings', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('total_amount')->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_id');
            $table->decimal('final_amount', 10, 2)->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id', 'discount_amount', 'final_amount']);
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id', 'discount_amount', 'final_amount']);
        });

        Schema::table('transfer_bookings', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id', 'discount_amount', 'final_amount']);
        });

        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
    }
};
