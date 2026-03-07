<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Product\DTO\ProductDto;
use Modules\Product\Services\ProductService;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Models\Product;
use Modules\Branch\Services\BranchService;
use Modules\Category\Services\CategoryService;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private ProductService  $service,
        private BranchService   $branchService,
        private CategoryService $categoryService,
    ) {
        $this->middleware(['auth:admin', 'admin.locale']);
    }

    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', Product::class);
            $products = $this->service->findAll($request->all(), ['branch', 'category', 'images']);
            return view('product::products.index', compact('products'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $this->authorize('create', Product::class);
            $branches   = $this->branchService->active();
            $categories = $this->categoryService->active();
            return view('product::products.create', compact('branches', 'categories'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            $data   = (new ProductDto($request))->dataFromRequest();
            $images = $request->hasFile('images') ? $request->file('images') : [];
            $this->service->save($data, $images);
            return redirect()->route('admin.products.index')->with('success', __('dashboard/products.created_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        try {
            $this->authorize('update', $product);
            $branches = $this->branchService->active();
            // For Super Admin: load categories for the product's branch so the edit form
            // shows the correct category options server-side (AJAX will also re-load on branch change).
            // For Branch Manager: load their own branch's categories.
            $user       = auth()->guard('admin')->user();
            $branchId   = $user->hasRole(config('product.roles.super_admin'))
                ? $product->branch_id
                : $user->branch_id;
            $categories = $this->categoryService->active([], $branchId);
            return view('product::products.edit', compact('product', 'branches', 'categories'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            $data   = (new ProductDto($request))->dataFromRequest();
            $images = $request->hasFile('images') ? $request->file('images') : [];
            $this->service->update($product->id, $data, $images);
            return redirect()->route('admin.products.index')->with('success', __('dashboard/products.updated_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function activate(Product $product)
    {
        try {
            $this->authorize('activate', $product);
            $this->service->activate($product->id);
            return back()->with('success', __('dashboard/products.status_updated'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroyImage(int $imageId)
    {
        try {
            $image   = \Modules\Product\Models\ProductImage::findOrFail($imageId);
            $product = $image->product;
            $this->authorize('update', $product);
            $this->service->deleteImage($imageId);
            return back()->with('success', __('dashboard/products.image_deleted'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(ProductRequest $request, Product $product)
    {
        try {
            $this->service->delete($product->id);
            return redirect()->route('admin.products.index')->with('success', __('dashboard/products.deleted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
