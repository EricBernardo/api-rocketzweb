<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ClientScope implements Scope
{

    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function apply(Builder $builder, Model $model)
    {

        $role = $this->user->roles()->first()->name;

        if ($role != 'root') {

            $builder->where('company_id', '=', $this->user->company_id);

        }

    }
}
