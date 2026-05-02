<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Admin\Models\Admin;

class AddonStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        /** @var Admin $user */
        $user = auth()->guard('admin')->user();

        return [
            'title_en' => 'required|string|max:191',
            'title_ar' => 'required|string|max:191',
            'is_active' => 'nullable|in:0,1',
            'branch_id' => [
                Rule::requiredIf(fn () => $user?->hasRole(config('product.roles.super_admin'))),
                'nullable',
                'integer',
                'exists:branches,id',
            ],
            'values' => 'required|array|min:1',
            'values.*.title_en' => 'required|string|max:191',
            'values.*.title_ar' => 'required|string|max:191',
            'values.*.price' => 'required|numeric|min:0',
            'values.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title_en.required' => __('dashboard/addons.title_en').' '.__('validation.required'),
            'title_ar.required' => __('dashboard/addons.title_ar').' '.__('validation.required'),
            'branch_id.required' => __('dashboard/addons.branch_required_for_super_admin'),
            'values.required' => __('dashboard/addons.values_required'),
            'values.*.title_en.required' => __('dashboard/addons.value_title_en_required'),
            'values.*.title_ar.required' => __('dashboard/addons.value_title_ar_required'),
            'values.*.price.required' => __('dashboard/addons.value_price_required'),
        ];
    }
}
