<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    use HasFactory;
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

    /* this method represent the relation between productCategory & product
        the prdouct has one category
    */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

    /* this method represent the relation between tags & product (many to many)
        the prdouct has one category
    */
    public function tags() :MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function media() :MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
