<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaloriesBurned extends Model
{
    use HasFactory;

    protected $table = 'calories_burned';

    protected $fillable = [
        'user_id',
        'calories',
    ];
}
