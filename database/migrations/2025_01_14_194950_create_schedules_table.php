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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->foreignId('theatre_id')->constrained()->onDelete('cascade');
            $table->date('date'); 
            $table->time('start_time')->nullable()->change();
            $table->time('end_time')->nullable()->change();   
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['movie_id', 'theatre_id', 'date', 'start_time']);
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
