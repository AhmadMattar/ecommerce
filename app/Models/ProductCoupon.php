<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class ProductCoupon extends Model
{
    use HasFactory, SearchableTrait;

    protected $guarded = [];

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

    public function discount($total)
    {
        if (!$this->checkDate() || !$this->checkUsedTimes()) {
            return 0;
        }

        return $this->checkGreaterThan($total) ? $this->doProccess($total) : 0;
    }

    public function checkDate()
    {
        return $this->expire_date != '' ?
            (now()->between($this->start_date, $this->expire_date)) ? true : false :
            true;
    }

    public function checkUsedTimes()
    {
        return $this->use_times != '' ?
            ($this->use_times > $this->used_times) ? true : false :
            true;
    }

    public function checkGreaterThan($total)
    {
        return $this->greater_than != '' ?
            ($total <= $this->greater_than) ? true : false :
            true;
    }

    public function doProccess($total)
    {
        switch ($this->type) {
            case 'fixed':
                return $this->value;
                break;

            case 'percentage':
                return ($this->value / 100) * $total;
                break;

            default:
                return 0;
                break;
        }
    }
}
