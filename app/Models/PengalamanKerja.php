<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengalamanKerja extends Model
{
    protected $fillable = [
        'jabatan',
        'nama_perusahaan',
        'nama_lain_perusahaan',
        'tahun_mulai',
        'jumlah_bulan',
        'kerja_saat_ini',
        'keterangan',
    ];
}
