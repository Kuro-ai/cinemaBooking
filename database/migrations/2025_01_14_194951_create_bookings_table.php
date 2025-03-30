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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');        
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');    
            $table->string('booking_code')->unique();                                
            $table->integer('total_seats');                                          
            $table->decimal('total_price', 8, 2);    
            $table->string('payment_type')->nullable();
            $table->timestamp('payment_date')->nullable();     
            $table->text('seat_numbers')->default('[]')->nullable();                        
            $table->enum('status', ['booked', 'purchased', 'refunded', 'confirmed'])->change();
            $table->timestamps();                                                
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
