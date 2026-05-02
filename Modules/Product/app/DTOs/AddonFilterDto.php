<?php

namespace Modules\Product\DTOs;

class AddonFilterDto
{
    public function __construct(private readonly mixed $request) {}

    public function dataFromRequest(): array
    {
        return [
            'title' => $this->request->get('title'),
            'is_active' => $this->request->has('is_active') ? (int) $this->request->get('is_active') : null,
            'branch_id' => $this->request->get('branch_id'),
            'paginate' => $this->request->get('paginate'),
        ];
    }
}
