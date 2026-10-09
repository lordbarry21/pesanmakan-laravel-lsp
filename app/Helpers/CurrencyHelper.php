<?php
namespace App\Helpers;
class CurrencyHelper {
    public static function formatRupiah($val): string { return 'Rp ' . number_format($val, 0, ',', '.'); }
}
