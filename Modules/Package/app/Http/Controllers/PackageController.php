<?php

namespace Modules\Package\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Package\Http\Requests\PackageRequest;
use Modules\Package\Models\Package;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin.locale']);
    }

    public function index()
    {
        $packages = Package::query()->latest()->paginate(15);

        return view('package::packages.index', compact('packages'));
    }

    public function create()
    {
        return view('package::packages.create');
    }

    public function store(PackageRequest $request)
    {
        Package::query()->create([
            'title' => [
                'en' => $request->string('title_en')->toString(),
                'ar' => $request->string('title_ar')->toString(),
            ],
            'description' => array_filter([
                'en' => $request->input('description_en'),
                'ar' => $request->input('description_ar'),
            ], fn ($v) => $v !== null && $v !== ''),
            'price' => $request->input('price'),
            'discounted_price' => $request->input('discounted_price'),
            'months' => (int) $request->input('months'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.packages.index')->with('success', __('dashboard/packages.created_successfully'));
    }

    public function edit(Package $package)
    {
        return view('package::packages.edit', compact('package'));
    }

    public function update(PackageRequest $request, Package $package)
    {
        $package->update([
            'title' => [
                'en' => $request->string('title_en')->toString(),
                'ar' => $request->string('title_ar')->toString(),
            ],
            'description' => array_filter([
                'en' => $request->input('description_en'),
                'ar' => $request->input('description_ar'),
            ], fn ($v) => $v !== null && $v !== ''),
            'price' => $request->input('price'),
            'discounted_price' => $request->input('discounted_price'),
            'months' => (int) $request->input('months'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.packages.index')->with('success', __('dashboard/packages.updated_successfully'));
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', __('dashboard/packages.deleted_successfully'));
    }

    public function activate(Package $package)
    {
        $package->update(['is_active' => ! $package->is_active]);

        return back()->with('success', __('dashboard/packages.status_updated'));
    }
}
