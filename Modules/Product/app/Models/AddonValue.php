<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AddonValue extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'addon_id',
        'title',
        'price',
        'image',
    ];

    public array $translatable = ['title'];

    public function getImageAttribute($value): ?string
    {
        if ($value !== null && $value !== '') {
            return asset('uploads/'.config('product.addon_values_images_folder').'/'.$value);
        }

        return $value;
    }

    public function addon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }
}
