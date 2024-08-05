<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $vendorsids = DB::table('vendors')->pluck('id')->toArray();
        $categoriesids = DB::table('categories')->pluck('id')->toArray();
        $usersids = DB::table('users')->pluck('id')->toArray();
        $products = Product::all()->toArray();

        for ($i = 0; $i < 50; $i++) {
            $randomProducts = [];
            for ($j = 0; $j < 3; $j++) {
                $randomProducts[] = $products[array_rand($products)];
            }
            $product = json_encode($randomProducts);


            DB::table('orders')->insert([
                'user_email' => Str::random(10) . '@fakeemail.com',
                'adress' => Str::random(3) . '-' . Str::random(4),
                'order' => $product,
                'user_id' => $usersids[array_rand($usersids)],
                'vendor_id' => $vendorsids[array_rand($vendorsids)],
                'category_id' => $categoriesids[array_rand($categoriesids)],
            ]);
        }
    }
}
