<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depence extends Model
{
    protected $fillable = ['title','amount','date','payer_id','colocation_id','category_id'];

    public function colocation() 
    { 
        return $this->belongsTo(\App\Models\Colocation::class); 
    }
    public function category()
    { 
        return $this->belongsTo(\App\Models\Category::class); 
    }
    public function payer()
    { 
        return $this->belongsTo(\App\Models\User::class, 'payer_id'); 
    }
}
