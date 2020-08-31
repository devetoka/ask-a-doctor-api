<?php


namespace App\Models\Contracts;


trait UUID
{
    protected static function boot()
    {

        parent::boot();
        self::creating(function ($model) {
            $model->id = preg_replace('/\./', '', uniqid('usr', true));
        });
    }
}