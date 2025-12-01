<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'nama_menu',
        'harga',
        'kategori',
        'deskripsi',
        'is_tersedia',
        'is_rekomendasi',
        'foto',
    ];

    /**
     * The attributes that should be cast.
     * Convert database data (0/1) to native PHP types (boolean/integer).
     */
    protected $casts = [
        'harga' => 'integer',
        'is_tersedia' => 'boolean',
        'is_rekomendasi' => 'boolean',
    ];
}