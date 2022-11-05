<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
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
            'products.name' => 10,
            'products.description' => 10,
        ],
    ];
    // start scopes fun's
    public function scopeFeatured($query)
    {
        return $query->whereFeatured(1);
    }

    public function scopeActive($query)
    {
        return $query->whereStatus(1);
    }

    public function scopeActiveCategory($query)
    {
        return $query->whereHas('category', function($query1){
            $query1->whereStatus(1);
        });
    }

    public function scopeHasQuantity($query)
    {
        return $query->where('quantity', '>', 0);
    }
    //end scopes

    public function status()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function feature()
    {
        return $this->featured ? 'Yes' : 'No';
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

    // get the first media
    // we can apply the (Eager Loading) on  products by using this method
    public function firstMedia() :MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')->orderBy('file_sort', 'asc');
    }
    public function media() :MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }
}
