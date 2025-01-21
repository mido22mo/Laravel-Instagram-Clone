<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    use HasFactory;

    protected $fillable = ['liker_id', 'liked_id'];


    public function user(){
        return $this->belongsTo(User::class, 'liker_id');
    }

    public function post(){
        return $this->belongsTo(Posts::class, 'liked_id');
    }
}
