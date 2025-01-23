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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theatre_id')->constrained()->onDelete('cascade');    
            $table->string('seat_number');                                          
            $table->enum('type', ['regular', 'vip', 'recliner'])->default('regular'); 
            $table->decimal('price', 8, 2)->nullable();                                                   
            $table->timestamps();                                                 
            $table->unique(['theatre_id', 'seat_number']);                          
        });       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
