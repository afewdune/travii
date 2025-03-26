<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishRecord extends Model
{
    use HasFactory;

    protected $table = 'FishRecord';

    protected $fillable = [
        'FishID',
        'FisherID',
        'FishAdded',
        'FishUpdated',
        'FishDeleted',
    ];

    public $timestamps = false;

    public function fish()
    {
        return $this->belongsTo(Fish::class, 'FishID', 'FishID');
    }
}
