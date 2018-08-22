<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedidos extends Pivot
{
    use SoftDeletes;

    protected $guarded = [];

    public function ciclista() {
        return $this->belongsTo(Ciclista::class);
    }

    public function cliente() {
        return $this->belongsTo(Clientes::class);
    }
}
