<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\Phone;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const AVATAR_MALE = '/images/avatar-male.svg';

    public const AVATAR_FEMALE = '/images/avatar-female.svg';

    public const AVATARS = [
        self::AVATAR_MALE,
        self::AVATAR_FEMALE,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'google_id',
        'avatar',
        'phone',
        'phone_normalized',
        'address',
        'is_admin',
        'is_super_admin',
    ];

    public function wishlists()
    {
        return $this->belongsToMany(Product::class, 'wishlists');
    }

    public function getAvatarAttribute($value): string
    {
        if (in_array($value, self::AVATARS, true)) {
            return $value;
        }

        return $this->defaultAvatar();
    }

    public function defaultAvatar(): string
    {
        return ((int) $this->getKey() % 2 === 0) ? self::AVATAR_FEMALE : self::AVATAR_MALE;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value;

        if (Schema::hasColumn('users', 'phone_normalized')) {
            $this->attributes['phone_normalized'] = Phone::normalize($value);
        }
    }
}
