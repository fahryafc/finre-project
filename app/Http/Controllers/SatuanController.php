<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class SatuanController extends Controller
{
    public function store(Request $request)
    {
       try {
        $request->validate([
            'nama_satuan' => 'required|string|max:255|unique:satuan,nama_satuan,except,id_satuan',
        ]);

        $satuan = new Satuan;
        $satuan->nama_satuan = $request->nama_satuan;
        $satuan->save();
        Alert::success('Data Added!', 'Data Created Successfully');
        return redirect()->back();
       } catch (Exception $e) {
        Log::error('Error creating satuan: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong while creating satuan')->withInput();
       }
    }
}
