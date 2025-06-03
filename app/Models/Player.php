<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = 'players';

    protected $fillable = [
        'uuid',
        'username',
    ];

    public function stats()
    {
        return $this->hasMany(Stat::class);
    }
}
