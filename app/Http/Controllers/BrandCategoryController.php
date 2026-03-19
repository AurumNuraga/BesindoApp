<?php

namespace App\Http\Controllers;

use App\Models\BrandCategory;
use Illuminate\Http\Request;

class BrandCategoryController extends Controller
{
    public function index()
    {
        $brands = BrandCategory::latest()->paginate(10);
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        BrandCategory::create($request->all());
        return redirect()->route('brands.index')->with('success', 'Brand berhasil ditambahkan');
    }

    public function edit(BrandCategory $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, BrandCategory $brand)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $brand->update($request->all());
        return redirect()->route('brands.index')->with('success', 'Brand berhasil diupdate');
    }

    public function destroy(BrandCategory $brand)
    {
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Brand berhasil dihapus');
    }
}