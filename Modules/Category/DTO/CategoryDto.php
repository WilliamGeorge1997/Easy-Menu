<?php

namespace Modules\Category\DTO;

class CategoryDto
{
    public $title;
    public $description;
    public $image;
    public $order;
    public $is_active;
    public $branch_id;

    public function __construct($request)
    {
        // Translatable fields stored as JSON
        $this->title       = ['en' => $request->get('title_en'), 'ar' => $request->get('title_ar')];
        $this->description = ['en' => $request->get('description_en'), 'ar' => $request->get('description_ar')];

        if ($request->hasFile('image')) {
            $this->image = $request->file('image');
        }

        $this->order     = $request->get('order', 0);
        $this->is_active = isset($request['is_active']) ? 1 : 0;

        // Branch logic: Super Admin passes branch_id, Branch Manager uses own branch
        $user            = auth('admin')->user();
        $this->branch_id = $user->hasRole(config('category.roles.super_admin'))
            ? $request->get('branch_id')
            : $user->branch_id;
    }

    public function dataFromRequest(): array
    {
        $data = json_decode(json_encode($this), true);
        if ($data['image'] === null) {
            unset($data['image']);
        }
        return $data;
    }
}
