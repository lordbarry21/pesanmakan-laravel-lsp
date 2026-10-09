<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    // Gunakan guarded id agar kolom kalkulasi 'subtotal' tidak terblokir
    protected $guarded = ['id'];

    // Relasi balik ke menu makanan (Many-to-One)
    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }

    // Relasi balik ke pesanan induk (Many-to-One)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
