<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Branch\DTO\BranchDto;
use Modules\Branch\Http\Requests\BranchRequest;
use Modules\Branch\Models\Branch;
use Modules\Branch\Services\BranchService;

class BranchController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private BranchService $branchService)
    {
        $this->middleware(['auth:admin', 'admin.locale']);
    }

    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', Branch::class);
            $branches = $this->branchService->findAll($request->all());
            return view('branch::branches.index', compact('branches'));
        } catch (\Exception $e) {
            abort(403);
        }
    }

    public function create()
    {
        try {
            $this->authorize('create', Branch::class);
            return view('branch::branches.create');
        } catch (\Exception $e) {
            abort(403);
        }
    }

    public function store(BranchRequest $request)
    {
        try {
            $data = (new BranchDto($request))->dataFromRequest();
            $this->branchService->save($data);
            return redirect()->route('admin.branches.index')->with('success', __('dashboard/branches.created_successfully'));
        } catch (\Exception $e) {
            logger()->error('Branch store failed: ' . $e->getMessage());
            return redirect()->back()->with('error', __('dashboard/branches.something_went_wrong'))->withInput();
        }
    }

    public function edit(Branch $branch)
    {
        try {
            $this->authorize('update', $branch);
            return view('branch::branches.edit', compact('branch'));
        } catch (\Exception $e) {
            abort(403);
        }
    }

    public function update(BranchRequest $request, Branch $branch)
    {
        try {
            $data = (new BranchDto($request))->dataFromRequestForUpdate();
            $this->branchService->update($branch->id, $data);
            return redirect()->route('admin.branches.index')->with('success', __('dashboard/branches.updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function activate(Branch $branch)
    {
        try {
            $this->authorize('activate', $branch);
            $this->branchService->activate($branch->id);
            return redirect()->back()->with('success', __('dashboard/branches.status_updated'));
        } catch (\Exception $e) {
            abort(403);
        }
    }

    public function destroy(Branch $branch)
    {
        try {
            $this->authorize('delete', $branch);
            $this->branchService->delete($branch->id);
            return redirect()->route('admin.branches.index')->with('success', __('dashboard/branches.deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
