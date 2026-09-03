<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'branch',
        'address',
        'city',
        'state',
        'postal_code',
        'phone',
        'whatsapp_number',
        'whatsapp_prefill_text',
        'map_embed_url',
        'opening_hours',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
