<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'Auther',
        'user_id',
        'body'
    ];
    protected $casts = [];



    public function Comment()
    {
        return $this->belongsTo(User::class);
    }
}
