<?php

namespace App\Http\Controllers;

use App\Models\KartuStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KRSController extends Controller
{
    public function index()
    {
        $tahun_ajaran =  KartuStudi::where('username', Auth::user()->username)->with('get_tahun_ajaran')->select('tahun_ajaran')->groupBy('tahun_ajaran')->get();

        return view('krs.index', compact('tahun_ajaran'));
    }



    public function api_get_krs($kode_tahun_ajaran)
    {
        $krs = KartuStudi::where('username', Auth::user()->username)->where('tahun_ajaran', $kode_tahun_ajaran)->with('mata_kuliah')->latest()->get();
        $total_sks = 0;
        foreach ($krs as $key => $k) {
            if ($k->mata_kuliah) {
                $total_sks += (int) $k->mata_kuliah->jumlah_sks;
            }
        }
        return response([
            'message' => 'success',
            'data' => [
                'krs' => $krs,
                'total_sks' => $total_sks
            ]
        ]);
    }
}
