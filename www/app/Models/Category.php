<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    //
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
