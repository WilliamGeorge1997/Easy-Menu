<?php

namespace Modules\Branch\DTO;

use Illuminate\Support\Str;

class BranchDto
{
    public $title;
    public $slug;
    public $phone;
    public $address;
    public $image;
    public $is_active;

    public function __construct($request)
    {
        $this->title   = ['en' => $request->get('title_en'), 'ar' => $request->get('title_ar')];
        $this->address = ['en' => $request->get('address_en'), 'ar' => $request->get('address_ar')];
        $this->phone   = $request->get('phone');

        // Auto-generate slug on create from English title
        $this->slug = Str::slug($request->get('title_en'));

        $this->is_active = isset($request['is_active']) ? 1 : 0;

        if ($request->hasFile('image')) {
            $this->image = $request->file('image');
        }
    }

    public function dataFromRequest(): array
    {
        $data = json_decode(json_encode($this), true);
        if ($data['image'] === null) unset($data['image']);
        return $data;
    }

    public function dataFromRequestForUpdate(): array
    {
        $data = $this->dataFromRequest();
        unset($data['slug']);
        return $data;
    }
}
