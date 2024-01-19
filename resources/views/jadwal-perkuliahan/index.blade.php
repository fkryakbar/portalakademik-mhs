@extends('layouts.main')

@section('title', 'Jadwal Perkuliahan')
@section('content')
    <div class="lg:p-5 p-2 min-h-screen" id="app">
        <div class="flex gap-2 items-center text-gray-500  mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                <path
                    d="M12.75 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM7.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM8.25 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM9.75 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM10.5 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM12.75 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM14.25 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 13.5a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                <path fill-rule="evenodd"
                    d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z"
                    clip-rule="evenodd" />
            </svg>
            <h1 class="font-bold text-2xl">Jadwal Perkuliahan</h1>
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
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <!-- head -->
                    <thead>
                        <tr class="bg-gray-100 font-bold text-black">
                            <th>No</th>
                            <th>Mata Kuliah</th>
                            <th>Dosen</th>
                            <th>Jadwal</th>
                        </tr>
                    </thead>
                    <tbody id="data_presensi">
                        @foreach ($jadwal as $i => $j)
                            <tr>
                                <td class="whitespace-nowrap w-[10px]">{{ $i + 1 }}</td>
                                <td class="whitespace-nowrap w-[10px]">
                                    <p class="bg-blue-500 rounded text-xs p-1 text-white font-bold inline">
                                        {{ $j->mata_kuliah->kode }}
                                    </p>
                                    @if ($j->mata_kuliah)
                                        <div class="w-fit mt-2">
                                            <p class="text-gray-500 font-semibold">
                                                {{ $j->mata_kuliah->nama }}
                                            </p>
                                            <p class="text-gray-500">
                                                Semester {{ $j->mata_kuliah->semester }}
                                            </p>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @foreach ($j->dosen as $d)
                                        {{ $d->name }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $j->jadwal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
