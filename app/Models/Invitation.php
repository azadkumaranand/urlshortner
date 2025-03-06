<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
   protected $fillable = ['user_id', 'invited_by', 'token'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
