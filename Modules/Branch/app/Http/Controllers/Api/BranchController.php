<?php

namespace Modules\Branch\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Branch\Models\Branch;

class BranchController extends Controller
{
    /**
     * GET /api/v1/branches/{slug}
     * Return a single active branch by its slug.
     */
    public function show(string $slug)
    {
        try {
            $branch = Branch::active()->where('slug', $slug)->firstOrFail();
            return returnMessage(true, 'Branch Details', $branch);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'not_found');
        }
    }

    /**
     * GET /api/v1/branches/{slug}/categories
     * Return all active categories belonging to the branch identified by slug.
     */
    public function categories(string $slug)
    {
        try {
            $branch = Branch::active()->where('slug', $slug)->firstOrFail();

            $categories = $branch->categories()
                ->active()
                ->orderBy('order')
                ->get();

            return returnMessage(true, 'Branch Categories', $categories);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'not_found');
        }
    }
}
