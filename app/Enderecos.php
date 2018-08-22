<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enderecos extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function cliente() {
        return $this->belongsTo(Clientes::class);
    }

}
