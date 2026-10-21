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
        Schema::create('new_registers', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('nama_lengkap');
            $table->string('tanggal_acara');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Pria', 'Wanita']);
            $table->string('nomor_hp');
            $table->string('nik', 16);
            $table->text('alamat_domisili');
            $table->string('kota_domisili');
            $table->string('pendidikan_akhir');
            $table->string('nama_sekolah')->nullable();
            $table->string('pengalaman_kerja_terakhir')->nullable();
            $table->enum('pelatihan_habitat', ['Iya', 'Tidak']);
            $table->enum('pelatihan_lain', ['Iya', 'Tidak']);
            $table->string('nama_lembaga_pelatihan')->nullable();
            $table->boolean('konfirmasi')->default(false); 
            $table->boolean('konfirmasi_event')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_registers');
    }
};
