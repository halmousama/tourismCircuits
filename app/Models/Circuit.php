<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circuit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'coordinates',
    ];

    // Define any relationships if necessary
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}