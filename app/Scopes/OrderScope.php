<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrderScope implements Scope
{

    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function apply(Builder $builder, Model $model)
    {

        $builder->whereHas('client');

        $role = $this->user->roles()->first()->name;

        if ($role == 'client') {
            $builder->where('client_id', '=', $this->user->client_id);
        }

    }
}
