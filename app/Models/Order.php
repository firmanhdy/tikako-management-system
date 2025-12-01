<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'nomor_meja',
        'note',
    ];

    /**
     * The attributes that should be cast.
     * Ensure total_price is always cast to integer to avoid calculation errors.
     */
    protected $casts = [
        'total_price' => 'integer',
    ];

    /**
     * Relationship: An order belongs to one User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: An order has many Order Details (Items).
     */
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}