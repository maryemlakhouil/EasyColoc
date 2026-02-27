<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = ['name', 'owner_id', 'status'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(\App\Models\User::class, 'colocation_user')
            ->withPivot(['status', 'left_at'])
            ->withTimestamps();
    }

    public function invitations()
    {
        return $this->hasMany(\App\Models\Invitation::class);
    }

    public function categories()
    {
        return $this->hasMany(\App\Models\Category::class);
    }

   public function depences()
    {
        return $this->hasMany(\App\Models\Depence::class);
    }
}



