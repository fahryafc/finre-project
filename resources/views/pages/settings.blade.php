@extends('layouts.vertical', ['title' => 'Setting Profile'])

@section('content')
    <form method="POST" action="/settings-process" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="flex justify-end mb-5">
            <button type="submit" class="btn bg-primary text-white">Simpan Perubahan</button>
        </div>
        <div class="grid grid-cols-12 gap-5">
            <div class="@if(auth()->user()->hasRole('owner')) col-span-12 @else lg:col-span-6 col-span-12 @endif">
                <div class="max-w-2xl mx-auto p-3 bg-white rounded shadow dark:bg-gray-800">
                    @if ($errors->any())
                        <div class="bg-red-500 text-sm text-white rounded-md p-4 mb-3" role="alert">
                            <ul class="list-disc ps-4">
                                @foreach ($errors->all() as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="bg-green-500 text-sm text-white rounded-md p-4 mb-3" role="alert">
                            <span class="font-bold">Success!</span> {{ session('success') }}
                        </div>
                    @endif
                    <div class="mb-5">
                        <label for="exampleInputName" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama</label>
                        <input type="text" name="name" class="form-input" id="exampleInputName" value="{{ $user->name }}" aria-describedby="nameHelp" placeholder="Enter name" required>
                    </div>
                    <div class="mb-5">
                        <label for="exampleInputEmail1" class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                        <input type="email" name="email" class="form-input" id="exampleInputEmail1" value="{{ $user->email }}" aria-describedby="emailHelp" placeholder="Enter email" required>
                        <small id="emailHelp" class="form-text text-sm text-slate-700 dark:text-slate-400">We'll never share your email
                            with anyone else.</small>
                    </div>
                    <div class="mb-5">
                        <label for="exampleInputPassword1" class="text-gray-800 text-sm font-medium inline-block mb-2">Password</label>
                        <div class="flex items-center">
                            <input id="exampleInputPassword1" class="form-input rounded-e-none" name="password" type="password" placeholder="Enter your password">
                            <button id="toggle-password" class="rounded-e p-2 bg-slate-500 w-10" type="button">
                                <i class="mgc_eye_2_fill text-lg text-white m-0 p-0"></i>
                            </button>
                        </div>
                    </div>
                    @if (!auth()->user()->hasRole('owner'))
                        <div class="mb-5">
                            <label for="exampleInputName" class="text-gray-800 text-sm font-medium inline-block mb-2">Nama Perusahaan</label>
                            <input type="text" class="form-input" id="exampleInputName" name="company_name" value="{{ $user->user_profile->nama_perusahaan ?? '' }}" aria-describedby="nameHelp" placeholder="Enter company name">
                        </div>
                        <div class="mb-5">
                            <label for="exampleInputName" class="text-gray-800 text-sm font-medium inline-block mb-2">Bergerak Dibidang</label>
                            <select type="text" class="form-input" id="exampleInputName" name="bidang" aria-describedby="nameHelp">
                                <option selected disabled>Pilih Bidang</option>
                                <option value="Jasa" @selected($user->user_profile->bidang == 'Jasa')>Jasa</option>
                                <option value="Kesehatan" @selected($user->user_profile->bidang == 'Kesehatan')>Kesehatan</option>
                                <option value="Logistik" @selected($user->user_profile->bidang == 'Logistik')>Logistik</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="exampleInputPhone" class="text-gray-800 text-sm font-medium inline-block mb-2">No. HP</label>
                            <input type="text" name="phone" class="form-input" id="exampleInputPhone" value="{{ $user->user_profile->nomor_hp ?? '' }}" aria-describedby="phoneHelp" placeholder="Enter Phone" required>
                        </div>
                        <div class="mb-5">
                            <label for="exampleInputPhone" class="text-gray-800 text-sm font-medium inline-block mb-2">Alamat</label>
                            <input type="text" class="form-input" id="exampleInputPhone" name="address" value="{{ $user->user_profile->alamat ?? '' }}" aria-describedby="phoneHelp" placeholder="Enter address">
                        </div>
                        <div class="mb-5">
                            <label for="exampleInputPhone" class="text-gray-800 text-sm font-medium inline-block mb-2">Jumlah Karyawan</label>
                            <input type="number" min="0" class="form-input" id="exampleInputPhone" name="jumlah_karyawan" value="{{ $user->user_profile->jumlah_karyawan ?? '' }}" aria-describedby="phoneHelp" placeholder="Total Employee">
                        </div>
                        @if (!Auth::user()->email_verified_at)
                            <div class="mb-5">
                                <label for="exampleInputEmail1" class="block text-gray-800 text-sm font-medium mb-2">Email Verification</label>
                                <a href="/email/verify" class="btn bg-green-700 text-white">Verifiy Now</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @if (!auth()->user()->hasRole('owner'))
                <div class="lg:col-span-6 col-span-12">
                    <div id="img-preview" class="p-3 border border-dashed border-gray-400 h-96 rounded max-w-xl mx-auto flex items-center">
                        <i class="mgc_pic_2_line text-9xl max-w-3xl mx-auto block"></i>
                    </div>
                    <input id="file-input" type="file" name="image" accept="image/*" hidden>
                    <div class="flex items-center justify-center mt-5 gap-2">
                        <button id="upload-btn" type="button" class="btn bg-green-500 text-white">Pilih Foto Perusahaan</button>
                        <button id="delete-image" type="button" class="btn bg-red-500 text-white hidden">Hapus Foto</button>
                    </div>
                </div>
            @endif
        </div>
    </form>
    @if (!auth()->user()->hasRole('owner'))
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                if (@json($user->user_profile->gambar)) {
                    $("#delete-image").removeClass("hidden");
                    $("#img-preview").html(`<img src="${@json(asset('storage/user_images/' . $user->user_profile->gambar))}" class="w-full h-full object-cover">`);
                }

                $("#toggle-password").click(function () {
                    $("#exampleInputPassword1").attr('type') === 'password' ? $('#exampleInputPassword1').attr('type', 'text') : $('#exampleInputPassword1').attr('type', 'password');
                })

                $("#upload-btn").click(function () {
                    $("#file-input").click();
                })

                $("#file-input").change(function () {
                    $("#img-preview").removeClass("h-96");
                    $("#img-preview").html('<img src="' + URL.createObjectURL(event.target.files[0]) + '" class="w-full h-full object-cover">');
                })

                $("#delete-image").click(function () {
                    fetch('/settings-process', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            _token: "{{ csrf_token() }}",
                            delete_image: true
                        })
                    }).then(res => {
                        if (res.status === 200) {
                            window.location.reload();
                        }
                    })
                    .catch(err => console.log(err))
                })
            })
        </script>
    @endif
@endsection
