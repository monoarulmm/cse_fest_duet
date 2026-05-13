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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Branding
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('site_name')->nullable();

            // Banners (একটু অপ্টিমাইজ করা হয়েছে)
            $table->string('main_banner1')->nullable();
            $table->string('main_banner2')->nullable();
            $table->string('main_banner3')->nullable();

            $table->json('sponsor_banner')->nullable();

            // Contact Info
            $table->string('phone_primary')->nullable();
            $table->string('phone_secondary')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            // Social Links
            $table->string('fb_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('whatsapp_link')->nullable();

            // গুরুত্বপূর্ণ অংশ: টাইমস্ট্যাম্প কলাম যোগ করা
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
