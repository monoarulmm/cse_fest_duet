<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            // --- COMMON FIELDS (সব ইভেন্টের জন্য) ---
            $table->string('university_name');
            $table->string('team_name')->nullable(); // ICT Olympiad এ টিম নাম না থাকলে nullable
            $table->string('team_id')->nullable(); // ICT Olympiad এ টিম নাম না থাকলে nullable

            // Member 1 (Lead/Solo)
            $table->string('m1_name'); // অন্তত একজনের নাম লাগবেই
            $table->string('m1_email');
            $table->string('m1_phone');
            $table->string('m1_tshirt')->nullable();
            $table->string('student_id')->nullable(); // ICT Olympiad এর জন্য মেইনলি

            // Member 2 (Optional for Solo events)
            $table->string('m2_name')->nullable();
            $table->string('m2_email')->nullable();
            $table->string('m2_phone')->nullable();
            $table->string('m2_tshirt')->nullable();

            // Member 3 (Optional)
            $table->string('m3_name')->nullable();
            $table->string('m3_email')->nullable();
            $table->string('m3_phone')->nullable();
            $table->string('m3_tshirt')->nullable();

            // --- IUPC SPECIFIC ---
            $table->string('coach_name')->nullable();
            $table->string('coach_designation')->nullable();
            $table->string('coach_email')->nullable();
            $table->string('coach_phone')->nullable();
            $table->string('coach_tshirt')->nullable();
            $table->string('m1_cf_handle')->nullable();
            $table->string('m2_cf_handle')->nullable();
            $table->string('m3_cf_handle')->nullable();
            $table->enum('prev_ex', ['YES', 'NO'])->default('NO');
            // --- PROJECT SHOWCASE SPECIFIC ---
            $table->string('project_title')->nullable(); // Nullable করতে হবে কারণ IUPC তে এটা লাগবে না
            $table->string('domain')->nullable(); // AI, IoT, etc.
            $table->string('abstract_file')->nullable(); // PDF Path

            // --- SYSTEM & PAYMENT LOGIC ---
            $table->string('participant_id')->nullable()->unique();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('status', ['pre-registered', 'selected', 'verified', 'rejected'])
                ->default('pre-registered');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
