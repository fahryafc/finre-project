@extends('layouts.vertical', ['title' => 'Pengeluaran', 'sub_title' => 'Menu', 'mode' => $mode ?? '', 'demo' => $demo ?? '', 'isBreadcrumb' => false])

@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])

{{-- flatpickr daterange --}}
@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/@simonwep/pickr/dist/themes/classic.min.css', 'node_modules/@simonwep/pickr/dist/themes/monolith.min.css', 'node_modules/@simonwep/pickr/dist/themes/nano.min.css'])
@endsection

@section('content')
    <div class="grid grid-cols-5 gap-4 mb-3">
        <div class="col-span-5 lg:col-span-2">
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 h-full relative">
                <!-- Header Section -->
                <!-- Teks di Kiri Atas -->
                <h2 class="text-red-600 text-2xl font-bold h-[20&]">Total Pengeluaran</h2>
                <div class="flex items-center justify-between h-[70%]">
                    <div class="flex items-center h-full">
                        <!-- Angka di bawah Teks "Total Pengeluaran" -->
                        <p class="text-3xl xl:text-4xl font-extrabold text-red-600 mt-4">Rp 6.500.000</p>
                    </div>
                    <!-- Ikon Tetap di Kanan -->
                    <div class="text-red-600 p-3 rounded-full">
                        <i data-feather="activity" class="h-32 w-32"></i>
                    </div>
                </div>
                <!-- Footer Section -->
                <div class="flex items-center justify-between mt-4 h-[10%]">
                    <span class="text-gray-500 text-lg">Dari bulan lalu</span>
                </div>
            </div>
        </div>



        <div class="col-span-5 lg:col-span-3">
            <div class="bg-white shadow-md rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex gap-2">
                        <h2 class="text-lg font-semibold">Pengeluaran</h2>
                    </div>

                </div>

                <div class="flex flex-col items-start mb-2">
                    <p class="text-2xl font-semibold ml-2 text-red-500">
                        Rp 6.600.000
                        {{-- <span class="inline-flex items-center">
                            <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M16.21 16H7.79a1.76 1.76 0 0 1-1.59-1a2.1 2.1 0 0 1 .26-2.21l4.21-5.1a1.76 1.76 0 0 1 2.66 0l4.21 5.1A2.1 2.1 0 0 1 17.8 15a1.76 1.76 0 0 1-1.59 1" />
                            </svg>
                            <svg class="text-red-500 -ml-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 17a1.72 1.72 0 0 1-1.33-.64l-4.21-5.1a2.1 2.1 0 0 1-.26-2.21A1.76 1.76 0 0 1 7.79 8h8.42a1.76 1.76 0 0 1 1.59 1.05a2.1 2.1 0 0 1-.26 2.21l-4.21 5.1A1.72 1.72 0 0 1 12 17" />
                            </svg>
                        </span> --}}
                    </p>
                </div>

                <div id="chart-pengeluaran" class="apex-charts mt-4"></div>

            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 mb-3">
        <div class="card">
            <div class="p-6">
                <!-- Baris untuk Daftar Pengeluaran dan Tombol -->
                <div class="flex justify-between items-center mb-2">
                    <h5 class="text-lg font-semibold">Daftar Pengeluaran</h5>
                </div>
                <!-- Tanggal di bawah judul -->
                <p class="text-md font-base mb-4">14/12/2024 - 13/01/2025</p>

                <div id="table-gridjs"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite('resources/js/pages/custom.js')
    {{-- flatpickr --}}
    @vite(['resources/js/pages/highlight.js', 'resources/js/pages/form-color-pickr.js'])
@endsection
