<?php

namespace Modules\Package\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'title_en' => 'required|string|max:191',
            'title_ar' => 'required|string|max:191',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0|lte:price',
            'months' => 'required|integer|min:1|max:24',
            'is_active' => 'nullable|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'title_en.required' => __('validation.required', ['attribute' => __('dashboard/packages.title_en')]),
            'title_ar.required' => __('validation.required', ['attribute' => __('dashboard/packages.title_ar')]),
            'price.required' => __('validation.required', ['attribute' => __('dashboard/packages.price')]),
            'price.numeric' => __('validation.numeric', ['attribute' => __('dashboard/packages.price')]),
            'discounted_price.numeric' => __('validation.numeric', ['attribute' => __('dashboard/packages.discounted_price')]),
            'discounted_price.lte' => __('validation.lte.numeric', ['attribute' => __('dashboard/packages.discounted_price'), 'value' => __('dashboard/packages.price')]),
            'months.required' => __('validation.required', ['attribute' => __('dashboard/packages.months')]),
            'months.integer' => __('validation.integer', ['attribute' => __('dashboard/packages.months')]),
            'months.min' => __('validation.min.numeric', ['attribute' => __('dashboard/packages.months'), 'min' => 1]),
            'months.max' => __('validation.max.numeric', ['attribute' => __('dashboard/packages.months'), 'max' => 24]),
        ];
    }
}

