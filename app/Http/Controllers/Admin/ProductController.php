<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'bought_count' => 'nullable|integer|min:0',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name) . '-' . uniqid();
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');
        $data['rating'] = $request->rating ?? 0;
        $data['review_count'] = $request->review_count ?? 0;
        $data['bought_count'] = $request->bought_count ?? 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['main_image_path'] = $path;
        }

        $product = Product::create($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        // Handle Colors
        if ($request->has('color_names') && $request->has('color_hexes')) {
            $names = $request->color_names;
            $hexes = $request->color_hexes;
            foreach ($names as $index => $name) {
                if (!empty($name) && !empty($hexes[$index])) {
                    $product->colors()->create([
                        'name' => $name,
                        'hex_code' => $hexes[$index]
                    ]);
                }
            }
        }

        // Handle Specifications
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $keys = $request->spec_keys;
            $values = $request->spec_values;
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $product->specifications()->create([
                        'key' => $key,
                        'value' => $values[$index]
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'bought_count' => 'nullable|integer|min:0',
        ]);

        $data = $request->except('image');
        
        if ($request->name !== $product->name) {
            $data['slug'] = Str::slug($request->name) . '-' . uniqid();
        }

        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');
        $data['rating'] = $request->rating ?? 0;
        $data['review_count'] = $request->review_count ?? 0;
        $data['bought_count'] = $request->bought_count ?? 0;

        if ($request->hasFile('image')) {
            if ($product->main_image_path) {
                Storage::disk('public')->delete($product->main_image_path);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['main_image_path'] = $path;
        }

        $product->update($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        // Handle Colors
        if ($request->has('color_names') && $request->has('color_hexes')) {
            // Delete old colors if replacing
            $product->colors()->delete();
            
            $names = $request->color_names;
            $hexes = $request->color_hexes;
            foreach ($names as $index => $name) {
                if (!empty($name) && !empty($hexes[$index])) {
                    $product->colors()->create([
                        'name' => $name,
                        'hex_code' => $hexes[$index]
                    ]);
                }
            }
        }

        // Handle Specifications
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            // Delete old specifications if replacing
            $product->specifications()->delete();
            
            $keys = $request->spec_keys;
            $values = $request->spec_values;
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $product->specifications()->create([
                        'key' => $key,
                        'value' => $values[$index]
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image_path) {
            Storage::disk('public')->delete($product->main_image_path);
        }
        
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
