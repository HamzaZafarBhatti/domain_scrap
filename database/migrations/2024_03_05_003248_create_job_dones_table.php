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
        Schema::create('job_dones', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_country')->default(false);
            $table->boolean('is_city')->default(false);
            $table->boolean('is_niche')->default(false);
            $table->boolean('is_sub_niche')->default(false);
            $table->string('status')->nullable();
            $table->string('domain')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_dones');
    }
};
