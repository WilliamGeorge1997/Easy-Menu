<?php

namespace Modules\Branch\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\Models\Admin;
use Modules\Branch\Models\WorkHour;
use Modules\Category\Models\Category;
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

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function workHours(): HasMany
    {
        return $this->hasMany(WorkHour::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(BranchSetting::class);
    }
}
