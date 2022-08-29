<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
     * Returns an array of the start and end of today.
     *
     * @return array
     */
    public function nowStartAndEndUtc(): array
    {
        return [
            Carbon::parse(now()->startOfDay(), 'Pacific/Honolulu')->setTimezone('UTC'),
            Carbon::parse(now()->endOfDay(), 'Pacific/Honolulu')->setTimezone('UTC')
        ];
    }

    /**
     * @return HasMany
     */
    public function consumedFoods(): HasMany
    {
        return $this->hasMany(ConsumedFood::class);
    }
}
