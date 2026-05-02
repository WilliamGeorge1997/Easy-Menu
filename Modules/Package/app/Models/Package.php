<?php

namespace Modules\Package\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Package extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'price',
        'discounted_price',
        'months',
        'is_active',
    ];

    public array $translatable = ['title', 'description'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }
}

