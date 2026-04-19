<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Category\Services\CategoryService;
use Modules\Product\Models\Product;

class ProductController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    /**
     * GET /api/v1/products/categories-by-branch/{branchId}
     * Return active categories for a given branch (used by the dashboard AJAX select).
     */
    public function index(int $branchId)
    {
        try {
            $categories = $this->categoryService->active([], $branchId);
            $data = $categories->map(fn($c) => [
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
            return returnMessage(true, 'Product details', $product->load('images'));
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
