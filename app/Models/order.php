<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_email",
        "adress",
        "order_status",
        "order",
        "user_id",
        "vendor_id",
        'category_id',
    ];


    protected $casts = [
        'order' => 'array'
    ];



    public function order()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function Vendor()
    {
        return $this->belongsTo(vendor::class);
    }
}
