<?php

namespace Modules\Branch\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Branch\Models\Branch;

class BranchSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $branch = $this->route('branch');
        $branch = $branch instanceof Branch ? $branch : Branch::find($branch);

        return $branch ? $this->user('admin')?->can('update', $branch) : false;
    }

    public function rules(): array
    {
        return [
            'email' => ['nullable', 'email', 'max:191'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'currency_en' => ['nullable', 'string', 'max:100'],
            'currency_ar' => ['nullable', 'string', 'max:100'],
            'lang' => ['nullable', 'in:en,ar'],
            'about_en' => ['nullable', 'string'],
            'about_ar' => ['nullable', 'string'],
            'terms_en' => ['nullable', 'string'],
            'terms_ar' => ['nullable', 'string'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'x' => ['nullable', 'url', 'max:255'],
            'snapchat' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'telegram' => ['nullable', 'string', 'max:100'],
            'wifi_username' => ['nullable', 'string', 'max:191'],
            'wifi_password' => ['nullable', 'string', 'max:191'],
        ];
    }
}
