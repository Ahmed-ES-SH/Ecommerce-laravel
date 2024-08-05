<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appurl = config('app.url');
        $path = 'images/users' ;
        $fullpath = public_path($path);
        $images = scandir($fullpath);
        $imagesarray = array_filter($images , function ($image) {
            return in_array(pathinfo($image ,PATHINFO_EXTENSION) ,['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });

        for ($i = 0 ; $i < 50 ; $i++) {
            $imageuser = $imagesarray[array_rand($imagesarray)];
            $imageurl = $appurl . '/' . $path . '/' . $imageuser ;
                DB::table('users')->insert([
                    'email' => Str::random(10).'fake.com' ,
                    'name' => Str::random(10),
                    'image' => $imageurl ,
                    'password' => Hash::make('password')
                ]);
        }
    }
}
