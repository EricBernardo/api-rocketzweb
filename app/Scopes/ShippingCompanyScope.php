<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ShippingCompanyScope implements Scope
{

    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('company_id', '=', $this->user->company_id);
    }
}
