<?php

namespace Modules\Product\DTOs;

use Illuminate\Validation\ValidationException;
use Modules\Admin\Models\Admin;

class AddonDto
{
    public array $title;

    public int $is_active;

    public ?int $branch_id;

    public array $values;

    public function __construct(private readonly mixed $request)
    {
        /** @var Admin $user */
        $user = auth('admin')->user();

        $this->title = [
            'en' => $this->request->get('title_en'),
            'ar' => $this->request->get('title_ar'),
        ];
        $this->is_active = isset($this->request['is_active']) ? 1 : 0;
        $this->branch_id = $user->hasRole(config('product.roles.super_admin'))
            ? (int) $this->request->get('branch_id')
            : (int) $user->branch_id;

        if (empty($this->branch_id)) {
            throw ValidationException::withMessages([
                'branch_id' => __('dashboard/addons.branch_required_for_manager'),
            ]);
        }

        $this->values = collect($this->request->get('values', []))
            ->map(function (array $value, int $index) {
                return [
                    'title' => [
                        'en' => $value['title_en'],
                        'ar' => $value['title_ar'],
                    ],
                    'price' => (float) ($value['price'] ?? 0),
                    'is_active' => isset($value['is_active']) ? 1 : 0,
                    'image' => $this->request->file('values.'.$index.'.image'),
                    'existing_image' => $value['existing_image'] ?? null,
                ];
            })
            ->values()
            ->all();
    }

    public function dataFromRequest(): array
    {
        return [
            'title' => $this->title,
            'is_active' => $this->is_active,
            'branch_id' => $this->branch_id,
            'values' => $this->values,
        ];
    }
}
