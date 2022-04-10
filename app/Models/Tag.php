<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

use function PHPUnit\Framework\returnSelf;

class Tag extends Model
{
    use HasFactory, Sluggable, SearchableTrait;
    protected $guarded = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $searchable = [
        'columns' => [
            'tags.name' => 10,
        ],
    ];

    public function status()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    function products() :MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

}
