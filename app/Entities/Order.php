<?php

namespace App\Entities;

use App\Scopes\OrderScope;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'subtotal',
        'discount',
        'total',
        'paid',
        'date',
    ];

    protected $casts = [
        'discount' => 'float',
        'subtotal' => 'float',
        'total'    => 'float',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderScope(auth()->guard('api')->user()));
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')->withPivot('id', 'price', 'quantity');
    }

}
