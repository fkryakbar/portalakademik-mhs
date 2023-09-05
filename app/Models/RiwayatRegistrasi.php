<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatRegistrasi extends Model
{
    use HasFactory;
    protected $table = 'riwayat_registrasi';
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->hasOne(User::class, 'username', 'username');
    }

    public function tahun_ajaran()
    {
        return $this->hasOne(TahunAjaran::class, 'kode_tahun_ajaran', 'kode_tahun_ajaran');
    }
}
