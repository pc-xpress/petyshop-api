<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'content',
        'image',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
