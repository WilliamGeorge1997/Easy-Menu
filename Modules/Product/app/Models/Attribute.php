<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Models\Branch;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'attributes';

    protected $fillable = [
        'title',
        'required',
        'multi_select',
        'override_price',
        'product_id',
        'branch_id',
        'is_active',
        'order',
        'type',
    ];

    public array $translatable = ['title'];

    protected $casts = [
        'required' => 'boolean',
        'multi_select' => 'boolean',
        'override_price' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, array $data): Builder
    {
        return $query
            ->when($data['title'] ?? null, fn ($q, $value) => $q->where('title->ar', 'LIKE', '%'.$value.'%')->orWhere('title->en', 'LIKE', '%'.$value.'%'))
            ->when(isset($data['is_active']), fn ($q) => $q->where('is_active', $data['is_active']))
            ->when($data['branch_id'] ?? null, fn ($q, $branchId) => $q->where('branch_id', $branchId))
            ->when($data['product_id'] ?? null, fn ($q, $productId) => $q->where('product_id', $productId))
            ->when($data['type'] ?? null, fn ($q, $type) => $q->where('type', $type));
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function values(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
