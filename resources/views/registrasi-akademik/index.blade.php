@extends('layouts.main')

@section('title', 'Registrasi Akademik')

@section('content')
    <div class="lg:p-5 p-2 min-h-screen" id="app">
        <div class="flex gap-2 items-center text-gray-500  mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                <path
                    d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z" />
                <path
                    d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-8.105 4.342.75.75 0 01-.832 0 47.877 47.877 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286A48.4 48.4 0 016 13.18v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.661a6.729 6.729 0 00.551-1.608 1.5 1.5 0 00.14-2.67v-.645a48.549 48.549 0 013.44 1.668 2.25 2.25 0 002.12 0z" />
                <path
                    d="M4.462 19.462c.42-.419.753-.89 1-1.394.453.213.902.434 1.347.661a6.743 6.743 0 01-1.286 1.794.75.75 0 11-1.06-1.06z" />
            </svg>
            <h1 class="font-bold text-2xl">Registrasi Akademik</h1>
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

        @if ($belum_registrasi)
            <div class="mt-10 rounded-lg p-5 shadow">
                @if ($belum_registrasi->file_bukti_path)
                    <div class="font-semibold text-white bg-amber-400 p-2 rounded-lg w-fit">
                        Sedang Melakukan Verifikasi pembayaran semester
                        {{ $belum_registrasi->tahun_ajaran->nama_tahun_ajaran }}
                    </div>
                    <p class="mt-3">Bukti yang sudah diupload : </p>
                    <a href="{{ asset('storage/' . $belum_registrasi->file_bukti_path) }}" target="_blank">
                        <div class="bg-blue-500 text-white font-semibold p-2 rounded-lg mt-1 w-fit">
                            Buka File
                        </div>
                    </a>
                @else
                    <div class="font-semibold text-white bg-amber-400 p-2 rounded-lg w-fit">
                        Segera lakukan pembayaran untuk semester {{ $belum_registrasi->tahun_ajaran->nama_tahun_ajaran }}
                    </div>

                    <form action="" class="mt-3" enctype="multipart/form-data" id="upload_bukti" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="file_input" class="block mb-2 text-sm font-medium text-gray-900">Upload Bukti
                                Pembayaran</label>
                            <p class="mt-3 text-xs">File JPEG, PNG, PDF dengan max 500 KB</p>
                            <input type="text" style="display: none" name="kode_tahun_ajaran"
                                value="{{ $belum_registrasi->kode_tahun_ajaran }}">
                            <input type="file" class="file-input file-input-bordered w-full max-w-xs mt-3"
                                name="bukti" />
                        </div>
                        <button id="upload_button"
                            class="btn btn-sm bg-green-500 hover:bg-green-700 text-white border-0">Upload</button>
                    </form>
                @endif
            </div>
        @endif
        <div class="flex gap-2 items-center mt-5 font-bold text-xl text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            <h1 class="">Riwayat Registrasi</h1>
        </div>
        <div class="mt-3 grid lg:grid-cols-3 grid-cols-1 gap-3">
            @foreach ($sudah_registrasi as $r)
                <div class="bg-gray-50 p-5 rounded-lg shadow">
                    <div class="text-center">
                        <p>Semester</p>
                        <p class="text-xl font-bold">{{ $r->tahun_ajaran->nama_tahun_ajaran }}</p>
                        <p class="p-2 rounded-lg bg-green-500 w-fit mx-auto font-bold uppercase mt-2 text-sm text-white">
                            Lunas
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    @if ($belum_registrasi && !$belum_registrasi->file_bukti_path)
        <script>
            const upload_button = document.getElementById('upload_button');
            const form = document.getElementById('upload_bukti');
            upload_button.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Upload Bukti?',
                    text: "Bukti setelah diupload tidak dapat diubah",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Upload!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            })
        </script>
    @endif
@endsection
