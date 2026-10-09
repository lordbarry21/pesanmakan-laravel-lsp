<?php
namespace App\Services;
class ReceiptService {
    public static function generateReceipt($order): array { return ['id' => $order->id]; }
}
