@extends('layouts.default')

@section('content')
    <section class="grid grid-cols-12 gap-5">
        <div class="sm:col-span-6 col-span-12 space-y-3">
            <div>
                <h1 class="text-lg">Nama: </h1>
                <h3 class="text-base">{{ $subscription->user->name ?? '-' }}</h3>
            </div>
            <div>
                <h1 class="text-lg">Email: </h1>
                <h3 class="text-base">{{ $subscription->user->email ?? '-' }}</h3>
            </div>
            <div>
                <h1 class="text-lg">Status Paket Sekarang: </h1>
                <h3 @class(['text-red-600 text-base' => $subscription->status == 'expired', 'text-green-600 text-base' => $subscription->status == 'active'])>
                    {{ Str::title($subscription->status) ?? "-" }}
                </h3>
            </div>
        </div>
        <div class="sm:col-span-6 col-span-12 space-y-3">
            <div>
                <h1 class="text-lg">No. HP: </h1>
                <h3 class="text-base">{{ $subscription->user->user_profile->nomor_hp ?? '-' }}</h3>
            </div>
            <div>
                <h1 class="text-lg">Alamat: </h1>
                <h3 class="text-base">{{ $subscription->user->user_profile->alamat ?? '-' }}</h3>
            </div>
        <div>
    </section>
    <div class="overflow-x-auto mt-5">
        <div class="min-w-full inline-block align-middle bg-white dark:bg-gray-800">
            <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                <div class="py-3 px-4">
                    <form action="/dashboard-owner/detail/{{ $subscription->id }}" autocomplete="off" method="POST" class="flex items-end gap-2">
                        @csrf
                        @method("GET")
                        <div>
                            <label for="">From</label>
                            <input type="date" value="{{ request()->from ?? '' }}" name="from" class="form-input">
                        </div>
                        <div>
                            <label for="">To</label>
                            <input type="date" value="{{ request()->to ?? '' }}" name="to" class="form-input">
                        </div>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-teal-600 border border-transparent rounded-md shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">Filter</button>
                        @if (request()->from || request()->to)
                            <a href="/dashboard-owner/detail/{{ $subscription->id }}" class="px-4 py-2 text-sm font-medium text-white bg-rose-600 border border-transparent rounded-md shadow-sm hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Paket</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($payment_history->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Data kosong</td>
                                </tr>
                            @endif
                            @foreach ($payment_history as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $payment_history->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ Str::title($item->nama_paket) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ Str::title($item->periode) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="py-1 px-4">
                    {{ $payment_history->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
