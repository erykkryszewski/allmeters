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
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();

            $table->integer('work_hours')->default(0);
            $table->decimal('sleep_hours', 3, 1)->default(0); // e.g. 7.5
            $table->integer('calories')->nullable();
            $table->boolean('high_protein')->default(false);
            $table->boolean('low_fat')->default(false);

            $table->enum('activity_level', ['none','short','long'])->default('none');
            $table->boolean('wife_time')->default(false);
            $table->enum('social_media_level', ['ok','medium','high'])->default('ok');

            $table->boolean('smoking')->default(false);
            $table->boolean('alcohol')->default(false);
            $table->boolean('other_drugs')->default(false);

            $table->boolean('meditation')->default(false);
            $table->boolean('reading')->default(false);
            $table->integer('chess_games')->default(0);

            $table->enum('water_level', ['low','medium','high'])->default('low');

            $table->integer('score')->default(0);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('days');
    }
};
