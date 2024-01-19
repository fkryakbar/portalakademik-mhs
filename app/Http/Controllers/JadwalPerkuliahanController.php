<?php

namespace App\Http\Controllers;

use App\Models\KartuStudi;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPerkuliahanController extends Controller
{
    public function index()
    {
        $tahun_ajaran = TahunAjaran::latest()->firstOrFail();
        // $jadwal = KartuStudi::where('username', Auth::user()->username)->where('tahun_ajaran', $tahun_ajaran->kode_tahun_ajaran)->with('mata_kuliah')->get();
        $jadwal = Kelas::wherehas('mahasiswa', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->with(['dosen' => function ($query) {
            $query->where('role', 'dosen');
        }])->get();
        // dd($jadwal);
        return view('jadwal-perkuliahan.index', compact('jadwal'));
    }
}
