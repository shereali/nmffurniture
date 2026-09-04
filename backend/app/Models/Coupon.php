<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'max_discount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Calculate discount amount for a given subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_spend) {
            return 0.00;
        }

        if ($this->type === 'percentage') {
            $discount = ($subtotal * ($this->value / 100));
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            return round($discount, 2);
        }

        return min($this->value, $subtotal);
    }
}
