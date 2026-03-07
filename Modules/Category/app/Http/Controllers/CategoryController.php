<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Category\DTO\CategoryDto;
use Modules\Category\Services\CategoryService;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Models\Category;
use Modules\Branch\Services\BranchService;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private CategoryService $service,
        private BranchService $branchService,
    ) {
        $this->middleware(['auth:admin', 'admin.locale']);
    }

    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', Category::class);
            $categories = $this->service->findAll($request->all(), ['branch']);
            return view('category::categories.index', compact('categories'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        try {
            $this->authorize('create', Category::class);
            $branches = $this->branchService->active();
            return view('category::categories.create', compact('branches'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created category.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $data = (new CategoryDto($request))->dataFromRequest();
            $this->service->save($data);
            return redirect()->route('admin.categories.index')->with('success', __('dashboard/categories.created_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(Category $category)
    {
        try {
            $this->authorize('update', $category);
            $branches = $this->branchService->active();
            return view('category::categories.edit', compact('category', 'branches'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified category.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $data = (new CategoryDto($request))->dataFromRequest();
            $this->service->update($category->id, $data);
            return redirect()->route('admin.categories.index')->with('success', __('dashboard/categories.updated_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Toggle the active status of a category.
     */
    public function activate(Category $category)
    {
        try {
            $this->authorize('activate', $category);
            $this->service->activate($category->id);
            return back()->with('success', __('dashboard/categories.status_updated'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy(CategoryRequest $request, Category $category)
    {
        try {
            $this->service->delete($category->id);
            return redirect()->route('admin.categories.index')->with('success', __('dashboard/categories.deleted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
