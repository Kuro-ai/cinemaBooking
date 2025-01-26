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
        Schema::create('theatres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('cinema_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('2D');
            $table->boolean('is_active')->default(true);
            $table->string('screen_size')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->unique(['name', 'cinema_id']); 
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theatres');
    }
};
