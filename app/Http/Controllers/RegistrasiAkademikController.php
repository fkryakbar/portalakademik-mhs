<?php

namespace App\Http\Controllers;

use App\Models\RiwayatRegistrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrasiAkademikController extends Controller
{
    public function index()
    {
        $belum_registrasi = RiwayatRegistrasi::where('username', Auth::user()->username)->where('status_registrasi', 'pending')->with('tahun_ajaran')->first();
        $sudah_registrasi = RiwayatRegistrasi::where('username', Auth::user()->username)->where('status_registrasi', 'verified')->with('tahun_ajaran')->get();
        return view('registrasi-akademik.index', compact('belum_registrasi', 'sudah_registrasi'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'bukti' => 'required|file|mimes:jpeg,png,pdf|max:500',
            'kode_tahun_ajaran' => 'required'
        ], [
            'bukti.required' => 'File bukti wajib diupload',
            'bukti.mimes' => 'File harus berupa JPEG, PNG, atau PDF',
            'bukti.max' => 'File maksimal 500 KB'
        ]);

        $riwayat = RiwayatRegistrasi::where('username', Auth::user()->username)->where('status_registrasi', 'pending')->where('kode_tahun_ajaran', $request->kode_tahun_ajaran)->firstOrFail();

        $file_bukti_path = $request->file('bukti')->store('/bukti_registrasi');

        $riwayat->update([
            'file_bukti_path' => $file_bukti_path
        ]);

        return back()->with('message', 'File Berhasil di Submit');
    }
}
