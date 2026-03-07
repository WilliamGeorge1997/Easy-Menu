<?php

namespace Modules\Branch\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Branch extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'phone',
        'address',
        'image',
        'is_active',
    ];

    public $translatable = ['title', 'address'];

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
            ->when($data['title'] ?? null, fn($q) => $q->where('title->ar', 'LIKE', '%' . $data['title'] . '%')
                ->orWhere('title->en', 'LIKE', '%' . $data['title'] . '%'))
            ->when($data['slug'] ?? null, fn($q) => $q->where('slug', 'LIKE', '%' . $data['slug'] . '%'));
    }

    public function getImageAttribute($value): ?string
    {
        if ($value !== null && $value !== '') {
            return asset('uploads/branches/' . $value);
        }
        return $value;
    }

    public function admins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Admin\Models\Admin::class);
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Category\Models\Category::class);
    }
}
