<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $mahasiswa = User::where('role', 'mahasiswa')->where('id', Auth::user()->id)->firstOrFail();
        $jurusan = Jurusan::latest()->get();
        return view('profile.index', compact('mahasiswa', 'jurusan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'profile' => 'file|mimes:jpeg,png|max:100',
            'nik' => 'max:100',
            'email' => 'max:100',
            'no_telp' => 'max:30',
            'jenis_kelamin' => 'max:20',
            'tempat_lahir' => 'max:50',
            'tanggal_lahir' => 'max:15',
            'alamat' => 'max:300',
            'asal_sekolah' => 'max:50',
        ], [
            'profile.mimes' => 'Gambar harus berupa Format JPEG atau PNG',
            'profile.max' => 'Maksimal Ukuran Gambar adalah 100 KB',
            'email.max' => 'Maksimal karakter email adalah 100 karakter',
            'no_telp.max' => 'Maksimal karakter No Telepon adalah 30 karakter',
            'tempat_lahir.max' => 'Maksimal karakter Tempat Lahir adalah 50 karakter',
            'tanggal_lahir.max' => 'Maksimal karakter Tanggal Lahir adalah 15 karakter',
            'alamat.max' => 'Maksimal karakter Alamat adalah 300 karakter',
            'asal_sekolah.max' => 'Maksimal karakter Asal sekolah adalah 50 karakter',
        ]);
        $mahasiswa = User::where('id', Auth::user()->id)->where('role', 'mahasiswa')->firstOrFail();
        if ($request->file('profile')) {
            if ($mahasiswa->biodata->gambar) {
                Storage::delete($mahasiswa->biodata->gambar);
                $path =  $request->file('profile')->store('/profile');
            } else {
                $path =  $request->file('profile')->store('/profile');
            }
            $request->merge(['gambar' => $path]);
        }

        $mahasiswa->biodata->update($request->except(['angkatan', 'program_studi', 'profile']));

        return back()->with('Biodata berhasil diperbarui');
    }
}
