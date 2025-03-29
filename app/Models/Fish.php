<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fish extends Model
{
    use HasFactory;

    protected $primaryKey = 'FishID'; 

    protected $fillable = [
        'FishID', 
        'FishName', 
        'FishImage', 
        'FishRarity', 
        'FishWorth', 
        'FishTokenWorth'
    ];
}