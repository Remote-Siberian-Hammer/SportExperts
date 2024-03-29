<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'auth_platform',

        'vk_id',
        'vk_sid_token',
        'vk_sig_token',

        'google_id',
        'google_access_token',

        'first_name',
        'first_name_eng',
        'last_name',
        'last_name_eng',
        'birth_date',
        'gender',
        'email',
        'is_admin',
        'password',
    ];

    static function createBearerTocken($user)
    {
        $user->tokens()->delete();
        return $user->createToken('user_token')->plainTextToken;
    }

    static function deleteBearerTocken($user)
    {
        return $user->tokens()->delete();
    }
}
