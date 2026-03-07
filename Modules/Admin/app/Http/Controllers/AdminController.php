<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Admin\Services\AdminService;

class AdminController extends Controller
{
    public function __construct(private AdminService $adminService) {}

    public function dashboard()
    {
        return view('admin::dashboard');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $admins = $this->adminService->findAll($request->all(), ['roles', 'branch']);
        return view('admin::admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Switch the authenticated admin's language.
     */
    public function setLanguage(string $lang)
    {
        $supported = ['en', 'ar'];
        if (!in_array($lang, $supported)) {
            return redirect()->back();
        }

        $admin = auth('admin')->user();
        $admin->lang = $lang;
        $admin->save();

        // Clear the cached session locale so middleware picks up the new value
        request()->session()->forget('locale');

        return redirect()->back();
    }

    /**
     * Toggle active status — Super Admin only.
     */
    public function activate(int $id)
    {
        try {
            $admin = $this->adminService->findById($id);
            $this->authorize('activate', $admin);
            $this->adminService->activate($id);
            return redirect()->route('admin.admins.index')->with('success', __('dashboard/admins.updated_successfully'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->back()->with('error', __('dashboard/admins.unauthorized'));
        } catch (\Exception $e) {
            logger()->error('Admin activate failed: ' . $e->getMessage());
            return redirect()->back()->with('error', __('dashboard/admins.something_went_wrong'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $admin = $this->adminService->findById($id, ['roles']);
        return view('admin::admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $data = [
                'name'      => $request->get('name'),
                'email'     => $request->get('email'),
                'phone'     => $request->get('phone'),
                'is_active' => $request->has('is_active') ? 1 : 0,
                'password'  => $request->get('password') ? bcrypt($request->get('password')) : null,
            ];
            $this->adminService->update($id, $data);
            return redirect()->route('admin.admins.index')->with('success', __('dashboard/admins.updated_successfully'));
        } catch (\Exception $e) {
            logger()->error('Admin update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', __('dashboard/admins.something_went_wrong'))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->adminService->delete($id);
            return redirect()->route('admin.admins.index')->with('success', __('dashboard/admins.deleted_successfully'));
        } catch (\Exception $e) {
            logger()->error('Admin delete failed: ' . $e->getMessage());
            return redirect()->back()->with('error', __('dashboard/admins.something_went_wrong'));
        }
    }
}
