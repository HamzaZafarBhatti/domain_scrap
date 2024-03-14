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
        Schema::table('job_dones', function (Blueprint $table) {
            $table->integer('progress')->default(0);
            $table->string('city_name')->nullable();
            $table->string('country_name')->nullable();
            $table->string('niche_name')->nullable();
            $table->string('sub_niche_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_dones', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
