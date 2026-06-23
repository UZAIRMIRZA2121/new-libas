<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PageController extends Controller
{
    public function home()
    {
        $categories = \App\Models\Category::where('is_active', true)->whereNull('parent_id')->get();
        $banners = \App\Models\Banner::where('is_active', true)->get();
        $brands = \App\Models\Brand::where('is_active', true)->get();
        $featuredProducts = \App\Models\Product::with(['category'])->where('is_active', true)->where('is_featured', true)->latest()->take(8)->get();
        $recommendedProducts = \App\Models\Product::where('is_active', true)->inRandomOrder()->take(8)->get();
        return view('home', compact('categories', 'banners', 'brands', 'featuredProducts', 'recommendedProducts'));
    }

    public function product($slug)
    {
        $product = \App\Models\Product::with(['images', 'colors'])->where('slug', $slug)->firstOrFail();
        
        $recommendedProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        if ($recommendedProducts->count() < 4) {
            $moreProducts = \App\Models\Product::where('is_active', true)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $recommendedProducts->pluck('id'))
                ->inRandomOrder()
                ->take(4 - $recommendedProducts->count())
                ->get();
            $recommendedProducts = $recommendedProducts->merge($moreProducts);
        }

        return view('product', compact('product', 'recommendedProducts'));
    }

    public function category($cat_id = null)
    {
        // Fetch main categories
        $categories = \App\Models\Category::where('is_active', true)->whereNull('parent_id')->get();
        
        $subCategories = collect();
        $productsQuery = \App\Models\Product::with(['category'])->where('is_active', true);
        
        $currentParentId = null;

        if ($cat_id) {
            $currentCategory = \App\Models\Category::find($cat_id);
            
            if ($currentCategory) {
                if (is_null($currentCategory->parent_id)) {
                    $currentParentId = $currentCategory->id;
                    // It's a parent category, get its children for subcategories
                    $subCategories = \App\Models\Category::where('parent_id', $currentCategory->id)->where('is_active', true)->get();
                    
                    // Get products from this category and all its subcategories
                    $categoryIds = $subCategories->pluck('id')->push($currentCategory->id);
                    $productsQuery->whereIn('category_id', $categoryIds);
                } else {
                    $currentParentId = $currentCategory->parent_id;
                    // It's a subcategory, get sibling subcategories
                    $subCategories = \App\Models\Category::where('parent_id', $currentCategory->parent_id)->where('is_active', true)->get();
                    
                    // Get products only for this subcategory
                    $productsQuery->where('category_id', $currentCategory->id);
                }
            }
        }

        // You can change get() to paginate(12) if you prefer pagination
        $products = $productsQuery->latest()->get();

        return view('category', compact('cat_id', 'categories', 'subCategories', 'products', 'currentParentId'));
    }

    public function brand($brand_id = null)
    {
        $brands = \App\Models\Brand::where('is_active', true)->get();
        $currentBrand = null;
        $productsQuery = \App\Models\Product::with(['category', 'brand'])->where('is_active', true);

        if ($brand_id) {
            $currentBrand = \App\Models\Brand::find($brand_id);
            if ($currentBrand) {
                $productsQuery->where('brand_id', $currentBrand->id);
            }
        }

        $products = $productsQuery->latest()->get();

        return view('brand', compact('brand_id', 'brands', 'currentBrand', 'products'));
    }

    // Removed duplicate product method

    public function cart()
    {
        $cart = session()->get('cart', []);
        
        $recommendedProducts = collect();
        if (count($cart) > 0) {
            $productIdsInCart = collect($cart)->pluck('id')->toArray();
            
            $categoryIds = \App\Models\Product::whereIn('id', $productIdsInCart)
                                ->pluck('category_id')
                                ->filter()
                                ->unique()
                                ->toArray();
            
            if (count($categoryIds) > 0) {
                $recommendedProducts = \App\Models\Product::whereIn('category_id', $categoryIds)
                                    ->whereNotIn('id', $productIdsInCart)
                                    ->where('is_active', true)
                                    ->inRandomOrder()
                                    ->take(4)
                                    ->get();
            }
        }

        if ($recommendedProducts->isEmpty()) {
            $recommendedProducts = \App\Models\Product::where('is_active', true)
                                    ->where('is_featured', true)
                                    ->inRandomOrder()
                                    ->take(4)
                                    ->get();
        }

        return view('cart', compact('recommendedProducts'));
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        $error = null;

        if ($request->has('order_number')) {
            $order = Order::where('order_number', $request->order_number)->with('items')->first();
            if (!$order) {
                $error = 'Order not found. Please check your order number and try again.';
            }
        }

        return view('track-order', compact('order', 'error'));
    }
}
