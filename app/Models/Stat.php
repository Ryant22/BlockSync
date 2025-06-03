<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $table = 'stats';

    protected $fillable = [
        'uuid',
        'category',
        'key',
        'value',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
