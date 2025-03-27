<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RodPurchase extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'rod_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rod()
    {
        return $this->belongsTo(Rod::class);
    }
}