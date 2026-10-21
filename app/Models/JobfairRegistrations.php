<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobfairRegistrations extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'job_fair_registrations';
    protected $fillable = [
        'email', 'nama_lengkap', 'tanggal_acara', 'tanggal_lahir',
        'jenis_kelamin', 'nomor_hp', 'nik', 'alamat_domisili', 
        'kota_domisili', 'pendidikan_akhir', 'nama_sekolah',
        'pengalaman_kerja_terakhir', 'pelatihan_habitat', 
        'pelatihan_lain', 'nama_lembaga_pelatihan', 'form_komentar','konfirmasi', 'konfirmasi_event'
    ];
}
