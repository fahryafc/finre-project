@extends('layouts.default')

@section('content')
    <div class="grid grid-cols-12 gap-2">
        <div class="xl:col-span-3 sm:col-span-6 col-span-12">
            <div class="h-full p-3 bg-white dark:bg-gray-800 rounded border dark:border-gray-700">
                <div class="space-y-2">
                    <img src="{{ asset('images/group.png') }}" alt="group" class="block mx-auto w-20">
                    <div class="flex justify-around flex-wrap items-center">
                        <div>
                            <h1 class="text-xl text-center">{{ $data['user_active'] }}</h1>
                            <h2 class="text-xl text-center">Active</h2>
                        </div>
                        <div>
                            <h1 class="text-xl text-center">{{ $data['user_inactive'] }}</h1>
                            <h2 class="text-xl text-center">InActive</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-3 sm:col-span-6 col-span-12">
            <div class="h-full p-3 bg-white space-y-2 dark:bg-gray-800 rounded border dark:border-gray-700">
                <img src="{{ asset('images/box.png') }}" alt="group" class="block mx-auto w-20">
                <div>
                    <h1 class="text-center text-xl">{{ $data['paket_personal'] }}</h1>
                    <h2 class="text-center text-xl">Personal</h2>
                </div>
            </div>
        </div>
        <div class="xl:col-span-3 sm:col-span-6 col-span-12">
            <div class="h-full p-3 bg-white space-y-2 dark:bg-gray-800 rounded border dark:border-gray-700">
                <img src="{{ asset('images/rating.png') }}" alt="group" class="block mx-auto w-20">
                <div>
                    <h1 class="text-center text-xl">{{ $data['paket_business'] }}</h1>
                    <h2 class="text-center text-xl">Business</h2>
                </div>
            </div>
        </div>
        <div class="xl:col-span-3 sm:col-span-6 col-span-12">
            <div class="h-full p-3 bg-white space-y-2 dark:bg-gray-800 rounded border dark:border-gray-700">
                <img src="{{ asset('images/team-building.png') }}" alt="group" class="block mx-auto w-20">
                <div>
                    <h1 class="text-center text-xl">{{ $data['paket_enterprise'] }}</h1>
                    <h2 class="text-center text-xl">Enterprise</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto mt-5">
        <div class="min-w-full inline-block align-middle bg-white dark:bg-gray-800">
            <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                <div class="flex justify-between gap-5 items-end py-3 px-4">
                    <form action="/dashboard-owner" class="flex items-center gap-3" method="POST" autocomplete="off">
                        @csrf
                        @method("GET")
                        <div class="relative max-w-xs">
                            <label for="table-with-pagination-search" class="sr-only">Search</label>
                            <input type="search" name="keyword" value="{{ request()->keyword ?? '' }}" id="table-with-pagination-search" class="form-input ps-11" placeholder="Search for names">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" >
                                </svg>
                            </div>
                        </div>
                        @if (request()->keyword)
                            <a href="/dashboard-owner" class="px-4 py-2 text-sm font-medium text-white bg-rose-600 border border-transparent rounded-md shadow-sm hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">Reset</a>
                        @endif
                    </form>
                    <form action="/dashboard-owner" autocomplete="off" method="POST" class="flex items-end gap-2">
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
                            <a href="/dashboard-owner" class="px-4 py-2 text-sm font-medium text-white bg-rose-600 border border-transparent rounded-md shadow-sm hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($data['all_user']->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Data kosong</td>
                                </tr>
                            @endif
                            @foreach ($data['all_user'] as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $data['all_user']->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $item->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $item->user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ Str::title($item->nama_paket) ?? "-" }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ Str::title($item->payment_history[count($item->payment_history) - 1]->periode) ?? "-" }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <span @class(['text-red-600' => $item->status == 'expired', 'text-green-600' => $item->status == 'active'])>
                                            {{ Str::title($item->status) ?? "-" }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <a href="/dashboard-owner/detail/{{ $item->id }}" class="text-indigo-600 bg-indigo-100 hover:bg-indigo-200 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="py-1 px-4">
                    {{ $data['all_user']->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
