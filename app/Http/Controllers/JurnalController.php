<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Modal;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Repositories\JurnalRepository;
use Carbon\Carbon;

class JurnalController extends Controller
{
    protected $jurnalRepository;

    public function __construct(JurnalRepository $jurnalRepository)
    {
        $this->jurnalRepository = $jurnalRepository;
    }

    public function index(Request $request)
    {
        $tanggal_mulai = Carbon::parse($request->tanggal_mulai)->format('Y-m-d');
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai)->format('Y-m-d');
        $jurnal = $this->jurnalRepository->getByTanggal($tanggal_mulai, $tanggal_selesai);
        
        $tipe_periode = Jurnal::typePeriode();
        return view('pages.jurnal.index', compact('jurnal','tipe_periode'));
    }

    public function aruskas(Request $request)
    {
        $tanggal_mulai = Carbon::parse($request->tanggal_mulai)->format('Y-m-d');
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai)->format('Y-m-d');
        $aruskas = $this->jurnalRepository->getArusKasByTanggal($tanggal_mulai, $tanggal_selesai);
        $saldo_awal = Modal::leftJoin('jurnal', 'jurnal.no_reff', 'modal.id_modal')
            ->leftJoin('jurnal_detail', 'jurnal_detail.id_jurnal', 'jurnal.id_jurnal')
            ->join('akun', 'jurnal_detail.id_akun', 'akun.id_akun')
            ->where('modal.jns_transaksi', 'Penyetoran Modal')
            ->where('jurnal.code', Modal::CODE_JURNAL)
            ->where('akun.type', 'Kas & Bank')
            ->sum('jurnal_detail.debit');
        
        $tipe_periode = Jurnal::typePeriode();
        return view('pages.aruskas.index', compact('aruskas','tipe_periode','saldo_awal'));
    }

    public function neraca(Request $request)
    {
        $tanggal_mulai = Carbon::parse($request->tanggal_mulai)->format('Y-m-d');
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai)->format('Y-m-d');
        $neraca = $this->jurnalRepository->getNeracaByTanggal($tanggal_mulai, $tanggal_selesai);
        
        $tipe_periode = Jurnal::typePeriode();
        return view('pages.neraca.index', compact('neraca','tipe_periode'));
    }

    public function labarugi(Request $request)
    {
        $tanggal_mulai = Carbon::parse($request->tanggal_mulai)->format('Y-m-d');
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai)->format('Y-m-d');
        $labarugi = $this->jurnalRepository->getLabaRugiByTanggal($tanggal_mulai, $tanggal_selesai);
        
        $tipe_periode = Jurnal::typePeriode();
        return view('pages.labarugi.index', compact('labarugi','tipe_periode'));
    }

    public function detail($id, $code)
    {
        try {
            $detailJurnal = DB::table('jurnal_detail')
                ->join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
                ->join('akun', 'akun.id_akun', '=', 'jurnal_detail.id_akun')
                ->select('jurnal_detail.*', 'akun.*')
                ->where('jurnal.no_reff', $id)
                ->where('jurnal.code',$code)
                ->get();

            return response()->json([
                'status' => 'success',
                'detailJurnal' => $detailJurnal,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail jurnal',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function exportToExcel()
    {
        // // Ambil data kontak dari database
        // $kontak = Kontak::all(['jenis_kontak', 'nama_kontak', 'email', 'no_hp', 'nm_perusahaan', 'alamat']);

        // // Inisialisasi Spreadsheet baru
        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();

        // // Atur Header
        // $sheet->setCellValue('A1', 'No');
        // $sheet->setCellValue('B1', 'Jenis Kontak');
        // $sheet->setCellValue('C1', 'Nama Kontak');
        // $sheet->setCellValue('D1', 'Email');
        // $sheet->setCellValue('E1', 'No HP');
        // $sheet->setCellValue('F1', 'Nama Perusahaan');
        // $sheet->setCellValue('G1', 'Alamat');

        // // Set style header
        // $headerStyle = [
        //     'font' => [
        //         'bold' => true,
        //         'color' => ['rgb' => 'FFFFFF'],
        //         'size' => 12,
        //     ],
        //     'fill' => [
        //         'fillType' => Fill::FILL_SOLID,
        //         'startColor' => ['rgb' => '307487'],
        //     ],
        //     'alignment' => [
        //         'horizontal' => Alignment::HORIZONTAL_CENTER,
        //         'vertical' => Alignment::VERTICAL_CENTER,
        //     ],
        // ];
        // $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // // Isi data
        // $row = 2;
        // $no = 1; // Inisialisasi nomor urut
        // foreach ($kontak as $data) {
        //     $sheet->setCellValue('A' . $row, $no++);
        //     $sheet->setCellValue('C' . $row, $data->nama_kontak);
        //     $sheet->setCellValue('B' . $row, $data->jenis_kontak);
        //     $sheet->setCellValue('D' . $row, $data->email);
        //     $sheet->setCellValue('E' . $row, $data->no_hp);
        //     $sheet->setCellValue('F' . $row, $data->nm_perusahaan);
        //     $sheet->setCellValue('G' . $row, $data->alamat);
        //     $row++;
        // }

        // // Set border untuk semua data
        // $sheet->getStyle('A1:G' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // // Set auto size pada kolom
        // foreach (range('A', 'G') as $columnID) {
        //     $sheet->getColumnDimension($columnID)->setAutoSize(true);
        // }

        // // Buat file Excel
        // $fileName = 'Data_Kontak.xlsx';
        // $writer = new Xlsx($spreadsheet);

        // // Response untuk download file
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="' . $fileName . '"');
        // header('Cache-Control: max-age=0');

        // $writer->save('php://output');
    }

    public function exportToPDF()
    {
        // // Ambil data kontak dari database
        // $kontak = Kontak::all(['jenis_kontak', 'nama_kontak', 'email', 'no_hp', 'nm_perusahaan', 'alamat']);

        // // Load view dengan data kontak
        // $pdf = \PDF::loadView('pages/kontak/kontak_pdf', compact('kontak'));

        // // Set orientasi landscape dan ukuran A4 (opsional)
        // $pdf->setPaper('A4', 'landscape');

        // // Buat file PDF
        // return $pdf->download('Data_Kontak.pdf');
    }
}
