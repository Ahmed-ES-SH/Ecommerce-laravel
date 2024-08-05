<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appurl = config('app.url');
        $path = 'images/users';
        $fullpath = public_path($path);
        $images = scandir($fullpath);
        $imagesarray = array_filter($images, function ($image) {
            return in_array(pathinfo($image, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });

        $categoriesides = DB::table('categories')->pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            $imageuser = $imagesarray[array_rand($imagesarray)];
            $imageurl = $appurl . '/' . $path . '/' . $imageuser;
            DB::table('vendors')->insert([
                'vendor_name' => Str::random(8) . '-fake',
                'store_name' => Str::random(12) . '-fake',
                'storedescription' => Str::random(20) . '-fake',
                'vendor_email' => Str::random(8) . '@fake.com',
                'vendor_phone' => '01017534536',
                'storeurl' => 'fakeurl@mail.com',
                'adress' => 'fakestreet/fakehousenumber',
                'category' => $categoriesides[array_rand($categoriesides)],
                'logo' => $imageurl //
            ]);
        }
    }
}
