<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Akun;
use Carbon\CarbonPeriod;
use Illuminate\View\View;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        if ($toDate->lt($fromDate)) {
            return redirect()->back()->with('failed', 'Tanggal sampai harus lebih besar atau sama dengan tanggal dari.');
        }

        if ($fromDate->diffInMonths($toDate) > 12) {
            return redirect()->back()->with('failed', 'Rentang tanggal maksimal hanya 1 tahun.');
        }

        $data['total_pendapatan'] = DB::table('akun')
            ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
            ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('jurnal.tanggal', [$from, $to]);
            }, function ($query) {
                return $query->whereYear('jurnal.tanggal', date('Y'));
            })
            ->where('akun.kategori_akun', 'Pendapatan')
            ->where('akun.nama_akun', '!=', 'Diskon Penjualan')
            ->sum('jurnal_detail.debit');

        $data['total_pengeluaran'] = DB::table('akun')
            ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
            ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('jurnal.tanggal', [$from, $to]);
            }, function ($query) {
                return $query->whereYear('jurnal.tanggal', date('Y'));
            })
            ->where('akun.kategori_akun', 'Beban')
            ->sum('jurnal_detail.kredit');

        $data['total_keuntungan'] = $data['total_pendapatan'] - $data['total_pengeluaran'];

        // Total saldo akhir / total semua saldo akhir * 100%
        $chart['uang_masuk'] = DB::table('akun')
            ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
            ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('jurnal.tanggal', [$from, $to . ' 23:59:59']);
            }, function ($query) {
                return $query->whereYear('jurnal.tanggal', date('Y'));
            })
            ->where('akun.type', 'Kas & Bank')
            ->sum('jurnal_detail.debit');

        $chart['uang_keluar'] = DB::table('akun')
            ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
            ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('jurnal.tanggal', [$from, $to . ' 23:59:59']);
            }, function ($query) {
                return $query->whereYear('jurnal.tanggal', date('Y'));
            })
            ->where('akun.type', 'Kas & Bank')
            ->sum('jurnal_detail.kredit');

        $period = [];
        $months = range(1, 12);

        if ($from && $to) {
            $getPeriod = CarbonPeriod::create($from, $to);

            foreach ($getPeriod as $date) {
                array_push($period, $date->format('d-m-Y'));
            }

            $getDataAvgPenjualan = DB::table('akun')
                ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
                ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                ->whereBetween('jurnal.tanggal', [$from, $to])
                ->select(
                    DB::raw('SUM(jurnal_detail.debit) as jml_penjualan'),
                    DB::raw('DATE(jurnal.tanggal) as hari'),
                )
                ->groupBy('hari')
                ->pluck('jml_penjualan', 'hari') // Ambil sebagai key-value (bulan => total_hutang)
                ->toArray();

            $chart['jml_penjualan'] = array_map(function ($period) use ($getDataAvgPenjualan) {
                return $getDataAvgPenjualan[date('Y-m-d', strtotime($period))] ?? 0;
            }, $period);
        } else {
            $getDataAvgPenjualan = DB::table('akun')
                ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
                ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                ->whereYear('jurnal.tanggal', date('Y'))
                ->select(
                    DB::raw('SUM(jurnal_detail.debit) as jml_penjualan'),
                    DB::raw('MONTH(jurnal.tanggal) as bulan'),
                )
                ->groupBy('bulan')
                ->pluck('jml_penjualan', 'bulan') // Ambil sebagai key-value (bulan => total_hutang)
                ->toArray();

            $chart['jml_penjualan'] = array_map(function ($month) use ($getDataAvgPenjualan) {
                return $getDataAvgPenjualan[$month] ?? 0;
            }, $months);
        }

        return view('pages.dashboard.index', compact('data', 'chart', 'period'));
    }

    public function pendapatan(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        if ($toDate->lt($fromDate)) {
            return redirect()->back()->with('failed', 'Tanggal sampai harus lebih besar atau sama dengan tanggal dari.');
        }

        if ($fromDate->diffInMonths($toDate) > 12) {
            return redirect()->back()->with('failed', 'Rentang tanggal maksimal hanya 1 tahun.');
        }

        $total_penjualan = DB::table('penjualan')
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from, $to . ' 23:59:59']);
            }, function ($query) {
                return $query->whereYear('created_at', date('Y'));
            })
            ->sum('total_pemasukan');

        $total_pesanan = DB::table('produk_penjualan')
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from, $to . ' 23:59:59']);
            }, function ($query) {
                return $query->whereYear('created_at', date('Y'));
            })
            ->sum('kuantitas');

        $total_pelanggan = DB::table('kontak')
            ->where('jenis_kontak', 'Pelanggan')
            ->count();

        $produk_terlaris = DB::table('penjualan')
            ->join('kontak', 'kontak.id_kontak', '=', 'penjualan.id_kontak')
            ->join('produk_penjualan', 'produk_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('produk', 'produk.id_produk', '=', 'produk_penjualan.id_produk')
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('penjualan.created_at', [$from, $to . ' 23:59:59']);
            }, function ($query) {
                return $query->whereYear('penjualan.created_at', date('Y'));
            })
            ->select(
                'produk.nama_produk',
                'produk_penjualan.kuantitas',
                DB::raw('produk_penjualan.kuantitas * produk.harga_jual as total_penjualan'),
            )
            ->orderBy('total_penjualan', 'desc')
            ->paginate(5);

        $period = [];
        $months = range(1, 12);

        if ($from && $to) {
            $getPeriod = CarbonPeriod::create($from, $to);

            foreach ($getPeriod as $date) {
                array_push($period, $date->format('d-m-Y'));
            }

            $getPendapatanLain = DB::table('akun')
                ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
                ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                ->whereBetween('jurnal.tanggal', [$from, $to])
                ->select(
                    DB::raw('SUM(jurnal_detail.debit) as jml_penjualan'),
                    DB::raw('DATE(jurnal.tanggal) as hari'),
                )
                ->where('akun.kategori_akun', 'Pendapatan')
                ->where('akun.nama_akun', '!=', 'Pendapatan Jasa/barang')
                ->where('akun.nama_akun', '!=', 'Diskon Penjualan')
                ->groupBy('hari')
                ->pluck('total_penjualan', 'hari') // Ambil sebagai key-value (bulan => total_hutang)
                ->toArray();

            $chart['pendapatan_lain'] = array_map(function ($period) use ($getPendapatanLain) {
                return $getPendapatanLain[date('Y-m-d', strtotime($period))] ?? 0;
            }, $period);
        } else {
            $getPendapatanLain = DB::table('akun')
                ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
                ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                ->whereYear('jurnal.tanggal', date('Y'))
                ->select(
                    DB::raw('SUM(jurnal_detail.debit) as jml_penjualan'),
                    DB::raw('MONTH(jurnal.tanggal) as bulan'),
                )
                ->where('akun.kategori_akun', 'Pendapatan')
                ->where('akun.nama_akun', '!=', 'Pendapatan Jasa/barang')
                ->where('akun.nama_akun', '!=', 'Diskon Penjualan')
                ->groupBy('bulan')
                ->pluck('total_penjualan', 'bulan') // Ambil sebagai key-value (bulan => total_hutang)
                ->toArray();

            $chart['pendapatan_lain'] = array_map(function ($month) use ($getPendapatanLain) {
                return $getPendapatanLain[$month] ?? 0;
            }, $months);
        }

        return view('pages.dashboard.pendapatan', [
            'total_penjualan' => $total_penjualan,
            'total_pesanan' => $total_pesanan,
            'total_pelanggan' => $total_pelanggan,
            'produk_terlaris' => $produk_terlaris,
            'chart' => $chart,
            'period' => $period
        ]);
    }

    public function pengeluaran(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        if ($toDate->lt($fromDate)) {
            return redirect()->back()->with('failed', 'Tanggal sampai harus lebih besar atau sama dengan tanggal dari.');
        }

        if ($fromDate->diffInMonths($toDate) > 12) {
            return redirect()->back()->with('failed', 'Rentang tanggal maksimal hanya 1 tahun.');
        }

        $period = [];
        $months = range(1, 12);

        // Chart data handle
        if ($from && $to) {
            $getPeriod = CarbonPeriod::create($from, $to);

            foreach ($getPeriod as $date) {
                array_push($period, $date->format('d-m-Y'));
            }

            $getChartPengeluaran = DB::table('akun')
                ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
                ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                ->whereBetween('jurnal.tanggal', [$from, $to])
                ->select(
                    DB::raw('SUM(jurnal_detail.kredit) as total_pengeluaran'),
                    DB::raw('DATE(jurnal.tanggal) as hari'),
                )
                ->where('akun.kategori_akun', 'Beban')
                ->groupBy('hari')
                ->pluck('total_pengeluaran', 'hari') // Ambil sebagai key-value (bulan => total_hutang)
                ->toArray();

            $chart['pengeluaran'] = array_map(function ($period) use ($getChartPengeluaran) {
                return $getChartPengeluaran[date('Y-m-d', strtotime($period))] ?? 0;
            }, $period);
        } else {
            $getChartPengeluaran = DB::table('akun')
                ->join('jurnal_detail', 'jurnal_detail.id_akun', '=', 'akun.id_akun')
                ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                ->whereYear('jurnal.tanggal', date('Y'))
                ->select(
                    DB::raw('SUM(jurnal_detail.kredit) as total_pengeluaran'),
                    DB::raw('MONTH(jurnal.tanggal) as bulan'),
                )
                ->where('akun.kategori_akun', 'bulan')
                ->groupBy('bulan')
                ->pluck('total_pengeluaran', 'bulan') // Ambil sebagai key-value (bulan => total_hutang)
                ->toArray();

            $chart['pengeluaran'] = array_map(function ($month) use ($getChartPengeluaran) {
                return $getChartPengeluaran[$month] ?? 0;
            }, $months);
        }

        $total_pengeluaran = Pengeluaran::when($from, function ($query) use ($from, $to) {
            return $query->whereBetween('tanggal', [$from, $to]);
        }, function ($query) {
            return $query->whereYear('tanggal', date('Y'));
        })->sum('biaya');

        $pengeluaran = Pengeluaran::join('kontak', 'pengeluaran.id_kontak', '=', 'kontak.id_kontak')
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('pengeluaran.created_at', [$from, $to . ' 23:59:59']);
            }, function ($query) {
                return $query->whereYear('pengeluaran.created_at', date('Y'));
            })
            ->select(
                'pengeluaran.nm_pengeluaran',
                'pengeluaran.biaya',
                DB::raw('FORMAT((pengeluaran.biaya / ' . $total_pengeluaran . ') * 100, 2) as presentase')
            )
            ->paginate(5);

        return view('pages.dashboard.pengeluaran', [
            'pengeluaran' => $pengeluaran,
            'chart' => $chart,
            'total_pengeluaran' => $total_pengeluaran,
            'period' => $period
        ]);
    }
}
