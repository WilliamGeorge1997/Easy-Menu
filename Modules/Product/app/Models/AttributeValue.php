<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AttributeValue extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'attribute_values';

    protected $fillable = [
        'attribute_id',
        'value',
        'price',
    ];

    public array $translatable = ['value'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function attribute(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
