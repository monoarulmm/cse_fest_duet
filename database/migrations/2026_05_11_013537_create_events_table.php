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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // IUPC, AI Hackathon, ICT Olympiad

            $table->string('slug')->unique(); // iupc, ai-hackathon, ict-olympiad
            $table->longText('description')->nullable();
            $table->string('rules')->nullable();
            $table->string('result')->nullable();
            $table->string('seatplan')->nullable();
            $table->json('images')->nullable();
            $table->integer('reg_fee')->default(0);
            $table->integer('min_members')->default(1);
            $table->integer('max_members')->default(3);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
            $table->boolean('needs_coach')->default(false); // IUPC এর জন্য true
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
