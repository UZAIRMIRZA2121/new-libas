<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class StoreCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Women' => ['Tops', 'Bottoms', 'Dresses', 'Outerwear', 'Footwear', 'Accessories'],
            'Men' => ['Shirts', 'T-Shirts', 'Trousers', 'Jeans', 'Footwear', 'Accessories'],
            'Kids' => ['Boys Clothing', 'Girls Clothing', 'Baby Clothing', 'Toys', 'Footwear'],
            'Beauty' => ['Makeup', 'Skincare', 'Haircare', 'Fragrances'],
            'Home & Living' => ['Furniture', 'Decor', 'Lighting', 'Bedding'],
            'Health and Wellness' => ['Supplements', 'Personal Care', 'Fitness Equipment'],
            'Electronics' => ['Mobiles', 'Laptops', 'Audio', 'Smart Home'],
            'Home Textile' => ['Towels', 'Curtains', 'Blankets'],
            'Bags & Luggage' => ['Backpacks', 'Suitcases', 'Handbags'],
            'Sports & Outdoor' => ['Activewear', 'Sports Equipment', 'Camping'],
        ];

        foreach ($categories as $parentName => $subcategories) {
            // Create Parent
            $parent = Category::firstOrCreate([
                'slug' => Str::slug($parentName),
            ], [
                'name' => $parentName,
                'is_active' => true,
            ]);

            // Create Subcategories
            foreach ($subcategories as $subName) {
                Category::firstOrCreate([
                    'slug' => Str::slug($subName . ' ' . $parentName),
                ], [
                    'name' => $subName,
                    'parent_id' => $parent->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
