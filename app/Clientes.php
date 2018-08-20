<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends Model
{
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    use SoftDeletes;

    protected $guarded = [];

    protected $softCascade = ['users'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
