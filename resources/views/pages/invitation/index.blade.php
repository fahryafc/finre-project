@extends('layouts.default')

@section('content')
    <div class="px-3 py-5 dark:bg-slate-800 bg-white shadow-md rounded-md max-w-xl mx-auto">
        <div class="mb-5">
            <h1 class="text-lg text-center">Anda diundang member oleh {{ $detail->name }}</h1>
            <h1 class="text-base text-center">{{ $detail->email }}</h1>
        </div>
        <form action="/invitation-process/{{ $detail->invite_id }}" method="POST" class="flex flex-wrap justify-center items-center gap-5 max-w-xl mx-auto">
            @csrf
            @method('PUT')
            <button type="submit" class="bg-green-500 cursor-pointer font-semibold block w-36 p-3 text-white rounded-md" value="accepted" name="status" id="">Accepted</button>
            <button type="submit" class="bg-red-500 cursor-pointer font-semibold block w-36 p-3 text-white rounded-md" value="rejected" name="status" id="">Rejected</button>
        </form>
    </div>
@endsection
