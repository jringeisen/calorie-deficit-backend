<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Returns a carbon instance of the start of day.
     *
     * @return Carbon
     */
    public function startOfDayUtc(): Carbon
    {
        return now()->subHours(10);
    }

    /**
     * Returns a carbon instance 24 hours after the start of
     * day utc method.
     *
     * @return Carbon
     */
    public function endOfDayUtc(): Carbon
    {
        return $this->startOfDayUtc()->addHours(24);
    }

    /**
     * @return HasMany
     */
    public function consumedFoods(): HasMany
    {
        return $this->hasMany(ConsumedFood::class);
    }
}
