<?php

namespace App\Http\Controllers;

use App\Models\KartuStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapitulasiHasilStudiController extends Controller
{
    public function index()
    {
        $rekap = KartuStudi::where('username', Auth::user()->username)->where('huruf', '!=', null)->with('mata_kuliah')->get();

        $ipk = 0;
        $total_bobot = 0;
        $total_sks = 0;
        foreach ($rekap as $key => $r) {
            $total_bobot += (float) $r->bobot;
            $total_sks += (float) $r->mata_kuliah->jumlah_sks;

            if ($total_sks > 0) {
                $ipk = $total_bobot / $total_sks;
                $ipk = number_format($ipk, 2);
            }
        }
        return  view('rekapitulasi-hasil-studi.index', compact('rekap', 'ipk', 'total_bobot', 'total_sks'));
    }
}
