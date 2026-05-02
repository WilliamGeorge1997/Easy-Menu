<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Branch\Services\BranchService;
use Modules\Product\DTOs\AddonDto;
use Modules\Product\DTOs\AddonFilterDto;
use Modules\Product\Http\Requests\AddonIndexRequest;
use Modules\Product\Http\Requests\AddonStoreRequest;
use Modules\Product\Http\Requests\AddonUpdateRequest;
use Modules\Product\Models\Addon;
use Modules\Product\Services\AddonService;

class AddonController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly AddonService $service,
        private readonly BranchService $branchService
    ) {
        $this->middleware(['auth:admin', 'admin.locale']);
    }

    public function index(AddonIndexRequest $request)
    {
        try {
            $filters = (new AddonFilterDto($request))->dataFromRequest();
            $addons = $this->service->findAll($filters, ['branch', 'values']);
            $branches = $this->branchService->active();

            return view('product::addons.index', compact('addons', 'branches'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $branches = $this->branchService->active();

            return view('product::addons.create', compact('branches'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(AddonStoreRequest $request)
    {
        try {
            $data = (new AddonDto($request))->dataFromRequest();
            $this->service->save($data);

            return redirect()->route('admin.addons.index')->with('success', __('dashboard/addons.created_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Addon $addon)
    {
        try {
            $addon->load(['values', 'branch']);
            $branches = $this->branchService->active();

            return view('product::addons.edit', compact('addon', 'branches'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(AddonUpdateRequest $request, Addon $addon)
    {
        try {
            $data = (new AddonDto($request))->dataFromRequest();
            $this->service->update($addon->id, $data);

            return redirect()->route('admin.addons.index')->with('success', __('dashboard/addons.updated_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Addon $addon)
    {
        try {
            $this->service->delete($addon->id);

            return redirect()->route('admin.addons.index')->with('success', __('dashboard/addons.deleted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function activate(Addon $addon)
    {
        try {
            $this->service->activate($addon->id);

            return back()->with('success', __('dashboard/addons.status_updated_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
