<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    // Pastikan nama tabel eksplisit 'foods' agar konsisten dengan migration
    protected $table = 'foods';

    // Izinkan mass-assignment untuk semua kolom kecuali id
    protected $guarded = ['id'];

    // Relasi: Satu menu makanan bisa ada di banyak detail pesanan
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'food_id');
    }
}
