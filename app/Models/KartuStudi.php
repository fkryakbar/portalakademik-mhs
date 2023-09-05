<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStudi extends Model
{
    use HasFactory;

    protected $table = 'kartu_studi';
    protected $guarded = [];

    protected $map = [
        'tahun_ajaran' => 'kode_tahun_ajaran'
    ];

    public function get_tahun_ajaran()
    {
        return $this->hasOne(TahunAjaran::class, 'kode_tahun_ajaran', 'tahun_ajaran');
    }

    public function mata_kuliah()
    {
        return $this->hasOne(MataKuliah::class, 'kode', 'kode_mata_kuliah');
    }
}
