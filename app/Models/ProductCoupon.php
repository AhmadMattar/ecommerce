<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class ProductCoupon extends Model
{
    use HasFactory, SearchableTrait;

    protected $guarded = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    
    // public function sluggable(): array
    // {
    //     return [
    //         'slug' => [
    //             'source' => 'code'
    //         ]
    //     ];
    // }

    protected $searchable = [
        'columns' => [
            'product_coupons.code' => 10,
            'product_coupons.description' => 10,
        ],
    ];

    protected $dates = ['start_date', 'expire_date'];

    public function status()
    {
        return $this->status ? 'Active' : 'Inactive';
    }
}
