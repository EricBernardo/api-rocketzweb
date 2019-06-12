<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductScope implements Scope
{

    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function apply(Builder $builder, Model $model)
    {

        $builder->whereHas('category');

        if ($role == 'client') {
            $builder->where('client_id', '=', $this->user->client_id);
        }

    }
}
