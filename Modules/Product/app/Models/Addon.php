<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Models\Branch;
use Spatie\Translatable\HasTranslations;

class Addon extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'branch_id',
        'title',
        'is_active',
    ];

    public array $translatable = ['title'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    public function scopeFilter(Builder $query, array $data): Builder
    {
        return $query
            ->when($data['title'] ?? null, fn ($q, $value) => $q->where('title->ar', 'LIKE', '%'.$value.'%'))
            ->when(isset($data['is_active']), fn ($q) => $q->where('is_active', $data['is_active']))
            ->when($data['branch_id'] ?? null, fn ($q, $branchId) => $q->where('branch_id', $branchId));
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function values(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AddonValue::class);
    }
}
