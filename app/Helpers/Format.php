<?php

if (!function_exists('parseRupiahToNumber')) {
    function parseRupiahToNumber($rupiah)
    {
        // Hapus karakter selain angka dan koma/titik, serta awalan "Rp" jika ada
        $cleaned = str_replace(['Rp', '.', ' '], '', $rupiah); // Hapus "Rp", titik pemisah ribuan, dan spasi
        $cleaned = str_replace(',', '.', $cleaned); // Ganti koma menjadi titik untuk memastikan desimal benar

        return floatval($cleaned) ?: 0;
    }
}
?>
