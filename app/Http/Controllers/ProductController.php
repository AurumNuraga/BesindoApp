<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\BrandCategory;
use App\Models\PackageCategory;
use App\Models\ProductCategory;
use App\Models\Stock;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['supplier', 'category', 'brand', 'package'])->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $categories = ProductCategory::all();
        $brands = BrandCategory::all();
        $packages = PackageCategory::all();
        return view('products.create', compact('suppliers', 'categories', 'brands', 'packages'));
    }

    public function store(Request $request)
    {
    
        $validated = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required',
            'supplier_id' => 'required',
            'status' => 'nullable|string',
            'brand_id' => 'required',
            'package_id' => 'required',
            'barcode' => 'nullable|string',
            'unit_per_product' => 'nullable|integer',
            'unit_per_koli' => 'nullable|integer',
            'capital_price' => 'nullable|numeric',
            'sell_price' => 'nullable|numeric',
            'expedition_price' => 'nullable|numeric',
            'tax' => 'nullable|string',
            'location' => 'nullable|string',
            'color' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'volume' => 'nullable|numeric',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        $categories = ProductCategory::all();
        $brands = BrandCategory::all();
        $packages = PackageCategory::all();
        return view('products.edit', compact('product', 'suppliers', 'categories', 'brands', 'packages'));
    }

    public function update(Request $request, Product $product)
    {

    $validated = $request->validate([
        'name' => 'required|string',
        'category_id' => 'required',
        'supplier_id' => 'required',
        'brand_id' => 'required',
        'package_id' => 'required',
        'status' => 'nullable|string',
        'barcode' => 'nullable|string',
        'unit_per_product' => 'nullable|integer',
        'unit_per_koli' => 'nullable|integer',
        'capital_price' => 'nullable|numeric',
        'sell_price' => 'nullable|numeric',
        'expedition_price' => 'nullable|numeric',
        'tax' => 'nullable|string',
        'location' => 'nullable|string',
        'color' => 'nullable|string',
        'weight' => 'nullable|numeric',
        'length' => 'nullable|numeric',
        'width' => 'nullable|numeric',
        'height' => 'nullable|numeric',
        'volume' => 'nullable|numeric',
    ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus!');
    }
}