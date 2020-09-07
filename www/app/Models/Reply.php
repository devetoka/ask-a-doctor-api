<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    public $incrementing = false;
    protected $guarded = [];
    //
    public function replyable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->morphMany(Reply::class, 'replyable');
    }
}
