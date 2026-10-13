<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'title_en' => 'required|string|max:191',
            'title_ar' => 'required|string|max:191',
            'type' => 'required|string|in:size,color,weight,flavor,other',
            'order' => 'nullable|integer|min:1',
            'required' => 'nullable|in:0,1',
            'multi_select' => 'nullable|in:0,1',
            'override_price' => 'nullable|in:0,1',
            'is_active' => 'nullable|in:0,1',
            'values' => 'required|array|min:1',
            'values.*.value_en' => 'required|string|max:191',
            'values.*.value_ar' => 'required|string|max:191',
            'values.*.price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title_en.required' => __('dashboard/attributes.title_en_required'),
            'title_ar.required' => __('dashboard/attributes.title_ar_required'),
            'type.required' => __('dashboard/attributes.type_required'),
            'values.required' => __('dashboard/attributes.values_required'),
            'values.*.value_en.required' => __('dashboard/attributes.value_en_required'),
            'values.*.value_ar.required' => __('dashboard/attributes.value_ar_required'),
            'values.*.price.required' => __('dashboard/attributes.value_price_required'),
        ];
    }
}
