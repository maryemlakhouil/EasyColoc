<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'colocation_id'];

    public function colocation()
    {
        return $this->belongsTo(\App\Models\Colocation::class);
    }

    public function depenses()
    {
        return $this->hasMany(\App\Models\Depence::class);
    }
}

  