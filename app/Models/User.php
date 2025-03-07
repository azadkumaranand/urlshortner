<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'client_id',
        'password',
    ];

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
        ];
    }

    public function usersUnderClient(){
        return $this->hasMany(User::class, 'client_id', 'id');
    }

    public function sortUrlByUser(){
        return $this->hasMany(ShortUrl::class, 'user_id', 'id');
    }

    public function ShortUrlGeneretedByClientMembers(){
        return $this->hasMany(ShortUrl::class, 'client_id', 'id');
    }

    public function shortUrlCountByClientMembers()
    {
        return $this->hasMany(ShortUrl::class, 'client_id', 'id')->select('client_id', 'count');
    }
    public function ShortUrlGeneretedByMember(){
        return $this->hasMany(ShortUrl::class, 'user_id', 'id');
    }
    public function shortUrlCountByMember()
    {
        return $this->hasMany(ShortUrl::class, 'user_id', 'id')->select('client_id', 'count');
    }
}
