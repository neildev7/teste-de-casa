<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
        'gold',     // <-- ADICIONADO
        'potions',  // <-- ADICIONADO
    ];
}