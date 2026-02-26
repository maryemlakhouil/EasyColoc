<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
     protected $fillable = ['colocation_id', 'invited_by', 'invited_email', 'token', 'status', 'expires_at'];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
     public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}


