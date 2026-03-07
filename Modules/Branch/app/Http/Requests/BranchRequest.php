<?php

namespace Modules\Branch\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\Models\Admin;
use Modules\Branch\Models\Branch;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        /**
         * @var Admin $user
         */
        $user  = auth()->guard('admin')->user();
        $routeBranch = $this->route('branch');
        $model = $routeBranch instanceof Branch ? $routeBranch : ($routeBranch ? Branch::findOrFail($routeBranch) : null);
        $id    = $model?->id;

        return match (true) {
            $this->isMethod('POST')                                      => $user->can('create', Branch::class),
            $this->isMethod('PUT') || $this->isMethod('PATCH')           => $user->can('update', $model),
            $this->isMethod('DELETE')                                    => $user->can('delete', $model),
            $this->isMethod('GET') && $id                                => $user->can('update', $model),
            default                                                      => true,
        };
    }

    public function rules(): array
    {
        $routeBranch = $this->route('branch');
        $id = $routeBranch instanceof Branch ? $routeBranch->id : $routeBranch;

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'title_en'   => 'required|string|max:191',
                'title_ar'   => 'required|string|max:191',
                'phone'      => 'nullable|string|max:20',
                'address_en' => 'nullable|string|max:500',
                'address_ar' => 'nullable|string|max:500',
                'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'is_active'  => 'nullable|boolean',
            ];
        }

        return [
            'title_en'       => 'required|string|max:191',
            'title_ar'       => 'required|string|max:191',
            'phone'          => 'nullable|string|max:20',
            'address_en'     => 'nullable|string|max:500',
            'address_ar'     => 'nullable|string|max:500',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'      => 'nullable|boolean',
            // Branch Manager admin account
            'admin_name'     => 'required|string|max:191',
            'admin_email'    => 'required|email|unique:admins,email|max:191',
            'admin_phone'    => 'nullable|string|max:20',
            'admin_password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'title_en.required' => __('validation.required', ['attribute' => __('attributes.title') . ' (EN)']),
            'title_ar.required' => __('validation.required', ['attribute' => __('attributes.title') . ' (AR)']),
            'slug.required'     => __('validation.required', ['attribute' => 'Slug']),
            'slug.unique'       => __('validation.unique', ['attribute' => 'Slug']),
        ];
    }
}
