@extends('layouts.default')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-center">
            <h3 class="text-xl font-semibold text-teal-600 dark:text-teal-400">{{ Str::title($data['nama']) }}</h3>
            <p class="text-4xl font-bold text-gray-800 dark:text-gray-200 my-4">
                Rp <span>{{ $data['harga'] }}</span> K<span class="text-lg text-gray-500 dark:text-gray-400">/Bulan</span>
            </p>
            <ul class="text-left space-y-2 text-gray-600 dark:text-gray-300">
                <li class="flex items-center">
                    <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                    <span class="ml-2">1 Data Usaha</span>
                </li>
                <li class="flex items-center">
                    <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                    <span class="ml-2">{{ $data['jml_user'] }} User</span>
                </li>
                <li class="flex items-center">
                    <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                    <span class="ml-2">Produk & Inventori</span>
                </li>
                <li class="flex items-center">
                    <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                    <span class="ml-2">Penghitungan Pajak</span>
                </li>
                <li class="flex items-center">
                    <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                    <span class="ml-2">Manajemen Stok</span>
                </li>
                <li class="flex items-center">
                    <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                    <span class="ml-2">Pencatatan Hutang & Piutang</span>
                </li>
                <li class="flex items-center">
                    <span class="text-teal-600 dark:text-teal-400">&#10003;</span>
                    <span class="ml-2">Laporan Keuangan</span>
                </li>
            </ul>
            <button id="pay-button" class="buy mt-6 px-4 py-2 bg-teal-600 text-white dark:bg-teal-500 rounded-lg w-56">Bayar Sekarang</button>
        </div>
    </div>

    <div id="snap-container"></div>

    @push('scripts')
        <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}">
        </script>
        <script type="text/javascript">
            $("#pay-button").click(function () {
                window.snap.pay(@json($snapToken), {
                    onSuccess: function(result){
                        /* You may add your own implementation here */
                        window.location.href = `/payment-process/?name=${@json($data['nama'])}&price=${@json($data['harga'])}&users=${@json($data['jml_user'])}&periode=${@json($data['periode'])}`
                        console.log(result)
                    },
                    onPending: function(result){
                    /* You may add your own implementation here */
                    alert("wating your payment!"); console.log(result);
                    },
                    onError: function(result){
                    /* You may add your own implementation here */
                    alert("payment failed!"); console.log(result);
                    },
                    onClose: function(){
                    /* You may add your own implementation here */
                    alert('you closed the popup without finishing the payment');
                    }
                })
            })
        </script>
    @endpush
@endsection
