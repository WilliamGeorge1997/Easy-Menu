<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddonIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:191',
            'is_active' => 'nullable|in:0,1',
            'branch_id' => 'nullable|exists:branches,id',
            'paginate' => 'nullable|integer|min:1',
        ];
    }
}
