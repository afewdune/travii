<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'chance_common',
        'chance_rare',
        'chance_sr',
        'chance_ssr',
        'chance_nft',
        'chance_special',
        'image',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'rod_id');
    }

    public function purchases()
    {
        return $this->hasMany(RodPurchase::class);
    }
}
