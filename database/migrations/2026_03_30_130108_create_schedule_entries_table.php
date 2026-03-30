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
    Schema::create('schedule_entries', function (Blueprint $table) {
        $table->id();

        $table->string('student_id');

        $table->foreignId('course_id')->constrained()->cascadeOnDelete();
        $table->foreignId('section_id')->constrained()->cascadeOnDelete();

        $table->string('day');
        $table->string('start_time');
        $table->string('end_time');
        $table->string('room')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_entries');
    }
};
