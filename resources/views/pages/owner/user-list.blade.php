@extends('layouts.default')

@section('content')
    <div class="overflow-x-auto mt-5">
        <div class="min-w-full inline-block align-middle bg-white dark:bg-gray-800">
            <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                <div class="flex justify-between gap-5 items-end py-3 px-4">
                    <form action="/dashboard-owner/user-list" class="flex items-center gap-3" method="POST" autocomplete="off">
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
                            <a href="/dashboard-owner/user-list" class="px-4 py-2 text-sm font-medium text-white bg-rose-600 border border-transparent rounded-md shadow-sm hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">Reset</a>
                        @endif
                    </form>
                    <h2 class="text-lg">Total: {{ $data->total() }}</h2>
                </div>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. HP</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Langganan Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($data->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Data kosong</td>
                                </tr>
                            @endif
                            @foreach ($data as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $data->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $item->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $item->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $item->user_profile->nomor_hp }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        @if ($item->subscription)
                                            <span @class(['text-red-600' => $item->subscription->status == 'expired', 'text-green-600' => $item->subscription->status == 'active'])>
                                                {{ Str::title($item->subscription->status) ?? "-" }}
                                            </span>
                                        @else
                                            <span class="text-yellow-400">Belum berlangganan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="py-1 px-4">
                    {{ $data->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
