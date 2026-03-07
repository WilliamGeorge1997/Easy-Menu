<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Branch\Models\Branch;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'branch_id',
        'title',
        'description',
        'image',
        'order',
        'is_active',
    ];

    /**
     * Translatable fields stored as JSON in DB.
     */
    public array $translatable = ['title', 'description'];

    /**
     * Serialize dates to a consistent format.
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    /**
     * Scope: only active categories.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope: filter by title, branch_id, or is_active.
     */
    public function scopeFilter(Builder $query, array $data): Builder
    {
        return $query
            ->when($data['title'] ?? null, fn($q, $v) => $q->where('title->ar', 'LIKE', '%' . $v . '%'))
            ->when($data['branch_id'] ?? null, fn($q, $v) => $q->where('branch_id', $v))
            ->when(isset($data['is_active']), fn($q) => $q->where('is_active', $data['is_active']));
    }

    /**
     * Accessor: return full image URL.
     */
    public function getImageAttribute($value): ?string
    {
        if ($value !== null && $value !== '') {
            return asset('uploads/' . config('category.images_folder') . '/' . $value);
        }
        return $value;
    }

    /**
     * Relationship: belongs to a branch.
     */
    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Relationship: has many products.
     */
    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Product\Models\Product::class);
    }
}
