<?php

namespace Modules\Product\DTOs;

use Modules\Product\Models\Product;

class AttributeDto
{
    public int $product_id;

    public int $branch_id;

    public array $title;

    public bool $required;

    public bool $multi_select;

    public bool $override_price;

    public int $is_active;

    public int $order;

    public string $type;

    public array $values;

    public function __construct(private readonly mixed $request, ?Product $product = null)
    {
        $productId = (int) ($this->request->get('product_id') ?? $product?->id);
        $resolvedProduct = $product ?? Product::findOrFail($productId);

        $this->product_id = $resolvedProduct->id;
        $this->branch_id = (int) $resolvedProduct->branch_id;

        $this->title = [
            'en' => (string) $this->request->get('title_en'),
            'ar' => (string) $this->request->get('title_ar'),
        ];
        $this->required = (bool) $this->request->boolean('required');
        $this->multi_select = (bool) $this->request->boolean('multi_select');
        $this->override_price = (bool) $this->request->boolean('override_price');
        $this->is_active = $this->request->has('is_active') ? 1 : 0;
        $this->order = (int) ($this->request->get('order', 1) ?: 1);
        $this->type = (string) ($this->request->get('type', 'size') ?: 'size');

        $this->values = collect($this->request->get('values', []))
            ->map(function (array $value) {
                return [
                    'value' => [
                        'en' => (string) ($value['value_en'] ?? ''),
                        'ar' => (string) ($value['value_ar'] ?? ''),
                    ],
                    'price' => (float) ($value['price'] ?? 0),
                ];
            })
            ->filter(function ($item) {
                return ! empty($item['value']['en']) || ! empty($item['value']['ar']);
            })
            ->values()
            ->all();
    }

    public function dataFromRequest(): array
    {
        return [
            'product_id' => $this->product_id,
            'branch_id' => $this->branch_id,
            'title' => $this->title,
            'required' => $this->required,
            'multi_select' => $this->multi_select,
            'override_price' => $this->override_price,
            'is_active' => $this->is_active,
            'order' => $this->order,
            'type' => $this->type,
            'values' => $this->values,
        ];
    }
}
