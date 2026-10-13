<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Category\Services\CategoryService;
use Modules\Product\Models\Product;
use Modules\Product\Services\AddonService;

class ProductController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
        private AddonService $addonService
    ) {}

    /**
     * GET /api/v1/products/categories-by-branch/{branchId}
     * Return active categories for a given branch (used by the dashboard AJAX select).
     */
    public function index(int $branchId)
    {
        try {
            $categories = $this->categoryService->active([], $branchId);
            $data = $categories->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->getTranslation('title', app()->getLocale()),
            ]);

            return returnMessage(true, 'Categories', $data);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function show(Product $product)
    {
        try {
            $product->load([
                'images',
                'attributes' => fn ($query) => $query->where('is_active', 1)->orderBy('order')->with([
                    'values',
                ]),
                'addons' => fn ($query) => $query->where('is_active', 1)->with([
                    'values',
                ]),
            ]);

            $data = [
                'id' => $product->id,
                'title' => $product->getTranslation('title', app()->getLocale()),
                'description' => $product->getTranslation('description', app()->getLocale()),
                'price' => $product->price,
                'discounted_price' => $product->discounted_price,
                'is_active' => (bool) $product->is_active,
                'images' => $product->images->map(fn ($image) => [
                    'id' => $image->id,
                    'image' => $image->image,
                ])->values(),
                'attributes' => $product->attributes->map(function ($attribute) {
                    return [
                        'id' => $attribute->id,
                        'name' => $attribute->getTranslation('title', app()->getLocale()),
                        'title' => $attribute->getTranslation('title', app()->getLocale()),
                        'type' => $attribute->type,
                        'required' => (bool) $attribute->required,
                        'multi_select' => (bool) $attribute->multi_select,
                        'override_price' => (bool) $attribute->override_price,
                        'order' => (int) $attribute->order,
                        'values' => $attribute->values->map(fn ($value) => [
                            'id' => $value->id,
                            'name' => $value->getTranslation('value', app()->getLocale()),
                            'value' => $value->getTranslation('value', app()->getLocale()),
                            'price' => (float) $value->price,
                        ])->values(),
                    ];
                })->values(),
                'addons' => $product->addons->map(function ($addon) {
                    return [
                        'id' => $addon->id,
                        'name' => $addon->getTranslation('title', app()->getLocale()),
                        'values' => $addon->values->map(fn ($value) => [
                            'id' => $value->id,
                            'name' => $value->getTranslation('title', app()->getLocale()),
                            'price' => $value->price,
                            'image' => $value->image,
                        ])->values(),
                    ];
                })->values(),
            ];

            return returnMessage(true, 'Product details', $data);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function attributes(Product $product)
    {
        try {
            $product->load([
                'attributes' => fn ($query) => $query->where('is_active', 1)->orderBy('order')->with('values'),
            ]);

            $data = $product->attributes->map(function ($attribute) {
                return [
                    'id' => $attribute->id,
                    'name' => $attribute->getTranslation('title', app()->getLocale()),
                    'title' => $attribute->getTranslation('title', app()->getLocale()),
                    'type' => $attribute->type,
                    'required' => (bool) $attribute->required,
                    'multi_select' => (bool) $attribute->multi_select,
                    'override_price' => (bool) $attribute->override_price,
                    'order' => (int) $attribute->order,
                    'values' => $attribute->values->map(fn ($value) => [
                        'id' => $value->id,
                        'name' => $value->getTranslation('value', app()->getLocale()),
                        'value' => $value->getTranslation('value', app()->getLocale()),
                        'price' => (float) $value->price,
                    ])->values(),
                ];
            })->values();

            return returnMessage(true, 'Product attributes', $data);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function addonsByBranch(int $branchId)
    {
        try {
            $addons = $this->addonService->activeByBranch($branchId);
            $data = $addons->map(fn ($addon) => [
                'id' => $addon->id,
                'name' => $addon->getTranslation('title', app()->getLocale()),
            ]);

            return returnMessage(true, 'Addons', $data);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
