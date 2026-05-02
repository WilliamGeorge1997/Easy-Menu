<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Admin\Models\Admin;
use Modules\Product\Models\Product;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Admin $user */
        $user = auth()->guard('admin')->user();
        $routeProduct = $this->route('product');
        $model = $routeProduct instanceof Product ? $routeProduct : ($routeProduct ? Product::findOrFail($routeProduct) : null);
        $id = $model?->id;

        return match (true) {
            $this->isMethod('POST') && ! $id => $user->can('create', Product::class),
            $this->isMethod('PUT') || $this->isMethod('PATCH') => $user->can('update', $model),
            $this->isMethod('DELETE') => $user->can('delete', $model),
            $this->isMethod('GET') && $id => $user->can('update', $model),
            default => true,
        };
    }

    public function rules(): array
    {
        $routeProduct = $this->route('product');
        $id = $routeProduct instanceof Product ? $routeProduct->id : $routeProduct;
        /** @var Admin $user */
        $user = auth()->guard('admin')->user();

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'title_en' => 'required|string|max:191',
                'title_ar' => 'required|string|max:191',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'order' => 'nullable|integer|min:1',
                'is_active' => 'nullable|in:0,1',
                'category_id' => 'required|exists:categories,id',
                'addon_ids' => 'nullable|array',
                'addon_ids.*' => 'integer|exists:addons,id',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ];
        }

        return [
            'title_en' => 'required|string|max:191',
            'title_ar' => 'required|string|max:191',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'nullable|in:0,1',
            'category_id' => 'required|exists:categories,id',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'integer|exists:addons,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'branch_id' => [
                Rule::requiredIf(fn () => $user?->hasRole(config('product.roles.super_admin'))),
                'nullable',
                'exists:branches,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title_en.required' => __('validation.required', ['attribute' => __('dashboard/products.title_en')]),
            'title_ar.required' => __('validation.required', ['attribute' => __('dashboard/products.title_ar')]),
            'price.required' => __('validation.required', ['attribute' => __('dashboard/products.price')]),
            'price.numeric' => __('validation.numeric', ['attribute' => __('dashboard/products.price')]),
            'category_id.required' => __('validation.required', ['attribute' => __('dashboard/products.category')]),
            'category_id.exists' => __('validation.exists', ['attribute' => __('dashboard/products.category')]),
            'branch_id.required' => __('dashboard/products.branch_required_for_super_admin'),
            'branch_id.exists' => __('validation.exists', ['attribute' => __('dashboard/products.branch')]),
        ];
    }
}
