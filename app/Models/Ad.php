<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = ['name', 'position', 'type', 'code', 'adsense_slot_ids', 'is_active'];

    protected $casts = [
        'adsense_slot_ids' => 'array',
        'is_active' => 'boolean'
    ];
}
