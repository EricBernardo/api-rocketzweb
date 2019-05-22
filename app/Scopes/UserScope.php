<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserScope implements Scope
{
    
    private $user;
    
    public function __construct($user)
    {
        $this->user = $user;
    }
    
    public function apply(Builder $builder, Model $model)
    {
        
        if($this->user) {
         
            $role = $this->user->roles()->first()->name;
    
            if ($role == 'administrator') {
                $builder->where('company_id', '=', $this->user->company_id);
            }
            
        }
        
    }
}
