<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Exception;

class KategoriController extends Controller
{
    public function index(): View
    {
        return view('pages.produkdaninventori.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Kategori::create([
                'nama_kategori' => $request->nama_kategori
            ]);
            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating kategori: ' . $e->getMessage())->withInput();
        }
    }
}
