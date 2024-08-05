<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appUrl = config('app.url');  
        $imagesPath = 'images/products';  
        $fullImagesPath = public_path($imagesPath);
        $images = scandir($fullImagesPath);
        $vendorIds = DB::table('vendors')->pluck('id')->toArray();
        $vendorNames = DB::table('vendors')->pluck('vendor_name')->toArray();
        $categoryIds = DB::table('categories')->pluck('title')->toArray();
        
        // Filter to ignore any file not image
        $images = array_filter($images, function ($image) {
            return in_array(pathinfo($image, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });

        // Add the products = {length: 50}
        for ($i = 0; $i < 50; $i++) {
            $randomImage = $images[array_rand($images)];
            $imageUrl = $appUrl . '/' . $imagesPath . '/' . $randomImage;
            $productImages = json_encode([
                $imageUrl,
                $imageUrl,
                $imageUrl,
                $imageUrl
            ]);

            $vendorId = $vendorIds[array_rand($vendorIds)];
            $vendorName = $vendorNames[array_rand($vendorNames)];
            $randCategory = $categoryIds[array_rand($categoryIds)];

            DB::table('products')->insert([
                'title' => 'Product ' . ($i + 1),
                'description' => 'Description should be long for product ' . ($i + 1),
                'images' => $productImages,
                'vendor_id' => $vendorId,
                'vendor_name' => $vendorName,
                'price' => rand(100, 1000),
                'discount' => rand(10, 100),
                'stock' => rand(100, 200),
                'sku' => 'sku for product ' . ($i + 1),
                'category' => $randCategory,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
