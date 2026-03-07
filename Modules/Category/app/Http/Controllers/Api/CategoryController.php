<?php

namespace Modules\Category\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Category\Models\Category;

class CategoryController extends Controller
{
    /**
     * GET /api/v1/categories/{id}/products
     * Return all active products belonging to the active category.
     */
    public function products(int $id)
    {
        try {
            $category = Category::active()->findOrFail($id);

            $products = $category->products()
                ->active()
                ->with('images')
                ->orderBy('order')
                ->get();

            return returnMessage(true, 'Category Products', $products);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'not_found');
        }
    }
}
