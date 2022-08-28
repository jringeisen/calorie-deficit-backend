<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaloriesBurned extends Model
{
    use HasFactory;

    protected $table = 'calories_burned';

    protected $fillable = [
        'user_id',
        'calories',
    ];
}
