@extends('layouts.default')

@section('content')
    <h1 class="text-xl mb-8">{{ $user->email }}</h1>
    <form action="/paket-setting/change-permission/{{ $user->id }}" method="POST">
        @csrf
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="border rounded-lg overflow-hidden dark:border-gray-500">
                    <table class="min-w-full divide-y dark:divide-gray-500">
                        <thead class="bg-gray-400 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase dark:text-gray-400">Akses Fitur</th>
                                <th scope="col" class="flex items-center px-6 py-3 text-left text-xs font-medium text-gray-50 uppercase dark:text-gray-400">
                                    <input type="checkbox" id="checked-all" class="form-checkbox rounded text-primary" id="customCheck1">
                                    <label for="checked-all" class="ml-2">Pilih semua</label>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-500">
                            @foreach ($permissions as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ Str::title($item->name) }}
                                    </td>
                                    <td class="flex justify-start px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <input type="checkbox" value="{{ $item->name }}" @checked($user->hasPermissionTo($item->name)) name="permissions[]" class="form-checkbox rounded text-primary" id="customCheck1">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 w-56 px-4 rounded mt-4">Simpan</button>
        </div><script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                $("#checked-all").click(function () {
                    if ($(this).is(":checked")) {
                        $("input[type=checkbox]").prop('checked', true);
                    } else {
                        $("input[type=checkbox]").prop('checked', false);
                    }
                })
            })
        </script>
    </form>

@endsection
