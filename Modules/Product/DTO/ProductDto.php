<?php

namespace Modules\Product\DTO;

class ProductDto
{
    public $title;
    public $description;
    public $price;
    public $order;
    public $is_active;
    public $branch_id;
    public $category_id;

    public function __construct($request)
    {
        $this->title       = ['en' => $request->get('title_en'), 'ar' => $request->get('title_ar')];
        $this->description = ['en' => $request->get('description_en'), 'ar' => $request->get('description_ar')];
        $this->price       = $request->get('price', 0);
        $this->order       = $request->get('order', 1);
        $this->is_active   = isset($request['is_active']) ? 1 : 0;
        $this->category_id = $request->get('category_id');

        // Branch logic: Super Admin passes branch_id, Branch Manager uses own branch
        $user            = auth('admin')->user();
        $this->branch_id = $user->hasRole(config('product.roles.super_admin'))
            ? $request->get('branch_id')
            : $user->branch_id;
    }

    public function dataFromRequest(): array
    {
        return json_decode(json_encode($this), true);
    }
}
