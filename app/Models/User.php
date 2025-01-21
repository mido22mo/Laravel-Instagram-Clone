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
        'name',
        'email',
        'password',
        'image',
        'bio',
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

    public function posts(){
        return $this->hasMany(Posts::class , 'user_id');
    }

    public function comments(){
        return $this->hasMany(Comments::class, 'user_id' );
    }

    public function followings(){
        return $this->hasMany(Follows::class, 'follower_id');
    }

    public function followers(){
        return $this->hasMany(Follows::class, 'followed_id');
    }

    public function likes()
{
    return $this->hasMany(Likes::class, 'liker_id');
}


public function receivedMessages() 
{
    return $this->hasMany(Message::class, 'recipient_id');
}

public function sentMessages()
{
    return $this->hasMany(Message::class, 'sender_id');
}


}
