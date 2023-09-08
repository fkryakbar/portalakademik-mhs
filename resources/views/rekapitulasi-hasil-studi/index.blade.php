@extends('layouts.main')

@section('title', 'Rekapitulasi Hasil Studi')
@section('content')
    <div class="lg:p-5 p-2 min-h-screen" id="app">
        <div class="flex gap-2 items-center text-gray-500  mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                <path fill-rule="evenodd"
                    d="M2.25 2.25a.75.75 0 000 1.5H3v10.5a3 3 0 003 3h1.21l-1.172 3.513a.75.75 0 001.424.474l.329-.987h8.418l.33.987a.75.75 0 001.422-.474l-1.17-3.513H18a3 3 0 003-3V3.75h.75a.75.75 0 000-1.5H2.25zm6.54 15h6.42l.5 1.5H8.29l.5-1.5zm8.085-8.995a.75.75 0 10-.75-1.299 12.81 12.81 0 00-3.558 3.05L11.03 8.47a.75.75 0 00-1.06 0l-3 3a.75.75 0 101.06 1.06l2.47-2.47 1.617 1.618a.75.75 0 001.146-.102 11.312 11.312 0 013.612-3.321z"
                    clip-rule="evenodd" />
            </svg>
            <h1 class="font-bold text-2xl">Rekapitulasi Hasil Studi</h1>
        </div>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="p-3 bg-red-400 rounded-lg my-2">
                    <li>{{ $error }}</li>
                </div>
            @endforeach
        @endif
        @if (session()->has('message'))
            <div class="p-3 bg-green-500 text-white rounded-lg my-2">
                <p>{{ session('message') }}</p>
            </div>
        @endif

        <div class="mt-10">
            <table class="mb-3 lg:text-base text-sm">
                <tr>
                    <td>Nama</td>
                    <td class="pl-4">: {{ Auth::user()->name }}</td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td class="pl-4">: {{ Auth::user()->username }}</td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td class="pl-4">: {{ Auth::user()->biodata->jurusan->nama_jurusan }}</td>
                </tr>
            </table>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <!-- head -->
                    <thead>
                        <tr class="bg-gray-100 font-bold text-black">
                            <th>No</th>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th>K</th>
                            <th>N</th>
                            <th>KxN</th>
                        </tr>
                    </thead>
                    <tbody id="data_presensi">
                        @foreach ($rekap as $i => $r)
                            <tr>
                                <td class="whitespace-nowrap w-[10px]">{{ $i + 1 }}</td>
                                <td class="whitespace-nowrap w-[10px]">{{ $r->kode_mata_kuliah }}</td>
                                <td>
                                    @if ($r->mata_kuliah)
                                        {{ $r->mata_kuliah->nama }}
                                    @endif
                                </td>
                                <td class="whitespace-nowrap w-[10px]">
                                    @if ($r->mata_kuliah)
                                        {{ $r->mata_kuliah->jumlah_sks }}
                                    @endif
                                </td>
                                <td class="whitespace-nowrap w-[10px]">
                                    {{ $r->huruf }}
                                </td>
                                <td class="whitespace-nowrap w-[10px]">{{ $r->bobot }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-100 font-bold text-black">
                            <td colspan="3" class="text-center">
                                Jumlah
                            </td>
                            <td>{{ $total_sks }}</td>
                            <td></td>
                            <td>
                                {{ $total_bobot }}
                            </td>
                        </tr>
                        <tr class="bg-gray-100 font-bold text-black">
                            <td colspan="3" class="text-center">
                                Indeks Prestasi Kumulatif (IPK)
                            </td>
                            <td></td>
                            <td>{{ $ipk }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
