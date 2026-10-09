<?php
namespace App\Enums;
enum OrderStatus: string {
    case PENDING = 'Pending';
    case DIPROSES = 'Diproses';
    case SELESAI = 'Selesai';
    case BATAL = 'Batal';
}
