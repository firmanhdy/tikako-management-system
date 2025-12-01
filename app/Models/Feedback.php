<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'name',
        'email',
        'rating',
        'message',
    ];

    /**
     * The attributes that should be cast.
     * Ensure rating is always cast to integer when retrieved.
     */
    protected $casts = [
        'rating' => 'integer',
    ];
}