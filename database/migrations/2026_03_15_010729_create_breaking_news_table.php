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
        Schema::create('breaking_news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->timestamp('expiry_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('push_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breaking_news');
    }
};
