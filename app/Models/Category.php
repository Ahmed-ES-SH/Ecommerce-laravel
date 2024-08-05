<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
    ];


    protected $casts = [
        'image' => 'string', // إضافة هذا السطر
    ];


    public function orders()
    {
        return $this->hasMany(order::class); // تعريف علاقة hasMany مع نموذج Product
    }
}
