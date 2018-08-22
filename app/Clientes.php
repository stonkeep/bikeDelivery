<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends Model
{
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    use SoftDeletes;

    protected $guarded = [];

    protected $softCascade = ['users', 'enderecos'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function enderecos() {
        return $this->hasMany(Enderecos::class);
    }

    public function pedidos() {
        return $this->hasMany(Pedidos::class);
    }
}
