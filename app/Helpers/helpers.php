<?php

if (! function_exists('formatRupiah')) {
    /**
     * Format number or string to Rupiah format.
     *
     * @param string|int|float $value
     * @return string
     */
    function formatRupiah($value)
    {
        // Jika nilai adalah string, pastikan mengonversi ke angka yang valid
        if (is_string($value)) {
            // Menghapus titik ribuan (jika ada) dan memastikan nilai menjadi angka yang valid
            $value = (float) str_replace(',', '', $value);
        }

        // Memastikan bahwa harga yang diterima adalah angka yang valid
        if (!is_numeric($value) || $value < 0) {
            return 'Rp 0';
        }

        // Format angka menjadi format Rupiah
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
