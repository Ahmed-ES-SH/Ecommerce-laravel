<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'vendor_id',
        'vendor_name',
        'images',
        'category',
        'stock',
        'sku',
    ];

    protected $casts = [
        'images' => 'array'
    ];




    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
