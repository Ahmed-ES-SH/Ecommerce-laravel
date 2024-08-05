<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_name',
        'store_name',
        'vendor_email',
        'vendor_phone',
        'storeurl',
        'adress',
        'storedescription',
        'category',
        'logo',
    ];

    protected $casts = [
        'logo' => 'string'
    ] ;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function orders()
    {
        return $this->hasMany(order::class);
    }
}
