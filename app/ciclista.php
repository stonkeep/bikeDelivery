<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ciclista extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
