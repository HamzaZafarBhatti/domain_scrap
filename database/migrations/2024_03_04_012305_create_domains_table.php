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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain_name');
            $table->string('keyword');
            $table->foreignId('country_id');
            $table->foreignId('city_id');
            $table->foreignId('niche_id')->nullable()->constrained();
            $table->foreignId('sub_niche_id')->nullable()->constrained();
            $table->boolean('is_job')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
