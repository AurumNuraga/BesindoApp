<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Halaman 1: Menampilkan Menu Pilihan (Card)
     */
    public function index()
    {
        // Langsung return view menu (index.blade.php)
        return view('stocks.index');
    }

    /**
     * Halaman 2: Menampilkan Tabel Data Stok
     */
    public function check()
    {
        // Logic pengambilan data pindah ke sini
        $products = Product::with(['stock', 'category', 'brand'])
                    ->get()
                    ->sortBy(function($product) {
                        return $product->stock->stock;
                    });

        return view('stocks.stock', compact('products'));
    }
}