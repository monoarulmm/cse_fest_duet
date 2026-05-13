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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // Gateway Transaction ID

            // Foreign Keys (আপনার টেবিলের নাম অনুযায়ী 'events', 'teams', 'students' নিশ্চিত করুন)
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            // IUPC বা টিম ইভেন্টের জন্য
            $table->string('team_id')->nullable();

            // একক ইভেন্টের জন্য
            $table->string('student_id')->nullable();

            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('BDT');

            // Payment Status: Pending, Successful, Failed, Cancelled
            $table->string('status')->default('Pending');

            // পেমেন্ট মেথড (e.g., Bkash, Rocket, Card)
            $table->string('payment_method')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
