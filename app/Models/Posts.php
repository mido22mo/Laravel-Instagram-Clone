<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    protected $fillable = ['image','caption','filter','user_id'];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(){
        return $this->hasMany(Comments::class, 'post_id');
    }

    public function likes(){
        return $this->hasMany(Likes::class, 'liked_id');
    }

}
