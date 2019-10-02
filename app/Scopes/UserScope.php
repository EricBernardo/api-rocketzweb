<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UserScope implements Scope
{

    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function apply(Builder $builder, Model $model)
    {

        if($this->user && $this->user->roles()->first()->name != 'root') {
            $builder->where('users.company_id', '=', $this->user->company_id);
            $builder->whereHas('companies');
        }

    }
}
