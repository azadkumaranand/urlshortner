<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    protected $fillable = ['original_url', 'short_url', 'user_id', 'client_id'];

    public function client(){
        return $this->belongsTo(User::class, 'client_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
        }
}
