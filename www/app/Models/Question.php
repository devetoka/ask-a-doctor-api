<?php

namespace App\Models;

use App\Models\Contracts\UUID;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $incrementing = false;
    protected $guarded = [];
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
