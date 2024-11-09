<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Kontak;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Exception;

// phpspreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;

// PDF
use Barryvdh\DomPDF\Facade\Pdf;

class KontakController extends Controller
{
    public function index(): View
    {
        try {
            $kontak = Kontak::paginate(5);
            $totals = DB::table('kontak')
                ->selectRaw('
                        COUNT(*) AS total_kontak,
                        SUM(CASE WHEN jenis_kontak = "pelanggan" THEN 1 ELSE 0 END) AS total_pelanggan,
                        SUM(CASE WHEN jenis_kontak = "karyawan" THEN 1 ELSE 0 END) AS total_karyawan,
                        SUM(CASE WHEN jenis_kontak = "vendor" THEN 1 ELSE 0 END) AS total_vendor,
                        SUM(CASE WHEN jenis_kontak = "lainnya" THEN 1 ELSE 0 END) AS total_lainnya
                    ')->first();


            return view('pages.kontak.index', [
                'kontak' => $kontak,
                'totals' => $totals
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data kontak.');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_kontak' => 'required|string|max:255',
            'jenis_kontak' => 'required|string',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:15',
            'nm_perusahaan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        try {
            Kontak::create($request->all());
            return redirect()->route('kontak.index')->with('success', 'Kontak berhasil ditambahkan');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan kontak: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Kontak $kontak)
    {
        $request->validate([
            'nama_kontak' => 'required|string|max:255',
            'jenis_kontak' => 'required|string',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:15',
            'nm_perusahaan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        try {
            $kontak->update($request->all());
            return redirect()->route('kontak.index')->with('success', 'Kontak berhasil diupdate');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate kontak: ' . $e->getMessage());
        }
    }

    public function destroy(Kontak $kontak)
    {
        try {
            $kontak->delete();
            return redirect()->route('kontak.index')->with('success', 'Kontak berhasil dihapus');
        } catch (Exception $e) {
            return redirect()->route('kontak.index')->with('error', 'Gagal menghapus kontak: ' . $e->getMessage());
        }
    }

    public function exportKontakToExcel()
    {
        // Ambil data kontak dari database
        $kontak = Kontak::all(['jenis_kontak', 'nama_kontak', 'email', 'no_hp', 'nm_perusahaan', 'alamat']);

        // Inisialisasi Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Atur Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Jenis Kontak');
        $sheet->setCellValue('C1', 'Nama Kontak');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'No HP');
        $sheet->setCellValue('F1', 'Nama Perusahaan');
        $sheet->setCellValue('G1', 'Alamat');

        // Set style header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '307487'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Isi data
        $row = 2;
        $no = 1; // Inisialisasi nomor urut
        foreach ($kontak as $data) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('C' . $row, $data->nama_kontak);
            $sheet->setCellValue('B' . $row, $data->jenis_kontak);
            $sheet->setCellValue('D' . $row, $data->email);
            $sheet->setCellValue('E' . $row, $data->no_hp);
            $sheet->setCellValue('F' . $row, $data->nm_perusahaan);
            $sheet->setCellValue('G' . $row, $data->alamat);
            $row++;
        }

        // Set border untuk semua data
        $sheet->getStyle('A1:G' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Set auto size pada kolom
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Buat file Excel
        $fileName = 'Data_Kontak.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Response untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function exportKontakToPDF()
    {
        // Ambil data kontak dari database
        $kontak = Kontak::all(['jenis_kontak', 'nama_kontak', 'email', 'no_hp', 'nm_perusahaan', 'alamat']);

        // Load view dengan data kontak
        $pdf = \PDF::loadView('pages/kontak/kontak_pdf', compact('kontak'));

        // Set orientasi landscape dan ukuran A4 (opsional)
        $pdf->setPaper('A4', 'landscape');

        // Buat file PDF
        return $pdf->download('Data_Kontak.pdf');
    }
}
