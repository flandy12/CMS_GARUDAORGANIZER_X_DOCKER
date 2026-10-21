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
        Schema::table('job_fair_registrations', function (Blueprint $table) {
            $table->boolean('konfirmasi')->default(false)->after('nama_lembaga_pelatihan'); 
            $table->boolean('konfirmasi_event')->default(false)->after('konfirmasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_fair_registrations', function (Blueprint $table) {
            $table->boolean('konfirmasi')->default(false)->after('nama_lembaga_pelatihan'); 
            $table->boolean('konfirmasi_event')->default(false)->after('konfirmasi');
        });
    }
};
