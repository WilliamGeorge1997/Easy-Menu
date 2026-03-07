<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Admin\Models\Admin;
use Modules\Category\Models\Category;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Admin $user */
        $user          = auth()->guard('admin')->user();
        $routeCategory = $this->route('category');
        $model         = $routeCategory instanceof Category ? $routeCategory : ($routeCategory ? Category::findOrFail($routeCategory) : null);
        $id            = $model?->id;

        return match (true) {
            $this->isMethod('POST') && !$id                    => $user->can('create', Category::class),
            $this->isMethod('PUT') || $this->isMethod('PATCH') => $user->can('update', $model),
            $this->isMethod('DELETE')                          => $user->can('delete', $model),
            $this->isMethod('GET') && $id                      => $user->can('update', $model),
            default                                            => true,
        };
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $routeCategory = $this->route('category');
        $id = $routeCategory instanceof Category ? $routeCategory->id : $routeCategory;

        // UPDATE rules
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'title_en'       => 'required|string|max:191',
                'title_ar'       => 'required|string|max:191',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'order'          => 'nullable|integer|min:0',
                'is_active'      => 'nullable|boolean',
            ];
        }

        // STORE rules (POST)
        return [
            'title_en'       => 'required|string|max:191',
            'title_ar'       => 'required|string|max:191',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order'          => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
            'branch_id'      => [
                Rule::requiredIf(fn() => auth()->guard('admin')->user()?->hasRole(config('category.roles.super_admin'))),
                'nullable',
                'exists:branches,id',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title_en.required'  => 'The English title is required.',
            'title_ar.required'  => 'The Arabic title is required.',
            'branch_id.required' => 'Branch is required for Super Admin.',
            'branch_id.exists'   => 'The selected branch does not exist.',
        ];
    }
}
