<?php

namespace App\Http\Controllers;

use App\Models\PackageCategory;
use Illuminate\Http\Request;

class PackageCategoryController extends Controller
{
    public function index()
    {
        $packages = PackageCategory::latest()->paginate(10);
        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        PackageCategory::create($request->all());
        return redirect()->route('packages.index')->with('success', 'Kemasan berhasil ditambahkan');
    }

    public function edit(PackageCategory $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, PackageCategory $package)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $package->update($request->all());
        return redirect()->route('packages.index')->with('success', 'Kemasan berhasil diupdate');
    }

    public function destroy(PackageCategory $package)
    {
        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Kemasan berhasil dihapus');
    }
}