@extends('layouts.vertical', ['title' => 'Paket'])
@section('content')
    <div class="p-5 dark:bg-slate-700 bg-white rounded-md shadow">
        <div class="mb-2">
            <h2 class="text-base">Nama Paket:</h2>
            <p class="text-base">{{ Str::title($information->nama_paket) }}</p>
        </div>
        <div class="mb-2">
            <h2 class="text-base">Maksimal Jumlah Member:</h2>
            <p class="text-base">{{ $information->max_invite }}</p>
        </div>
        <div class="mb-2">
            <h2 class="text-base">Tanggal Berakhir:</h2>
            <p class="text-base">{{ $information->ends_at->locale('id')->isoFormat('D MMMM YYYY') }}</p>
        </div>

        <section class="mt-6">
            <h1 class="text-base mb-2 text-center uppercase">Undang Member</h1>
            <form method="POST" action="/paket-setting/invites" class="p-3 max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-md shadow mb-10" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="text-gray-800 text-sm font-medium inline-block mb-2">Email address</label>
                    <input type="email" name="email" class="form-input" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn bg-primary text-white">Kirim undangan</button>
                </div>
            </form>
            <h1 class="text-base mb-2 text-center uppercase">Daftar Member</h1>
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <div class="border rounded-lg overflow-hidden dark:border-gray-500">
                        <table class="min-w-full divide-y dark:divide-gray-500">
                            <thead class="bg-gray-400 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase dark:text-gray-400">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase dark:text-gray-400">Status</th>
                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-50 uppercase dark:text-gray-400">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-gray-500">
                                @if ($members->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">Data kosong</td>
                                    </tr>
                                @endif
                                @foreach ($members as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $item->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            @switch($item->status)
                                                @case("accepted")
                                                    <p class="text-center w-20 gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-500 text-white">Diterima</p>
                                                    @break
                                                @case("rejected")
                                                    <p class="text-center w-20 gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white">Ditolak</p>
                                                    @break
                                                @default
                                                    <p class="text-center w-20 gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-yellow-500 text-white">Pending</p>
                                            @endswitch
                                        </td>
                                        <td class="flex items-center gap-5 justify-end px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            @if ($item->status == "accepted")
                                                <a href="/paket-setting/invites/{{ $item->email }}" class="text-primary text-base hover:text-sky-700">Edit</a>
                                            @endif
                                            @if ($item->status == "rejected")
                                                <form action="/paket-setting/invites" method="POST">
                                                    @csrf
                                                    <input type="email" name="email" value="{{ $item->email }}" hidden>
                                                    <button type="submit" class="text-green-500 hover:text-green-700">Re-Invite</button>
                                                </form>
                                            @endif
                                            <form action="/paket-setting/invites/{{ $item->id }}" method="POST">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
