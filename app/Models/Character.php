<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar',
        'hp',
        'mp',
        'attack',
        'defense',
        'speed',
        'special_attack',
        'special_defense',
        'level',
        'exp',
    ];
}
