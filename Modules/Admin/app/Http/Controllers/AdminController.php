<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Branch\Models\Branch;
use Modules\Category\Models\Category;
use Illuminate\Validation\Rule;
use Modules\Admin\Services\AdminService;
use Modules\Product\Models\Product;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct(private AdminService $adminService) {}

    public function dashboard(): View
    {
        /** @var \Modules\Admin\Models\Admin $admin */
        $admin = auth('admin')->user();
        $isSuperAdmin = $admin->hasRole(config('admin.roles.super_admin'));
        $branchId = $isSuperAdmin ? null : $admin->branch_id;
        $isBranchScoped = ! $isSuperAdmin;

        $productsBaseQuery = Product::query()
            ->when($isBranchScoped, fn ($query) => $query->where('branch_id', $branchId ?? 0));
        $categoriesBaseQuery = Category::query()
            ->when($isBranchScoped, fn ($query) => $query->where('branch_id', $branchId ?? 0));
        $branchesBaseQuery = Branch::query()
            ->when($isBranchScoped, fn ($query) => $query->where('id', $branchId ?? 0));

        $stats = [
            'products' => (clone $productsBaseQuery)->count(),
            'active_products' => (clone $productsBaseQuery)->where('is_active', true)->count(),
            'inactive_products' => (clone $productsBaseQuery)->where('is_active', false)->count(),
            'categories' => (clone $categoriesBaseQuery)->count(),
            'active_categories' => (clone $categoriesBaseQuery)->where('is_active', true)->count(),
            'branches' => (clone $branchesBaseQuery)->count(),
        ];

        $monthlyProducts = collect(range(5, 0))->map(function (int $monthsAgo) use ($productsBaseQuery) {
            $date = Carbon::now()->subMonths($monthsAgo);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            return [
                'label' => $date->translatedFormat('M Y'),
                'count' => (clone $productsBaseQuery)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count(),
            ];
        });

        $categoriesWithProductCount = Category::query()
            ->when($isBranchScoped, fn ($query) => $query->where('branch_id', $branchId ?? 0))
            ->withCount([
                'products' => fn ($query) => $query->when($isBranchScoped, fn ($productQuery) => $productQuery->where('branch_id', $branchId ?? 0)),
            ])
            ->orderByDesc('products_count')
            ->limit(6)
            ->get();

        $recentProducts = Product::query()
            ->with(['branch:id,title', 'category:id,title'])
            ->when($isBranchScoped, fn ($query) => $query->where('branch_id', $branchId ?? 0))
            ->latest()
            ->limit(8)
            ->get();

        return view('admin::dashboard', compact(
            'isSuperAdmin',
            'stats',
            'monthlyProducts',
            'categoriesWithProductCount',
            'recentProducts'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->ensureSuperAdmin();
        $admins = $this->adminService->findAll($request->all(), ['roles', 'branch']);

        return view('admin::admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->ensureSuperAdmin();
        $roles = Role::query()
            ->where('guard_name', 'admin')
            ->latest('id')
            ->get();

        return view('admin::admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191', 'unique:admins,email'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:admins,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')->where(fn ($q) => $q->where('guard_name', 'admin'))],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => bcrypt($validated['password']),
                'is_active' => $request->boolean('is_active'),
                'lang' => config('app.locale', 'en'),
                'role_id' => $validated['role_id'],
            ];
            $this->adminService->save($data);

            return redirect()->route('admin.admins.index')->with('success', __('dashboard/admins.created_successfully'));
        } catch (\Exception $e) {
            logger()->error('Admin create failed: '.$e->getMessage());

            return redirect()->back()->with('error', __('dashboard/admins.something_went_wrong'))->withInput();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $this->ensureSuperAdmin();

        return view('admin::show');
    }

    /**
     * Switch the authenticated admin's language.
     */
    public function setLanguage(string $lang)
    {
        $supported = ['en', 'ar'];
        if (! in_array($lang, $supported)) {
            return redirect()->back();
        }

        /** @var \Modules\Admin\Models\Admin $admin */
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
        $this->ensureSuperAdmin();
        try {
            $admin = $this->adminService->findById($id);
            $this->authorize('activate', $admin);
            $this->adminService->activate($id);

            return redirect()->route('admin.admins.index')->with('success', __('dashboard/admins.updated_successfully'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->back()->with('error', __('dashboard/admins.unauthorized'));
        } catch (\Exception $e) {
            logger()->error('Admin activate failed: '.$e->getMessage());

            return redirect()->back()->with('error', __('dashboard/admins.something_went_wrong'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $this->ensureSuperAdmin();
        $admin = $this->adminService->findById($id, ['roles']);
        $roles = Role::query()
            ->where('guard_name', 'admin')
            ->latest('id')
            ->get();

        return view('admin::admins.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $this->ensureSuperAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191', Rule::unique('admins', 'email')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('admins', 'phone')->ignore($id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')->where(fn ($q) => $q->where('guard_name', 'admin'))],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'password' => ! empty($validated['password']) ? bcrypt($validated['password']) : null,
            ];
            $admin = $this->adminService->update($id, $data);
            $role = Role::query()
                ->where('id', (int) $validated['role_id'])
                ->where('guard_name', 'admin')
                ->firstOrFail();
            $admin->syncRoles([$role]);

            return redirect()->route('admin.admins.index')->with('success', __('dashboard/admins.updated_successfully'));
        } catch (\Exception $e) {
            logger()->error('Admin update failed: '.$e->getMessage());

            return redirect()->back()->with('error', __('dashboard/admins.something_went_wrong'))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->ensureSuperAdmin();
        try {
            $this->adminService->delete($id);

            return redirect()->route('admin.admins.index')->with('success', __('dashboard/admins.deleted_successfully'));
        } catch (\Exception $e) {
            logger()->error('Admin delete failed: '.$e->getMessage());

            return redirect()->back()->with('error', __('dashboard/admins.something_went_wrong'));
        }
    }

    public function editProfile(): View
    {
        /** @var \Modules\Admin\Models\Admin $admin */
        $admin = auth('admin')->user();
        $admin->loadMissing('roles');

        return view('admin::profile.edit', compact('admin'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var \Modules\Admin\Models\Admin $admin */
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191', Rule::unique('admins', 'email')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('admins', 'phone')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => ! empty($validated['password']) ? bcrypt($validated['password']) : null,
        ];

        try {
            $this->adminService->update($admin->id, $data);

            return redirect()->route('admin.profile.edit')->with('success', __('dashboard/admins.profile_updated_successfully'));
        } catch (\Exception $e) {
            logger()->error('Profile update failed: '.$e->getMessage());

            return redirect()->back()->with('error', __('dashboard/admins.something_went_wrong'))->withInput();
        }
    }

    private function ensureSuperAdmin(): void
    {
        /** @var \Modules\Admin\Models\Admin|null $admin */
        $admin = auth('admin')->user();
        if (! $admin || ! $admin->hasRole(config('admin.roles.super_admin'))) {
            abort(403);
        }
    }
}
