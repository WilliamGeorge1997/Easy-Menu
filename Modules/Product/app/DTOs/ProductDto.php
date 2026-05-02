<?php

namespace Modules\Product\DTOs;

use Modules\Admin\Models\Admin;

class ProductDto
{
    public $title;

    public $description;

    public $price;

    public $discounted_price;

    public $order;

    public $is_active;

    public $branch_id;

    public $category_id;

    public $addon_ids;

    public function __construct($request)
    {
        $this->title = ['en' => $request->get('title_en'), 'ar' => $request->get('title_ar')];
        $this->description = ['en' => $request->get('description_en'), 'ar' => $request->get('description_ar')];
        $this->price = $request->get('price', 0);
        $this->discounted_price = $request->filled('discounted_price')
            ? $request->get('discounted_price')
            : null;
        $this->order = $request->get('order', 1);
        $this->is_active = isset($request['is_active']) ? 1 : 0;
        $this->category_id = $request->get('category_id');
        $this->addon_ids = collect($request->get('addon_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        // Branch logic: Super Admin passes branch_id, Branch Manager uses own branch
        /** @var Admin $user */
        $user = auth('admin')->user();
        $this->branch_id = $user->hasRole(config('product.roles.super_admin'))
            ? $request->get('branch_id')
            : $user->branch_id;
    }

    public function dataFromRequest(): array
    {
        return json_decode(json_encode($this), true);
    }
}
