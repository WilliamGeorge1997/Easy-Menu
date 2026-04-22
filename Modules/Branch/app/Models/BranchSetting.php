<?php

namespace Modules\Branch\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Branch\Models\Branch;
use Spatie\Translatable\HasTranslations;

class BranchSetting extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['branch_id', 'email', 'logo', 'currency', 'lang', 'about', 'terms', 'facebook', 'youtube', 'instagram', 'x', 'snapchat', 'tiktok', 'whatsapp', 'telegram', 'wifi_username', 'wifi_password'];
    public $translatable = ['currency', 'about', 'terms'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}
