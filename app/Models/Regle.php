<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regle extends Model
{
    protected $fillable = [
        'colocation_id',
        'from_user_id',
        'to_user_id',
        'montant',
        'paid_at',
    ];
    public function colocation()
    { 
        return $this->belongsTo(\App\Models\Colocation::class);
    }
    public function fromUser() 
    { 
        return $this->belongsTo(\App\Models\User::class, 'from_user_id');
    }
    public function toUser() 
    { 
        return $this->belongsTo(\App\Models\User::class, 'to_user_id'); 
    }
}
