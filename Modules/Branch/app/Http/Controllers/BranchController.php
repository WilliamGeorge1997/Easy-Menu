<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Branch\DTO\BranchDto;
use Modules\Branch\Http\Requests\BranchRequest;
use Modules\Branch\Http\Requests\BranchSettingRequest;
use Modules\Branch\Http\Requests\WorkHourRequest;
use Modules\Branch\Models\Branch;
use Modules\Branch\Models\BranchSetting;
use Modules\Branch\Models\WorkHour;
use Modules\Branch\Services\BranchService;
use Modules\Common\Helpers\UploadHelper;

class BranchController extends Controller
{
    use AuthorizesRequests;
    use UploadHelper;

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

    public function editWorkHours(Branch $branch)
    {
        try {
            $this->authorize('update', $branch);

            $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            $workHours = $branch->workHours()->get()->keyBy('day');

            return view('branch::branches.work-hours', compact('branch', 'days', 'workHours'));
        } catch (\Exception $e) {
            abort(403);
        }
    }

    public function updateWorkHours(WorkHourRequest $request, Branch $branch)
    {
        try {
            $this->authorize('update', $branch);

            DB::transaction(function () use ($request, $branch) {
                WorkHour::where('branch_id', $branch->id)->delete();

                $rows = collect($request->validated('hours'))
                    ->filter(fn(array $item) => ! (bool) ($item['is_closed'] ?? false))
                    ->map(fn(array $item) => [
                        'branch_id' => $branch->id,
                        'day' => $item['day'],
                        'from' => $item['from'],
                        'to' => $item['to'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                    ->values()
                    ->all();

                if (! empty($rows)) {
                    WorkHour::insert($rows);
                }
            });

            return redirect()
                ->route('admin.branches.work-hours.edit', $branch->id)
                ->with('success', __('dashboard/branches.work_hours_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function editSettings(Branch $branch)
    {
        try {
            $this->authorize('update', $branch);
            $setting = BranchSetting::firstOrNew(['branch_id' => $branch->id]);

            return view('branch::branches.settings', compact('branch', 'setting'));
        } catch (\Exception $e) {
            abort(403);
        }
    }

    public function updateSettings(BranchSettingRequest $request, Branch $branch)
    {
        try {
            $this->authorize('update', $branch);

            DB::transaction(function () use ($request, $branch) {
                $setting = BranchSetting::firstOrNew(['branch_id' => $branch->id]);

                $data = $request->validated();
                unset($data['currency_en'], $data['currency_ar'], $data['about_en'], $data['about_ar'], $data['terms_en'], $data['terms_ar']);

                $data['currency'] = [
                    'en' => $request->input('currency_en'),
                    'ar' => $request->input('currency_ar'),
                ];
                $data['about'] = [
                    'en' => $request->input('about_en'),
                    'ar' => $request->input('about_ar'),
                ];
                $data['terms'] = [
                    'en' => $request->input('terms_en'),
                    'ar' => $request->input('terms_ar'),
                ];

                if ($request->hasFile('logo')) {
                    if (! empty($setting->logo)) {
                        File::delete(public_path('uploads/branch-settings/' . $setting->logo));
                    }
                    $data['logo'] = $this->upload($request->file('logo'), 'branch-settings');
                }

                $setting->fill($data);
                $setting->branch_id = $branch->id;
                $setting->save();
            });

            return redirect()
                ->route('admin.branches.settings.edit', $branch->id)
                ->with('success', __('dashboard/branches.settings_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
