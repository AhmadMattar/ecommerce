<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory, SearchableTrait;
    protected $guarded = [];

    public $timestamps = false;

    protected $searchable = [
        'columns' => [
            'cities.name' => 10,
        ]
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function status()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }
}
