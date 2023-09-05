@extends('layouts.main')

@section('title', 'Hasil Studi')
@section('head-tag')
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
@endsection
@section('content')
    <div class="lg:p-5 p-2 min-h-screen" id="app">
        <div class="flex gap-2 items-center text-gray-500  mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                <path fill-rule="evenodd"
                    d="M2.25 2.25a.75.75 0 000 1.5H3v10.5a3 3 0 003 3h1.21l-1.172 3.513a.75.75 0 001.424.474l.329-.987h8.418l.33.987a.75.75 0 001.422-.474l-1.17-3.513H18a3 3 0 003-3V3.75h.75a.75.75 0 000-1.5H2.25zm6.04 16.5l.5-1.5h6.42l.5 1.5H8.29zm7.46-12a.75.75 0 00-1.5 0v6a.75.75 0 001.5 0v-6zm-3 2.25a.75.75 0 00-1.5 0v3.75a.75.75 0 001.5 0V9zm-3 2.25a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5z"
                    clip-rule="evenodd" />
            </svg>
            <h1 class="font-bold text-2xl">Hasil Studi</h1>
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
            <p class="hidden lg:block font-bold text-gray-700 text-xl">Kartu Hasil Studi</p>
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
            <div class="overflow-x-auto">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr class="bg-gray-100 font-bold">
                            <th>No</th>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th>W/P</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">Nilai Angka</th>
                            <th class="text-center">Nilai Huruf</th>
                            <th class="text-center">Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(d, i) in data" :key="d.id">
                            <td class="whitespace-nowrap w-[10px]">
                                @{{ i + 1 }}
                            </td>
                            <td>@{{ d.kode_mata_kuliah }}</td>
                            <td>
                                <div v-if="d.mata_kuliah">@{{ d.mata_kuliah.nama }}</div>
                            </td>
                            <td>
                                <div v-if="d.mata_kuliah">
                                    @{{ d.mata_kuliah.jenis }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div v-if="d.mata_kuliah">
                                    @{{ d.mata_kuliah.jumlah_sks }}
                                </div>
                            </td>
                            <td class="text-center">@{{ d.angka }}</td>
                            <td class="text-center">@{{ d.huruf }}</td>
                            <td class="text-center">@{{ d.bobot }}</td>
                        </tr>
                        <tr v-if="!isLoading && data.length > 0" class="bg-gray-100 font-bold">
                            <td colspan="4">
                                <p class="text-center font-bold">Jumlah</p>
                            </td>
                            <td>
                                <p class="text-center font-bold" v-text="total_sks"></p>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <p class="text-center font-bold" v-text="total_bobot"></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p v-if="!isLoading && data.length > 0" class="mt-3 font-semibold text-sm">Indeks Prestasi (IP) :
                    @{{ ip }}</p>
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
                const ip = ref(0);
                const total_bobot = ref(0);

                function getData(tahun_ajaran) {
                    isLoading.value = true
                    fetch(`/api/khs/${tahun_ajaran}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json'
                            }
                        }).then(response => response.json())
                        .then(res => {
                            data.value = res.data.khs;
                            ip.value = res.data.ip;
                            total_bobot.value = res.data.total_bobot;
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
                    total_sks,
                    total_bobot,
                    ip
                }
            }
        }).mount('#app')
    </script>
@endsection
