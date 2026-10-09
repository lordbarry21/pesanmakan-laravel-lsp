<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan katalog menu publik untuk customer.
     */
    public function index()
    {
        $foods = Food::all();
        return view('customer.index', compact('foods'));
    }

    /**
     * Menyimpan pesanan customer menggunakan database transaction atomik.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_number'  => 'required|string|max:50',
            'items'         => 'required|array',
            'items.*'       => 'nullable|integer|min:0',
        ]);

        // Filter hanya menu dengan kuantitas lebih dari 0
        $orderedItems = array_filter($request->items, fn ($qty) => (int)$qty > 0);

        if (empty($orderedItems)) {
            return back()->with('error', 'Pilih minimal satu menu makanan sebelum memesan!');
        }

        try {
            $order = DB::transaction(function () use ($request, $orderedItems) {
                // 1. Buat pesanan induk
                $order = Order::create([
                    'customer_name' => $request->customer_name,
                    'table_number'  => $request->table_number,
                    'total_price'   => 0,
                    'status'        => 'Pending',
                ]);

                $grandTotal = 0;

                // 2. Simpan setiap rincian item transaksi
                foreach ($orderedItems as $foodId => $quantity) {
                    $food = Food::findOrFail($foodId);
                    $subtotal = $food->price * $quantity;
                    $grandTotal += $subtotal;

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'food_id'  => $food->id,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ]);
                }

                // 3. Update total akhir transaksi
                $order->update(['total_price' => $grandTotal]);

                return $order;
            });

            return redirect()->route('customer.index')->with(
                'success', 
                "Pesanan #{$order->id} berhasil dibuat untuk Meja {$order->table_number}!"
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard rekapitulasi pesanan untuk admin dengan Eager Loading anti N+1.
     */
    public function adminDashboard()
    {
        $orders = Order::with('orderDetails.food')->latest()->get();
        return view('dashboard', compact('orders'));
    }

    /**
     * Memperbarui status pesanan dari dropdown admin dashboard.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        
        // Standarisasi kapitalisasi status ('Pending', 'Diproses', 'Selesai', 'Batal')
        $normalizedStatus = ucfirst(strtolower($request->status));
        $order->update(['status' => $normalizedStatus]);

        return back()->with('success', "Status pesanan #{$order->id} berhasil diubah menjadi {$normalizedStatus}!");
    }
}
