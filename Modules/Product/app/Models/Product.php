<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Branch\Models\Branch;
use Modules\Category\Models\Category;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'branch_id',
        'category_id',
        'title',
        'description',
        'price',
        'order',
        'is_active',
    ];

    public array $translatable = ['title', 'description'];

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    public function scopeFilter(Builder $query, array $data): Builder
    {
        return $query
            ->when($data['title'] ?? null, fn($q, $v) => $q->where('title->ar', 'LIKE', '%' . $v . '%'))
            ->when($data['branch_id'] ?? null, fn($q, $v) => $q->where('branch_id', $v))
            ->when($data['category_id'] ?? null, fn($q, $v) => $q->where('category_id', $v))
            ->when(isset($data['is_active']), fn($q) => $q->where('is_active', $data['is_active']));
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
