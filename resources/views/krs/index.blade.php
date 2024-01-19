@extends('layouts.main')

@section('title', 'Kartu Rencana Studi')
@section('head-tag')
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
@endsection
@section('content')
    <div class="lg:p-5 p-2 min-h-screen" id="app">
        <div class="flex gap-2 items-center text-gray-500  mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                <path fill-rule="evenodd"
                    d="M2.625 6.75a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875 0A.75.75 0 018.25 6h12a.75.75 0 010 1.5h-12a.75.75 0 01-.75-.75zM2.625 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zM7.5 12a.75.75 0 01.75-.75h12a.75.75 0 010 1.5h-12A.75.75 0 017.5 12zm-4.875 5.25a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875 0a.75.75 0 01.75-.75h12a.75.75 0 010 1.5h-12a.75.75 0 01-.75-.75z"
                    clip-rule="evenodd" />
            </svg>
            <h1 class="font-bold text-2xl">Rencana Studi</h1>
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
        <div class="flex justify-between items-center mt-4">
            <p class="hidden lg:block font-bold text-gray-700 text-xl">Data KRS</p>
            <div class="flex gap-2 items-center">
                <p>Semester</p>
                <select id="tahun-ajaran"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                    @foreach ($tahun_ajaran as $i => $tahun)
                        <option v-on:click="getData({{ $tahun->get_tahun_ajaran->kode_tahun_ajaran }})"
                            @selected($i == 0) value="{{ $tahun->get_tahun_ajaran->kode_tahun_ajaran }}">
                            {{ $tahun->get_tahun_ajaran->nama_tahun_ajaran }}</option>
                    @endforeach
                </select>
            </div>
        </div>
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
            <div class="flex justify-end mb-3">
                <div class="dropdown dropdown-end">
                    <label tabindex="0"
                        class="btn btn-sm bg-blue-500 hover:bg-blue-700 border-none flex gap-2 text-white">
                        Cetak
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </label>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                        @foreach ($tahun_ajaran as $i => $tahun)
                            <li><a href="/krs/cetak/{{ $tahun->get_tahun_ajaran->kode_tahun_ajaran }}"
                                    target="_blank">{{ $tahun->get_tahun_ajaran->nama_tahun_ajaran }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <!-- head -->
                    <thead>
                        <tr class="bg-gray-100 font-bold text-black">
                            <th>No</th>
                            <th>Detail</th>
                            <th>Dosen</th>
                            <th>Jadwal</th>
                            <th>SKS</th>
                        </tr>
                    </thead>
                    <tbody id="data_presensi">
                        <tr v-for="(d,i) in data" :key="d.id">
                            <td class="whitespace-nowrap w-[10px]">@{{ i + 1 }}</td>
                            <td class="whitespace-nowrap w-[10px]">
                                <p class="bg-blue-500 rounded text-xs p-1 text-white font-bold inline">
                                    @{{ d.kode_mata_kuliah }}
                                </p>
                                <div v-if="d.mata_kuliah" class="w-fit mt-2">
                                    <p class="text-gray-500 font-semibold">
                                        @{{ d.mata_kuliah.nama }}
                                    </p>
                                    <p class="text-gray-500">
                                        Semester @{{ d.mata_kuliah.semester }}
                                    </p>
                                </div>
                            </td>
                            <td>@{{ d.dosen_ampu }}</td>
                            <td>@{{ d.jadwal }}</td>
                            <td>
                                <div v-if="d.mata_kuliah">
                                    @{{ d.mata_kuliah.jumlah_sks }}
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="4" class="text-center">Total</td>
                            <td v-text="total_sks"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p v-if="isLoading" class="text-center font-semibold mt-5 text-gray-500">Loading ...</p>
            <p v-if="!isLoading && data.length == 0 " class="text-center font-semibold mt-5 text-gray-500">Belum Ada KRS
            </p>
        </div>
    </div>
    <script>
        const tahun_ajaran_toggle = document.getElementById('tahun-ajaran');

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        const {
            createApp,
            ref,
            onMounted
        } = Vue

        createApp({
            setup() {
                const data = ref([])
                const isLoading = ref(false)
                const total_sks = ref(0)

                function getData(tahun_ajaran) {
                    isLoading.value = true
                    fetch(`/api/krs/${tahun_ajaran}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json'
                            }
                        }).then(response => response.json())
                        .then(res => {
                            data.value = res.data.krs;
                            total_sks.value = res.data.total_sks;
                            isLoading.value = false
                        })
                        .catch(error => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something Went Wrong'
                            })
                        });
                }

                onMounted(() => {
                    $(document).ready(function() {
                        $('#tahun-ajaran').select2().on('change', function(e) {
                            getData(e.target.value)
                        });;
                    })
                    getData(tahun_ajaran_toggle.value)
                })

                return {
                    data,
                    getData,
                    isLoading,
                    total_sks
                }
            }
        }).mount('#app')
    </script>
@endsection
