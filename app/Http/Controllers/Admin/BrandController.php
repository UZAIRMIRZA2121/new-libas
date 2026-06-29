<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $data = $request->except(['logo', 'banner']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brands', 'public');
            $data['logo_path'] = $path;
        }

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('brands/banners', 'public');
            $data['banner_path'] = $path;
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $data = $request->except(['logo', 'banner']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
            }
            $path = $request->file('logo')->store('brands', 'public');
            $data['logo_path'] = $path;
        }

        if ($request->hasFile('banner')) {
            if ($brand->banner_path) {
                Storage::disk('public')->delete($brand->banner_path);
            }
            $path = $request->file('banner')->store('brands/banners', 'public');
            $data['banner_path'] = $path;
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_path);
        }
        if ($brand->banner_path) {
            Storage::disk('public')->delete($brand->banner_path);
        }
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->is_active = !$brand->is_active;
        $brand->save();

        return response()->json([
            'success' => true,
            'is_active' => $brand->is_active,
            'message' => 'Status updated successfully'
        ]);
    }
}
