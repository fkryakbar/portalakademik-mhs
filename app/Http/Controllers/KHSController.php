<?php

namespace App\Http\Controllers;

use App\Models\KartuStudi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class KHSController extends Controller
{
    public function index()
    {
        $tahun_ajaran =  KartuStudi::where('username', Auth::user()->username)->with('get_tahun_ajaran')->select('tahun_ajaran')->groupBy('tahun_ajaran')->get();
        return view('khs.index', compact('tahun_ajaran'));
    }

    public function cetak($kode_tahun_ajaran)
    {
        $khs = KartuStudi::where('username', Auth::user()->username)->where('tahun_ajaran', $kode_tahun_ajaran)->with('mata_kuliah')->latest()->get();
        $tahun_ajaran = TahunAjaran::where('kode_tahun_ajaran', $kode_tahun_ajaran)->first();
        $tanggal =  Carbon::today()->translatedFormat('d F Y');
        $ip = 0;
        $total_bobot = 0;
        $total_sks = 0;
        if (count($khs) > 0) {
            foreach ($khs as $key => $k) {
                if ($k->mata_kuliah) {
                    $total_sks += (float) $k->mata_kuliah->jumlah_sks;
                    $total_bobot += (float)$k->bobot;
                }
            }
            if ($total_sks > 0) {
                $ip = number_format($total_bobot / $total_sks, 2);
            }
        }
        $pdf = Pdf::loadView('cetak.khs', compact('khs', 'ip', 'total_bobot', 'total_sks', 'tanggal', 'tahun_ajaran'));
        // return view('cetak.khs', compact('khs', 'ip', 'total_bobot', 'total_sks', 'tanggal', 'tahun_ajaran'));
        return $pdf->stream('Kartu Hasil Studi.pdf');
    }

    public function api_get_khs($kode_tahun_ajaran)
    {
        $khs = KartuStudi::where('username', Auth::user()->username)->where('tahun_ajaran', $kode_tahun_ajaran)->with('mata_kuliah')->latest()->get();
        $ip = 0;
        $total_bobot = 0;
        $total_sks = 0;
        if (count($khs) > 0) {
            foreach ($khs as $key => $k) {
                if ($k->mata_kuliah) {
                    $total_sks += (float) $k->mata_kuliah->jumlah_sks;
                    $total_bobot += (float)$k->bobot;
                }
            }
            if ($total_sks > 0) {
                $ip = number_format($total_bobot / $total_sks, 2);
            }
        }
        return response([
            'message' => 'success',
            'data' => [
                'khs' => $khs,
                'total_sks' => $total_sks,
                'total_bobot' => $total_bobot,
                'ip' => $ip
            ]
        ]);
    }
}
