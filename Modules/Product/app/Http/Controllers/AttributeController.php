<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\DTOs\AttributeDto;
use Modules\Product\Http\Requests\AttributeRequest;
use Modules\Product\Models\Attribute;
use Modules\Product\Models\Product;
use Modules\Product\Services\AttributeService;

class AttributeController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly AttributeService $service
    ) {
        $this->middleware(['auth:admin', 'admin.locale']);
    }

    public function index(Product $product, Request $request)
    {
        try {
            $this->authorize('viewAny', [Attribute::class, $product]);
            $attributes = $this->service->findByProduct($product, $request->all(), ['values']);

            return view('product::attributes.index', compact('product', 'attributes'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create(Product $product)
    {
        try {
            $this->authorize('create', [Attribute::class, $product]);

            return view('product::attributes.create', compact('product'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(AttributeRequest $request, Product $product)
    {
        try {
            $this->authorize('create', [Attribute::class, $product]);
            $data = (new AttributeDto($request, $product))->dataFromRequest();
            $this->service->save($data);

            return redirect()->route('admin.products.attributes.index', $product->id)
                ->with('success', __('dashboard/attributes.created_successfully'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product, Attribute $attribute)
    {
        try {
            $this->authorize('update', $attribute);
            $attribute->load('values');

            return view('product::attributes.edit', compact('product', 'attribute'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(AttributeRequest $request, Product $product, Attribute $attribute)
    {
        try {
            $this->authorize('update', $attribute);
            $data = (new AttributeDto($request, $product))->dataFromRequest();
            $this->service->update($attribute->id, $data);

            return redirect()->route('admin.products.attributes.index', $product->id)
                ->with('success', __('dashboard/attributes.updated_successfully'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product, Attribute $attribute)
    {
        try {
            $this->authorize('delete', $attribute);
            $this->service->delete($attribute->id);

            return redirect()->route('admin.products.attributes.index', $product->id)
                ->with('success', __('dashboard/attributes.deleted_successfully'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function activate(Product $product, Attribute $attribute)
    {
        try {
            $this->authorize('activate', $attribute);
            $this->service->activate($attribute->id);

            return back()->with('success', __('dashboard/attributes.status_updated_successfully'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
