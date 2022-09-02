<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'timezone',
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
            $this->timezone ? now()->timezone($this->timezone)->startOfDay()->timezone('UTC') : now()->startOfDay(),
            $this->timezone ? now()->timezone($this->timezone)->endOfDay()->timezone('UTC') : now()->endOfDay(),
        ];
    }

    /**
     * Returns an array that includes the current date
     * and thirty days ago.
     *
     * @return array
     */
    public function lastThirtyDaysUtc(): array
    {
        return [
            $this->timezone ? now()->timezone($this->timezone)->subDays(30)->timezone('UTC') : now()->subDays(30),
            $this->timezone ? now()->timezone($this->timezone)->timezone('UTC') : now()
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
